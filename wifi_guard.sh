#!/bin/bash

# Ustawiamy plik logu
LOG_FILE="/var/log/wifi_guard.log"

# Przekierowanie wszystkiego do pliku logu
exec > >(tee -a $LOG_FILE) 2>&1

echo "----------------------------------------"
echo "$(date): [START] Strażnik WiFi uruchomiony (v2)."
echo "$(date): Czekam 75 sekund na ustabilizowanie sieci..."

sleep 35

echo "$(date): Sprawdzam stan połączenia..."

# Pobieramy dokładny status
STATUS=$(nmcli -t -f GENERAL.STATE device show wlan0)
echo "$(date): Aktualny status NetworkManager: $STATUS"

# POPRAWKA: Szukamy ":100", co oznacza pełne połączenie (connected global)
# Wcześniej szukaliśmy "connected", co pasowało też do "disconnected".
if echo "$STATUS" | grep -q ":100"; then
    echo "$(date): [SUKCES] Kod 100 wykryty. Jesteśmy online. Kończę pracę."
    exit 0
else
    echo "$(date): [ALARM] Brak kodu 100 (Brak pełnego połączenia)!"
    echo "$(date): Próbuję uruchomić tryb ratunkowy (Rescue_AP)..."
    
    # Próba siłowego rozłączenia
    echo "$(date): Rozłączam wlan0..."
    nmcli device disconnect wlan0
    sleep 5
    
    # Uruchomienie AP
    echo "$(date): Podnoszę profil Rescue_AP..."
    nmcli connection up Rescue_AP
    
    RESULT=$?
    if [ $RESULT -eq 0 ]; then
        echo "$(date): [SUKCES] Hotspot uruchomiony. IP: 192.168.4.1"
    else
        echo "$(date): [BŁĄD] Nie udało się uruchomić AP! Kod błędu: $RESULT"
        # Próba restartu NetworkManagera w akcie desperacji
        # sudo systemctl restart NetworkManager
    fi
fi

echo "$(date): [KONIEC] Skrypt zakończył działanie."
