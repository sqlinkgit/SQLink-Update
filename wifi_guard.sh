#!/bin/bash

# Ustawiamy plik logu
LOG_FILE="/var/log/wifi_guard.log"
exec > >(tee -a $LOG_FILE) 2>&1

echo "----------------------------------------"
echo "$(date): [START] Strażnik WiFi (v12 - Auto-Cleanup)"
echo "$(date): Czekam 50 sekund na start systemu..."

sleep 50

# --- KROK 1: SPRAWDZENIE KABLA (ETHERNET) ---
ETH_STATUS=$(nmcli -t -f GENERAL.STATE device show eth0 2>/dev/null)

# Sprawdzamy czy kabel ma status 100 (connected)
if echo "$ETH_STATUS" | grep -q ":100"; then
    echo "$(date): [OK] Kabel podłączony (Internet działa)."
    
    # NOWOŚĆ: Sprzątanie! Jeśli mamy kabel, a AP wisi w tle - kasujemy go.
    if nmcli connection show "Rescue_AP" >/dev/null 2>&1; then
        echo "$(date): Wykryto zbędny Hotspot Ratunkowy. Wyłączam go..."
        nmcli connection delete "Rescue_AP" >/dev/null 2>&1
        echo "$(date): Hotspot wyłączony."
    fi
    
    exit 0
fi

# --- KROK 2: SPRAWDZENIE CZY JESTEŚMY POŁĄCZENI Z WIFI DOMOWYM ---
WIFI_STATUS=$(nmcli -t -f GENERAL.STATE device show wlan0 2>/dev/null)
if echo "$WIFI_STATUS" | grep -q ":100"; then
    echo "$(date): [OK] WiFi połączone z routerem. Koniec."
    exit 0
fi

echo "$(date): [ALARM] Brak sieci! Uruchamiam OTWARTY Hotspot Ratunkowy..."

# --- KROK 3: START PROCEDURY RATUNKOWEJ ---
echo "$(date): Resetuję radio..."
systemctl stop NetworkManager
rmmod xradio_wlan
sleep 1
modprobe xradio_wlan
sleep 2
systemctl start NetworkManager
sleep 10
nmcli radio wifi on
sleep 3

# --- KROK 4: KONFIGURACJA AP (BEZ HASŁA) ---
echo "$(date): Tworzę profil Rescue_AP..."

nmcli connection delete "Rescue_AP" >/dev/null 2>&1

# Tworzymy sieć otwartą
nmcli con add type wifi ifname wlan0 mode ap con-name "Rescue_AP" ssid "SQLink_Ratunkowy" autoconnect yes

# Stabilność (BG / Kanał 6 / Stały MAC)
nmcli con modify "Rescue_AP" 802-11-wireless.band bg
nmcli con modify "Rescue_AP" 802-11-wireless.channel 6
nmcli con modify "Rescue_AP" wifi.cloned-mac-address preserve

# IP i DHCP
nmcli con modify "Rescue_AP" ipv4.addresses 192.168.4.1/24
nmcli con modify "Rescue_AP" ipv4.method shared

# Wyłączamy oszczędzanie energii
iw dev wlan0 set power_save off

sleep 2

# --- KROK 5: PODNOSZENIE SIECI ---
echo "$(date): Uruchamiam Hotspot..."
nmcli connection up "Rescue_AP"

RESULT=$?
if [ $RESULT -eq 0 ]; then
    echo "$(date): [SUKCES] Otwarty Hotspot aktywny."
    echo "$(date): SSID: SQLink_Ratunkowy"
else
    echo "$(date): [BŁĄD] Kod błędu: $RESULT"
    ifconfig wlan0 192.168.4.1 netmask 255.255.255.0 up
fi

exit 0