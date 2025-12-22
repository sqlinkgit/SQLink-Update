#!/bin/bash

GIT_URL="https://github.com/SQLinkgit/SQLink-Update.git"
GIT_DIR="/root/SQLink-Update"
WWW_DIR="/var/www/html"

echo "--- START UPDATE ---"
date

sleep 5

if [ ! -d "$GIT_DIR" ]; then
    cd /root
    git clone $GIT_URL
    if [ $? -ne 0 ]; then
        exit 1
    fi
fi

cd $GIT_DIR
git reset --hard
git pull origin main

if compgen -G "$GIT_DIR/*.py" > /dev/null; then
    sudo cp $GIT_DIR/*.py /usr/local/bin/
    sudo chmod +x /usr/local/bin/*.py
fi

for script in $GIT_DIR/*.sh; do
    filename=$(basename "$script")
    if [ "$filename" != "update_dashboard.sh" ]; then
        sudo cp "$script" /usr/local/bin/
        sudo chmod +x "/usr/local/bin/$filename"
    fi
done

sudo cp $GIT_DIR/*.php $WWW_DIR/
sudo cp $GIT_DIR/*.css $WWW_DIR/
sudo cp $GIT_DIR/*.js $WWW_DIR/
sudo cp $GIT_DIR/*.png $WWW_DIR/

if [ -d "$GIT_DIR/sounds" ]; then
    sudo mkdir -p /usr/local/share/svxlink/sounds/pl_PL/
    sudo cp -r $GIT_DIR/sounds/* /usr/local/share/svxlink/sounds/pl_PL/
    sudo chown -R svxlink:daemon /usr/local/share/svxlink/sounds/pl_PL/
    sudo chmod -R 755 /usr/local/share/svxlink/sounds/pl_PL/
fi

sudo chown -R www-data:www-data $WWW_DIR
sudo chmod -R 755 $WWW_DIR

RC_LOCAL="/etc/rc.local"
CLEANER_SCRIPT="/usr/local/bin/clean_logs_on_boot.sh"

if [ -f "$CLEANER_SCRIPT" ]; then
    if ! grep -q "clean_logs_on_boot.sh" "$RC_LOCAL"; then
        if grep -q "exit 0" "$RC_LOCAL"; then
             sudo sed -i -e '/exit 0/i \/usr/local/bin/clean_logs_on_boot.sh &' "$RC_LOCAL"
        else
             sudo sed -i -e '$i \/usr/local/bin/clean_logs_on_boot.sh &\n' "$RC_LOCAL"
        fi
    fi
fi

NM_CONF="/etc/NetworkManager/conf.d/default-wifi-powersave-on.conf"
if [ ! -f "$NM_CONF" ]; then
    sudo mkdir -p /etc/NetworkManager/conf.d
    echo -e "[connection]\nwifi.powersave = 2" | sudo tee "$NM_CONF" > /dev/null
    sudo systemctl restart NetworkManager
fi

SERVICE_NAME="ping-keepalive.service"
SRC_SERVICE="$GIT_DIR/$SERVICE_NAME"
DEST_SERVICE="/etc/systemd/system/$SERVICE_NAME"

if [ -f "$SRC_SERVICE" ]; then
    sudo cp "$SRC_SERVICE" "$DEST_SERVICE"
    REAL_PING_PATH=$(which ping)
    if [ ! -z "$REAL_PING_PATH" ]; then
        sudo sed -i "s|ExecStart=.*|ExecStart=$REAL_PING_PATH -i 15 8.8.8.8|g" "$DEST_SERVICE"
    fi
    sudo systemctl daemon-reload
    sudo systemctl enable ping-keepalive
    sudo systemctl restart ping-keepalive
fi

if ! cmp -s "$GIT_DIR/update_dashboard.sh" "/usr/local/bin/update_dashboard.sh"; then
    sudo cp "$GIT_DIR/update_dashboard.sh" /usr/local/bin/
    sudo chmod +x /usr/local/bin/update_dashboard.sh
fi

echo "--- END UPDATE ---"