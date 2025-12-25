#!/bin/bash

# Ustawiamy plik logu
LOG_FILE="/var/log/wifi_guard.log"
exec > >(tee -a $LOG_FILE) 2>&1

echo "----------------------------------------"
echo "$(date): [START] Strażnik WiFi (v10 - Nuclear Stability)"
echo "$(date): Czekam 50 sekund na start systemu..."

sleep 50

# --- KROK 1: SPRAWDZENIE KABLA (ETHERNET) ---
# Jeśli kabel jest podłączony, nie dotykamy WiFi!
ETH_STATUS=$(nmcli -t -f GENERAL.STATE device show eth0 2>/dev/null)
echo "$(date): Status ETH0: $ETH_STATUS"

if echo "$ETH_STATUS" | grep -q ":100"; then
    echo "$(date): [OK] Połączenie kablowe aktywne. Nie uruchamiam Hotspota."
    exit 0
fi

# --- KROK 2: SPRAWDZENIE WIFI ---
WIFI_STATUS=$(nmcli -t -f GENERAL.STATE device show wlan0 2>/dev/null)
echo "$(date): Status WLAN0: $WIFI_STATUS"

if echo "$WIFI_STATUS" | grep -q ":100"; then
    echo "$(date): [OK] WiFi połączone z domową siecią. Koniec."
    exit 0
fi

echo "$(date): [ALARM] Brak sieci (ani kabel, ani WiFi)! Rozpoczynam procedurę..."

# --- KROK 3: TOTALNY RESET STEROWNIKA (METODA SIŁOWA) ---
echo "$(date): Zatrzymuję NetworkManager..."
systemctl stop NetworkManager
sleep 2

echo "$(date): Resetuję sterownik xradio_wlan..."
rmmod xradio_wlan
sleep 1
modprobe xradio_wlan
# Opcjonalnie: wyłączamy power management w module
# modprobe xradio_wlan mac_addr_random=0

sleep 2
echo "$(date): Startuję NetworkManager..."
systemctl start NetworkManager
sleep 10

# Czekamy aż NM wstanie
nmcli radio wifi on
sleep 3

# --- KROK 4: KONFIGURACJA AP ---
echo "$(date): Tworzę profil Rescue_AP..."

# Czyścimy stare
nmcli connection delete "Rescue_AP" >/dev/null 2>&1

# TWORZENIE (Z ważną poprawką: wifi.cloned-mac-address preserve)
# To zapobiega zmianie adresu MAC, co często wiesza Orange Pi Zero
nmcli con add type wifi ifname wlan0 mode ap con-name "Rescue_AP" ssid "SQLink_WiFi_AP" autoconnect yes

# Parametry stabilności
nmcli con modify "Rescue_AP" 802-11-wireless.band bg
nmcli con modify "Rescue_AP" 802-11-wireless.channel 6
# KLUCZOWE: Nie zmieniaj MAC adresu!
nmcli con modify "Rescue_AP" wifi.cloned-mac-address preserve

# Szyfrowanie WPA2
nmcli con modify "Rescue_AP" wifi-sec.key-mgmt wpa-psk
nmcli con modify "Rescue_AP" wifi-sec.psk "sqlink123"

# IP i DHCP
nmcli con modify "Rescue_AP" ipv4.addresses 192.168.4.1/24
nmcli con modify "Rescue_AP" ipv4.method shared

# Wyłączamy oszczędzanie energii (ponownie, bo sterownik był resetowany)
iw dev wlan0 set power_save off

sleep 2

# --- KROK 5: START ---
echo "$(date): Podnoszę sieć..."
nmcli connection up "Rescue_AP"

RESULT=$?
if [ $RESULT -eq 0 ]; then
    echo "$(date): [SUKCES] Hotspot uruchomiony."
    echo "$(date): IP: 192.168.4.1"
else
    echo "$(date): [BŁĄD] Nie udało się uruchomić AP (Kod: $RESULT)."
    # Ostatnia szansa: wymuszenie IP, żeby chociaż ping działał
    ifconfig wlan0 192.168.4.1 netmask 255.255.255.0 up
fi

exit 0