#!/bin/bash

LOG_SOURCE="/var/log/svxlink"
LOG_DEST="/var/www/html/svx_events.log"

touch $LOG_DEST
chown www-data:www-data $LOG_DEST
chmod 644 $LOG_DEST

tail -F -n +1 "$LOG_SOURCE" | grep --line-buffered -E "ReflectorLogic: Connection established|ReflectorLogic: Disconnected|ReflectorLogic: Authentication failed|Node joined|Node left|Talker start|Talker stop|Connected nodes" >> "$LOG_DEST" &