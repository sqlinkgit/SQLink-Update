#!/bin/bash

LOG_FILE="/var/log/wifi_guard.log"

exec > >(tee -a $LOG_FILE) 2>&1

echo "----------------------------------------"
echo "$(date): [START] Strażnik WiFi (v5 - Fix IP & Pass)"
echo "$(date): Czekam 75 sekund na ustabilizowanie sieci..."

sleep 75

echo "$(date): Sprawdzam stan połączenia..."

nmcli radio wifi on
sleep 2

STATUS=$(nmcli -t -f GENERAL.STATE device show wlan0)
echo "$(date): Aktualny status NetworkManager: $STATUS"

if echo "$STATUS" | grep -q ":100"; then
    echo "$(date): [SUKCES] Kod 100 wykryty. Jesteśmy online. Kończę pracę."
    exit 0
else
    echo "$(date): [ALARM] Brak internetu! Uruchamiam procedurę ratunkową..."
    
    nmcli device disconnect wlan0 >/dev/null 2>&1
    sleep 2
    
    nmcli connection delete "Rescue_AP" >/dev/null 2>&1
    
    echo "$(date): Tworzę profil Rescue_AP..."
    
    nmcli con add type wifi ifname wlan0 con-name "Rescue_AP" autoconnect yes ssid "SQLink_WiFi_AP"
    
    nmcli con modify "Rescue_AP" 802-11-wireless.mode ap
    nmcli con modify "Rescue_AP" 802-11-wireless.band bg
    nmcli con modify "Rescue_AP" ipv4.addresses 192.168.4.1/24
    nmcli con modify "Rescue_AP" ipv4.gateway 192.168.4.1
    nmcli con modify "Rescue_AP" ipv4.method shared
    
    nmcli con modify "Rescue_AP" wifi-sec.key-mgmt wpa-psk
    nmcli con modify "Rescue_AP" wifi-sec.psk "sqlink123"
    
    sleep 3

    echo "$(date): Podnoszę profil Rescue_AP..."
    nmcli connection up "Rescue_AP"
    
    RESULT=$?
    if [ $RESULT -eq 0 ]; then
        echo "$(date): [SUKCES] Hotspot uruchomiony."
        echo "$(date): IP: 192.168.4.1 / Hasło: sqlink123"
    else
        echo "$(date): [BŁĄD] Nie udało się uruchomić AP! Kod błędu: $RESULT"

    fi
fi

echo "$(date): [KONIEC] Skrypt zakończył działanie."