#!/bin/bash

LOG_FILE="/var/log/svxlink"
EVENT_FILE="/var/www/html/svx_events.log"
ARCHIVE_DIR="/var/log/svxlink_history"
MAX_ARCHIVES=3

# 1. Stop SvxLink
systemctl stop svxlink

# 2. Archiwizacja
if [ ! -d "$ARCHIVE_DIR" ]; then
    mkdir -p "$ARCHIVE_DIR"
    chown svxlink:daemon "$ARCHIVE_DIR"
    chmod 755 "$ARCHIVE_DIR"
fi

if [ -s "$LOG_FILE" ]; then
    mv "$LOG_FILE" "$ARCHIVE_DIR/svxlink_$(date +"%Y%m%d_%H%M%S").log"
fi

# 3. Reset Logów
touch "$LOG_FILE"
chown svxlink:daemon "$LOG_FILE"
chmod 644 "$LOG_FILE"

echo "" > "$EVENT_FILE"
chown www-data:www-data "$EVENT_FILE"
chmod 644 "$EVENT_FILE"

# 4. Sprzątanie archiwum
ls -1t "$ARCHIVE_DIR"/svxlink_*.log 2>/dev/null | tail -n +$((MAX_ARCHIVES + 1)) | xargs -r rm --

# 5. START USŁUG
pkill -f "tail -F"
/usr/local/bin/svx_event_logger.sh &

sleep 2

systemctl start svxlink