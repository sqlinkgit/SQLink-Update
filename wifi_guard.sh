#!/bin/bash

LOG_FILE="/var/log/wifi_guard.log"
exec > >(tee -a $LOG_FILE) 2>&1

echo "----------------------------------------"
echo "$(date): [START] Strażnik WiFi (v7 - Clean & Split)"
echo "$(date): Czekam 50 sekund na start systemu..."

sleep 50

STATUS=$(nmcli -t -f GENERAL.STATE device show wlan0)
echo "$(date): Status: $STATUS"

if echo "$STATUS" | grep -q ":100"; then
    echo "$(date): [OK] Jesteśmy online. Koniec."
    exit 0
fi

echo "$(date): [ALARM] Brak sieci! Przygotowuję środowisko..."

echo "$(date): Czyszczę procesy i interfejsy..."

nmcli device disconnect wlan0 >/dev/null 2>&1

nmcli connection delete "Rescue_AP" >/dev/null 2>&1

killall dnsmasq >/dev/null 2>&1
rm /var/lib/misc/dnsmasq.leases >/dev/null 2>&1

ip addr flush dev wlan0
ip link set wlan0 down
sleep 1
ip link set wlan0 up
sleep 2

echo "$(date): Tworzę nowy profil..."

nmcli con add type wifi ifname wlan0 mode ap con-name "Rescue_AP" ssid "SQLink_WiFi_AP" autoconnect yes

nmcli con modify "Rescue_AP" wifi-sec.key-mgmt wpa-psk
nmcli con modify "Rescue_AP" wifi-sec.psk "sqlink123"

nmcli con modify "Rescue_AP" ipv4.addresses 192.168.4.1/24
nmcli con modify "Rescue_AP" ipv4.method shared

sleep 2

echo "$(date): Podnoszę sieć..."

nmcli connection up "Rescue_AP"
RESULT=$?

if [ $RESULT -eq 0 ]; then
    echo "$(date): [SUKCES] Hotspot uruchomiony poprawnie."
    echo "$(date): IP: 192.168.4.1 Hasło: sqlink123"
else
    echo "$(date): [BŁĄD] NetworkManager zgłosił błąd: $RESULT"
    echo "$(date): Próba ratunkowa - wymuszam IP ręcznie..."
    ifconfig wlan0 192.168.4.1 netmask 255.255.255.0 up
fi

exit 0