#!/bin/bash

# Ustawienia
GIT_URL="https://github.com/SQLinkgit/SQLink-Update.git"
GIT_DIR="/root/SQLink-Update"
WWW_DIR="/var/www/html"

echo "--- START AKTUALIZACJI (Orange Pi) ---"
date

sleep 5

# 1. Sprawdź czy folder istnieje
if [ ! -d "$GIT_DIR" ]; then
    echo "⚠️ Folder repozytorium nie istnieje. Pobieram go od nowa..."
    cd /root
    git clone $GIT_URL
    if [ $? -ne 0 ]; then
        echo "❌ BŁĄD KRYTYCZNY: Nie udało się sklonować repozytorium."
        exit 1
    fi
fi

# 2. Pobierz zmiany
echo "Pobieram najnowszą wersję..."
cd $GIT_DIR
git reset --hard
git pull origin main

# 3. Instaluj Python tools
if compgen -G "$GIT_DIR/*.py" > /dev/null; then
    sudo cp $GIT_DIR/*.py /usr/local/bin/
    sudo chmod +x /usr/local/bin/*.py
fi

# 4. Instaluj nowe skrypty (Logger, Cleaner)
for script in $GIT_DIR/*.sh; do
    filename=$(basename "$script")
    # Omijamy plik update_dashboard.sh w tej petli
    if [ "$filename" != "update_dashboard.sh" ]; then
        sudo cp "$script" /usr/local/bin/
        sudo chmod +x "/usr/local/bin/$filename"
    fi
done

# 5. Kopiuj pliki WWW
echo "Kopiuję pliki dashboardu..."
sudo cp $GIT_DIR/*.php $WWW_DIR/
sudo cp $GIT_DIR/*.css $WWW_DIR/
sudo cp $GIT_DIR/*.js $WWW_DIR/
sudo cp $GIT_DIR/*.png $WWW_DIR/

# 6. Dźwięki
if [ -d "$GIT_DIR/sounds" ]; then
    sudo mkdir -p /usr/local/share/svxlink/sounds/pl_PL/
    sudo cp -r $GIT_DIR/sounds/* /usr/local/share/svxlink/sounds/pl_PL/
    sudo chown -R svxlink:daemon /usr/local/share/svxlink/sounds/pl_PL/
    sudo chmod -R 755 /usr/local/share/svxlink/sounds/pl_PL/
fi

# 7. Uprawnienia WWW
sudo chown -R www-data:www-data $WWW_DIR
sudo chmod -R 755 $WWW_DIR

# --- 8. AUTOSTART (Logger + Cleaner) ---
RC_LOCAL="/etc/rc.local"
CLEANER_SCRIPT="/usr/local/bin/clean_logs_on_boot.sh"

if [ -f "$CLEANER_SCRIPT" ]; then
    if ! grep -q "clean_logs_on_boot.sh" "$RC_LOCAL"; then
        echo "🔧 Dodaję logger do rc.local..."
        if grep -q "exit 0" "$RC_LOCAL"; then
             sudo sed -i -e '/exit 0/i \/usr/local/bin/clean_logs_on_boot.sh &' "$RC_LOCAL"
        else
             sudo sed -i -e '$i \/usr/local/bin/clean_logs_on_boot.sh &\n' "$RC_LOCAL"
        fi
    fi
fi

# --- 9. FIX WIFI POWER SAVE (Network Manager) ---
NM_CONF="/etc/NetworkManager/conf.d/default-wifi-powersave-on.conf"
if [ ! -f "$NM_CONF" ]; then
    echo "🔧 Konfiguracja NetworkManager (Power Save OFF)..."
    sudo mkdir -p /etc/NetworkManager/conf.d
    echo -e "[connection]\nwifi.powersave = 2" | sudo tee "$NM_CONF" > /dev/null
    sudo systemctl restart NetworkManager
fi

# 10. SAMO-AKTUALIZACJA
if ! cmp -s "$GIT_DIR/update_dashboard.sh" "/usr/local/bin/update_dashboard.sh"; then
    echo "Aktualizuję instalator..."
    sudo cp "$GIT_DIR/update_dashboard.sh" /usr/local/bin/
    sudo chmod +x /usr/local/bin/update_dashboard.sh
fi

echo "--- KONIEC AKTUALIZACJI ---"