#!/bin/bash

GIT_URL="https://github.com/SQLinkgit/SQLink-Update.git"
GIT_DIR="/root/SQLink-Update"
WWW_DIR="/var/www/html"
SVX_CONF="/etc/svxlink/svxlink.conf"
SOUNDS_DIR="/usr/local/share/svxlink/sounds"

echo "--- START UPDATE ---"
date

OLD_HASH=""
NEW_HASH=""

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
    
    echo "Old Hash: $OLD_HASH"
    echo "New Hash: $NEW_HASH"
    
    if [ $? -ne 0 ]; then 
        echo "STATUS: FAILURE"; 
        exit 1; 
    fi
fi

SCRIPT_PATH="/usr/local/bin/update_dashboard.sh"
REPO_SCRIPT="$GIT_DIR/update_dashboard.sh"

if [ -f "$SCRIPT_PATH" ] && [ -f "$REPO_SCRIPT" ]; then
    if ! cmp -s "$REPO_SCRIPT" "$SCRIPT_PATH"; then
        echo "Aktualizowanie instalatora..."
        cp "$REPO_SCRIPT" "$SCRIPT_PATH"
        chmod +x "$SCRIPT_PATH"
        export SELF_UPDATED=1
        exec "$SCRIPT_PATH"
        exit 0
    fi
fi


if [ -d "$GIT_DIR/PL" ]; then
    echo "Wykryto folder dzwiekow PL w aktualizacji. Rozpoczynam migracje..."

    if [ -d "$SOUNDS_DIR/pl_PL" ]; then
        echo "Usuwanie starego katalogu: $SOUNDS_DIR/pl_PL"
        rm -rf "$SOUNDS_DIR/pl_PL"
    fi

    echo "Instalowanie nowych dzwiekow do: $SOUNDS_DIR/PL"
    mkdir -p "$SOUNDS_DIR"
    cp -R "$GIT_DIR/PL" "$SOUNDS_DIR/"

    
    echo "Nadawanie uprawnien dla $SOUNDS_DIR/PL"
    chmod -R 777 "$SOUNDS_DIR/PL"

    if [ -f "$SVX_CONF" ]; then
        echo "Aktualizacja konfiguracji $SVX_CONF..."
        
        sed -i '/^\[SimplexLogic\]/,/^\[/ s/DEFAULT_LANG=pl_PL/DEFAULT_LANG=PL/' "$SVX_CONF"
        
        sed -i '/^\[ReflectorLogic\]/,/^\[/ s/DEFAULT_LANG=pl_PL/DEFAULT_LANG=PL/' "$SVX_CONF"
        
        echo "Konfiguracja jezyka zaktualizowana."
    else
        echo "Blad: Nie znaleziono pliku $SVX_CONF"
    fi
else
    echo "Brak folderu PL w repozytorium - pomijanie migracji dzwiekow."
fi
# ====================================================


echo "Synchronizacja plikow WWW..."
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
    
    if [ "$filename" != "update_dashboard.sh" ] && [ "$filename" != "wifi_guard.sh" ]; then
        cp "$script" /usr/local/bin/
        chmod +x "/usr/local/bin/$filename"
    fi
done

chown -R www-data:www-data $WWW_DIR
chmod -R 755 $WWW_DIR

cat <<EOF > /usr/local/bin/clean_logs_on_boot.sh
#!/bin/bash
if [ -f /var/log/svxlink ]; then
    TIMESTAMP=\$(date +"%Y%m%d_%H%M%S")
    mkdir -p /root/svxlink_history
    cp /var/log/svxlink "/root/svxlink_history/svxlink_\$TIMESTAMP.log"
    truncate -s 0 /var/log/svxlink
fi
truncate -s 0 /var/www/html/svx_events.log
EOF
chmod +x /usr/local/bin/clean_logs_on_boot.sh


sed -i '/wifi_guard.sh/d' /etc/rc.local


if ! grep -q "clean_logs_on_boot.sh" /etc/rc.local; then
cat <<EOF > /etc/rc.local
#!/bin/sh -e
/usr/local/bin/clean_logs_on_boot.sh &
exit 0
EOF
    chmod +x /etc/rc.local
fi

echo "Usuwanie pozostalosci po Hotspocie..."

systemctl stop wifi_guard.service 2>/dev/null
systemctl disable wifi_guard.service 2>/dev/null
rm /etc/systemd/system/wifi_guard.service 2>/dev/null
systemctl daemon-reload


rm /usr/local/bin/wifi_guard.sh 2>/dev/null


nmcli connection delete "Rescue_AP" 2>/dev/null
nmcli connection delete "SQLink_Ratunkowy" 2>/dev/null
nmcli connection delete "SQLink_WiFi_AP" 2>/dev/null
# ----------------------------------------------------

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