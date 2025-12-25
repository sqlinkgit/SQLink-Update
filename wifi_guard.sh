#!/bin/bash


LOG_FILE="/var/log/wifi_guard.log"
exec > >(tee -a $LOG_FILE) 2>&1

echo "----------------------------------------"
echo "$(date): [START] Strażnik WiFi (v8 - XR819 Stability Fix)"
echo "$(date): Czekam 50 sekund na start systemu..."

sleep 50


STATUS=$(nmcli -t -f GENERAL.STATE device show wlan0)
echo "$(date): Status: $STATUS"

if echo "$STATUS" | grep -q ":100"; then
    echo "$(date): [OK] Jesteśmy online. Koniec."
    exit 0
fi

echo "$(date): [ALARM] Brak sieci! Restartuję sterownik WiFi..."

nmcli radio wifi off
rmmod xradio_wlan
sleep 1
modprobe xradio_wlan
sleep 2
nmcli radio wifi on
sleep 3

# 2. Czyszczenie
nmcli connection delete "Rescue_AP" >/dev/null 2>&1
killall dnsmasq >/dev/null 2>&1
ip addr flush dev wlan0

echo "$(date): Tworzę profil Rescue_AP..."

nmcli con add type wifi ifname wlan0 mode ap con-name "Rescue_AP" ssid "SQLink_WiFi_AP" autoconnect yes

nmcli con modify "Rescue_AP" 802-11-wireless.mode ap
nmcli con modify "Rescue_AP" 802-11-wireless.band bg
nmcli con modify "Rescue_AP" 802-11-wireless.channel 6

nmcli con modify "Rescue_AP" wifi-sec.key-mgmt wpa-psk
nmcli con modify "Rescue_AP" wifi-sec.psk "sqlink123"

nmcli con modify "Rescue_AP" ipv4.addresses 192.168.4.1/24
nmcli con modify "Rescue_AP" ipv4.method shared

iw dev wlan0 set power_save off

sleep 2

echo "$(date): Podnoszę sieć..."
nmcli connection up "Rescue_AP"

RESULT=$?
if [ $RESULT -eq 0 ]; then
    echo "$(date): [SUKCES] Hotspot uruchomiony na Kanale 6."
    echo "$(date): IP: 192.168.4.1"
else
    echo "$(date): [BŁĄD] Kod błędu: $RESULT"
    ifconfig wlan0 192.168.4.1 netmask 255.255.255.0 up
fi

exit 0