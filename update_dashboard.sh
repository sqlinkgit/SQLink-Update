#!/bin/bash

GIT_URL="https://github.com/SQLinkgit/SQLink-Update.git"
GIT_DIR="/root/SQLink-Update"
WWW_DIR="/var/www/html"

echo "--- START UPDATE ---"
date

if [ ! -d "$GIT_DIR" ]; then
    cd /root
    git clone $GIT_URL
    PULL_OUT="Cloned"
else
    cd $GIT_DIR
    git config core.fileMode false
    git fetch --all
    git reset --hard origin/main
    PULL_OUT=$(git pull origin main 2>&1)
    echo "$PULL_OUT"
    
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
    if [ "$filename" != "update_dashboard.sh" ]; then
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

cat <<EOF > /etc/rc.local
#!/bin/sh -e
/usr/local/bin/clean_logs_on_boot.sh &
exit 0
EOF
chmod +x /etc/rc.local

if [[ "$SELF_UPDATED" == "1" ]]; then
    echo "STATUS: SUCCESS"
elif [[ "$PULL_OUT" == *"Already up to date"* ]]; then
    echo "STATUS: UP_TO_DATE"
else
    echo "STATUS: SUCCESS"
fi

exit 0