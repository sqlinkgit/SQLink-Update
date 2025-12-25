#!/bin/bash

# Ustawiamy plik logu
LOG_FILE="/var/log/wifi_guard.log"
exec > >(tee -a $LOG_FILE) 2>&1

echo "----------------------------------------"
echo "$(date): [START] Strażnik WiFi (v6 - One Shot)"
echo "$(date): Czekam 50 sekund na start systemu..."

sleep 50

# Sprawdzamy status
STATUS=$(nmcli -t -f GENERAL.STATE device show wlan0)
echo "$(date): Status: $STATUS"

if echo "$STATUS" | grep -q ":100"; then
    echo "$(date): [OK] Jesteśmy online. Koniec."
    exit 0
fi

echo "$(date): [ALARM] Brak sieci! Tworzę Hotspot..."

# 1. Sprzątanie
nmcli device disconnect wlan0 >/dev/null 2>&1
nmcli connection delete "Rescue_AP" >/dev/null 2>&1
sleep 2

# 2. TWORZENIE (Wszystko w jednej komendzie - to klucz do sukcesu)
# Tworzy sieć + Ustawia WPA2 + Ustawia IP + Ustawia DHCP (shared)
echo "$(date): Wykonuję komendę tworzenia..."

nmcli con add type wifi ifname wlan0 mode ap con-name "Rescue_AP" ssid "SQLink_WiFi_AP" \
    wifi-sec.key-mgmt wpa-psk wifi-sec.psk "sqlink123" \
    ipv4.addresses 192.168.4.1/24 ipv4.method shared

RESULT=$?

if [ $RESULT -eq 0 ]; then
    echo "$(date): [SUKCES] Profil utworzony. Podnoszę sieć..."
    sleep 3
    nmcli connection up "Rescue_AP"
    
    # Dodatkowe wymuszenie IP, gdyby NetworkManager protestował
    # (To naprawia błąd, gdy strona się nie ładuje)
    ifconfig wlan0 192.168.4.1 netmask 255.255.255.0 up
    
    echo "$(date): GOTOWE. IP: 192.168.4.1 Hasło: sqlink123"
else
    echo "$(date): [KRYTYCZNY BŁĄD] Nie udało się utworzyć profilu! Kod: $RESULT"
fi

exit 0