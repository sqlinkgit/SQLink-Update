#!/bin/bash

GIT_URL="https://github.com/sqlinkgit/SQLink-Update.git"
GIT_DIR="/root/SQLink-Update"
WWW_DIR="/var/www/html"

echo "--- START UPDATE ---"
date

OLD_HASH=""
NEW_HASH=""

echo "Optymalizacja systemu i sieci..."


if ! dpkg -l | grep -q haveged; then
    echo "Instalacja haveged i dnsmasq..."
    apt-get update
    apt-get install -y dnsmasq-base dnsmasq haveged
    systemctl enable haveged
    systemctl start haveged
fi


systemctl stop dnsmasq 2>/dev/null
systemctl disable dnsmasq 2>/dev/null


NM_CONF="/etc/NetworkManager/NetworkManager.conf"
if [ -f "$NM_CONF" ]; then
    if ! grep -q "dns=dnsmasq" "$NM_CONF"; then
        sed -i '/\[main\]/a dns=dnsmasq' "$NM_CONF"
    fi

    if ! grep -q "wifi.scan-rand-mac-address=no" "$NM_CONF"; then
        echo -e "\n[device]\nwifi.scan-rand-mac-address=no" >> "$NM_CONF"
    fi
    systemctl restart NetworkManager
fi

HOSTNAME=$(hostname)
if ! grep -q "127.0.1.1 $HOSTNAME" /etc/hosts; then
    echo "127.0.1.1 $HOSTNAME" >> /etc/hosts
fi

if [ ! -d "$GIT_DIR" ]; then
    cd /root
    git clone $GIT_URL
    NEW_HASH="CLONED"
else
    cd $GIT_DIR
    git config core.fileMode false
    OLD_HASH=$(git rev-parse HEAD)
    git fetch --all
    git reset --hard origin/main
    NEW_HASH=$(git rev-parse HEAD)
    
    if [ $? -ne 0 ]; then echo "STATUS: FAILURE"; exit 1; fi
fi

SCRIPT_PATH="/usr/local/bin/update_dashboard.sh"
REPO_SCRIPT="$GIT_DIR/update_dashboard.sh"

if [ -f "$SCRIPT_PATH" ] && [ -f "$REPO_SCRIPT" ]; then
    if ! cmp -s "$REPO_SCRIPT" "$SCRIPT_PATH"; then
        cp "$REPO_SCRIPT" "$SCRIPT_PATH"
        chmod +x "$SCRIPT_PATH"
        export SELF_UPDATED=1
        exec "$SCRIPT_PATH"
        exit 0
    fi
fi

cp $GIT_DIR/*.css $WWW_DIR/ 2>/dev/null
cp $GIT_DIR/*.js $WWW_DIR/ 2>/dev/null
cp $GIT_DIR/*.png $WWW_DIR/ 2>/dev/null
cp $GIT_DIR/*.php $WWW_DIR/

if [ ! -f "$WWW_DIR/radio_config.json" ] && [ -f "$GIT_DIR/radio_config.json" ]; then
    cp $GIT_DIR/radio_config.json $WWW_DIR/
fi

if compgen -G "$GIT_DIR/*.py" > /dev/null; then
    cp $GIT_DIR/*.py /usr/local/bin/
    chmod +x /usr/local/bin/*.py
fi

for script in $GIT_DIR/*.sh; do
    filename=$(basename "$script")
    if [ "$filename" != "update_dashboard.sh" ]; then
        cp "$script" /usr/local/bin/
        chmod +x "/usr/local/bin/$filename"
    fi
done

if [ -f "$GIT_DIR/wifi_guard.sh" ]; then
    cp "$GIT_DIR/wifi_guard.sh" /usr/local/bin/wifi_guard.sh
    chmod +x /usr/local/bin/wifi_guard.sh
fi

crontab -l 2>/dev/null | grep -v "wifi_guard.sh" | grep -v "wifi_guardian.sh" | crontab -
sed -i '/wifi_guard.sh/d' /etc/rc.local

cat <<EOF > /etc/systemd/system/wifi_guard.service
[Unit]
Description=SQLink WiFi Guardian
After=network.target network-online.target NetworkManager.service
Wants=network-online.target

[Service]
Type=simple
ExecStart=/usr/local/bin/wifi_guard.sh
Restart=on-failure
RestartSec=10
User=root

[Install]
WantedBy=multi-user.target
EOF

systemctl daemon-reload
systemctl enable wifi_guard.service
systemctl restart wifi_guard.service

chown -R www-data:www-data $WWW_DIR
chmod -R 755 $WWW_DIR

if [[ "$SELF_UPDATED" == "1" ]]; then
    echo "STATUS: SUCCESS"
elif [[ "$NEW_HASH" == "CLONED" ]]; then
    echo "STATUS: SUCCESS"
elif [[ "$OLD_HASH" != "$NEW_HASH" ]]; then
    echo "STATUS: SUCCESS"
else
    echo "STATUS: UP_TO_DATE"
fi

exit 0