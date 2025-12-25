#!/bin/bash


LOG_FILE="/var/log/wifi_guard.log"
exec > >(tee -a $LOG_FILE) 2>&1

echo "----------------------------------------"
echo "$(date): [START] Strażnik WiFi (v16 - Universal Secure)"
echo "$(date): Czekam 50 sekund na start systemu..."
sleep 50


if grep -q "1" /sys/class/net/eth0/carrier 2>/dev/null; then
    echo "$(date): [OK] Kabel podłączony. Internet jest."
    

    if nmcli connection show "Rescue_AP" >/dev/null 2>&1; then
        nmcli connection delete "Rescue_AP"
    fi
    exit 0
fi

echo "$(date): [INFO] Brak kabla. Sprawdzam status WiFi..."


WIFI_STATE=$(nmcli -t -f GENERAL.STATE device show wlan0 2>/dev/null)

if echo "$WIFI_STATE" | grep -q ":100"; then
    echo "$(date): [OK] WiFi jest połączone z siecią użytkownika."
    exit 0
fi


echo "$(date): [ALARM] Brak połączenia z żadną znaną siecią. Uruchamiam Hotspot."

echo "$(date): Reset sterownika WiFi (Brutal Force)..."

systemctl stop NetworkManager
killall wpa_supplicant 2>/dev/null
sleep 1
rmmod xradio_wlan
sleep 2
modprobe xradio_wlan
sleep 3
systemctl start NetworkManager
sleep 8
nmcli radio wifi on
sleep 2

nmcli connection delete "Rescue_AP" >/dev/null 2>&1

echo "$(date): Tworzę sieć SQLink_WiFi_AP..."

nmcli con add type wifi ifname wlan0 mode ap con-name "Rescue_AP" ssid "SQLink_WiFi_AP" autoconnect yes
nmcli con modify "Rescue_AP" wifi-sec.key-mgmt wpa-psk
nmcli con modify "Rescue_AP" wifi-sec.psk "sqlink123"

nmcli con modify "Rescue_AP" ipv4.addresses 192.168.4.1/24
nmcli con modify "Rescue_AP" ipv4.gateway 192.168.4.1
nmcli con modify "Rescue_AP" ipv4.method shared
nmcli con modify "Rescue_AP" 802-11-wireless.band bg
nmcli con modify "Rescue_AP" 802-11-wireless.channel 1
iw dev wlan0 set power_save off

echo "$(date): Podnoszę Hotspot..."
nmcli connection up "Rescue_AP"

if [ $? -eq 0 ]; then
    echo "$(date): [SUKCES] Hotspot działa. SSID: SQLink_WiFi_AP IP: 192.168.4.1"
else
    echo "$(date): [BŁĄD] Nie udało się uruchomić Hotspotu."
    ifconfig wlan0 192.168.4.1 netmask 255.255.255.0 up
fi

exit 0