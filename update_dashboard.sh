#!/bin/bash

GIT_URL="https://github.com/SQLinkgit/SQLink-Update.git"
GIT_DIR="/root/SQLink-Update"
WWW_DIR="/var/www/html"

echo "--- START UPDATE ---"
date
sleep 3

if [ ! -d "$GIT_DIR" ]; then
    cd /root
    git clone $GIT_URL
else
    cd $GIT_DIR
    git config core.fileMode false
    git fetch --all
    git reset --hard origin/main
    git pull origin main
fi

SCRIPT_PATH="/usr/local/bin/update_dashboard.sh"
REPO_SCRIPT="$GIT_DIR/update_dashboard.sh"

if [ -f "$SCRIPT_PATH" ] && [ -f "$REPO_SCRIPT" ]; then
    if ! cmp -s "$REPO_SCRIPT" "$SCRIPT_PATH"; then
        echo "Aktualizowanie instalatora..."
        sudo cp "$REPO_SCRIPT" "$SCRIPT_PATH"
        sudo chmod +x "$SCRIPT_PATH"
        exec sudo "$SCRIPT_PATH"
        exit 0
    fi
fi

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

sudo cp $GIT_DIR/*.css $WWW_DIR/ 2>/dev/null
sudo cp $GIT_DIR/*.js $WWW_DIR/ 2>/dev/null
sudo cp $GIT_DIR/*.png $WWW_DIR/ 2>/dev/null
sudo cp $GIT_DIR/*.php $WWW_DIR/

if [ ! -f "$WWW_DIR/radio_config.json" ] && [ -f "$GIT_DIR/radio_config.json" ]; then
    sudo cp $GIT_DIR/radio_config.json $WWW_DIR/
fi

if [ -d "$GIT_DIR/sounds" ]; then
    sudo mkdir -p /usr/local/share/svxlink/sounds/pl_PL/
    sudo cp -r $GIT_DIR/sounds/* /usr/local/share/svxlink/sounds/pl_PL/
    sudo chown -R svxlink:daemon /usr/local/share/svxlink/sounds/pl_PL/
    sudo chmod -R 755 /usr/local/share/svxlink/sounds/pl_PL/
fi

sudo chown -R www-data:www-data $WWW_DIR
sudo chmod -R 755 $WWW_DIR


cat <<EOF > /usr/local/bin/clean_logs_on_boot.sh
#!/bin/bash
# Archiwizacja starego loga systemowego
if [ -f /var/log/svxlink ]; then
    TIMESTAMP=\$(date +"%Y%m%d_%H%M%S")
    mkdir -p /root/svxlink_history
    cp /var/log/svxlink "/root/svxlink_history/svxlink_\$TIMESTAMP.log"
    truncate -s 0 /var/log/svxlink
fi
# Czyszczenie loga WWW (dla pewnosci)
truncate -s 0 /var/www/html/svx_events.log
EOF
chmod +x /usr/local/bin/clean_logs_on_boot.sh


cat <<EOF > /etc/rc.local
#!/bin/sh -e
#
# rc.local
# This script is executed at the end of each multiuser runlevel.

/usr/local/bin/clean_logs_on_boot.sh &

exit 0
EOF
chmod +x /etc/rc.local


sudo systemctl stop svxlink-logger 2>/dev/null
sudo pkill -f "tail -F"
sudo pkill -f "tail -n 0"


if [ -f /var/www/html/svx_events.log ]; then
    sudo truncate -s 0 /var/www/html/svx_events.log
    sudo chmod 666 /var/www/html/svx_events.log
fi

LOGGER_SERVICE="/etc/systemd/system/svxlink-logger.service"
SMART_CMD="/bin/sh -c '/usr/bin/tail -n 0 -F /var/log/svxlink | /usr/bin/grep --line-buffered -E \"Connected nodes|Node joined|Node left|Talker start|Selecting TG\" >> /var/www/html/svx_events.log'"

echo "[Unit]" > "$LOGGER_SERVICE"
echo "Description=SvxLink Web Dashboard Smart Logger" >> "$LOGGER_SERVICE"
echo "After=network.target svxlink.service" >> "$LOGGER_SERVICE"
echo "" >> "$LOGGER_SERVICE"
echo "[Service]" >> "$LOGGER_SERVICE"
echo "Type=simple" >> "$LOGGER_SERVICE"
echo "ExecStart=$SMART_CMD" >> "$LOGGER_SERVICE"
echo "Restart=always" >> "$LOGGER_SERVICE"
echo "RestartSec=5" >> "$LOGGER_SERVICE"
echo "User=root" >> "$LOGGER_SERVICE"
echo "" >> "$LOGGER_SERVICE"
echo "[Install]" >> "$LOGGER_SERVICE"
echo "WantedBy=multi-user.target" >> "$LOGGER_SERVICE"

sudo systemctl daemon-reload
sudo systemctl enable svxlink-logger
sudo systemctl restart svxlink-logger

SERVICE_FILE="/etc/systemd/system/ping-keepalive.service"
PING_PATH=$(which ping)
if [ -z "$PING_PATH" ]; then PING_PATH="/bin/ping"; fi

if [ ! -f "$SERVICE_FILE" ]; then
    echo "[Unit]" > "$SERVICE_FILE"
    echo "Description=Ping Keepalive" >> "$SERVICE_FILE"
    echo "After=network.target" >> "$SERVICE_FILE"
    echo "" >> "$SERVICE_FILE"
    echo "[Service]" >> "$SERVICE_FILE"
    echo "ExecStart=$PING_PATH -i 15 8.8.8.8" >> "$SERVICE_FILE"
    echo "Restart=always" >> "$SERVICE_FILE"
    echo "User=root" >> "$SERVICE_FILE"
    echo "" >> "$SERVICE_FILE"
    echo "[Install]" >> "$SERVICE_FILE"
    echo "WantedBy=multi-user.target" >> "$SERVICE_FILE"

    sudo systemctl daemon-reload
    sudo systemctl enable ping-keepalive
fi

sudo systemctl restart ping-keepalive

echo "--- END UPDATE ---"