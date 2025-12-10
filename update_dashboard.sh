#!/bin/bash

# Ustawienia
GIT_URL="https://github.com/SQLinkgit/SQLink-Update.git"
GIT_DIR="/root/SQLink-Update"
WWW_DIR="/var/www/html"

echo "--- START AKTUALIZACJI ---"
date

sleep 20

# 1. Sprawdź czy folder istnieje (AUTO-NAPRAWA)
if [ ! -d "$GIT_DIR" ]; then
    echo "⚠️ Folder repozytorium nie istnieje. Pobieram go od nowa..."
    cd /root
    git clone $GIT_URL
    if [ $? -ne 0 ]; then
        echo "❌ BŁĄD KRYTYCZNY: Nie udało się sklonować repozytorium. Sprawdź internet."
        exit 1
    fi
fi

# 2. Pobierz zmiany z GitHub
echo "Pobieram najnowszą wersję..."
cd $GIT_DIR
git reset --hard
git pull origin main

# 3. SAMO-AKTUALIZACJA SKRYPTU
if ! cmp -s "$GIT_DIR/update_dashboard.sh" "/usr/local/bin/update_dashboard.sh"; then
    echo "Znaleziono nową wersję skryptu aktualizacji. Instaluję..."
    sudo cp "$GIT_DIR/update_dashboard.sh" /usr/local/bin/
    sudo chmod +x /usr/local/bin/update_dashboard.sh
fi

# 4. Kopiuj pliki na stronę WWW
echo "Kopiuję pliki dashboardu na stronę..."
sudo cp $GIT_DIR/*.php $WWW_DIR/
sudo cp $GIT_DIR/*.css $WWW_DIR/
sudo cp $GIT_DIR/*.js $WWW_DIR/
sudo cp $GIT_DIR/*.png $WWW_DIR/

# 5. Obsługa Dźwięków
if [ -d "$GIT_DIR/sounds" ]; then
    echo "Wykryto folder dźwięków - aktualizuję komunikaty..."
    sudo mkdir -p /usr/local/share/svxlink/sounds/pl_PL/
    sudo cp -r $GIT_DIR/sounds/* /usr/local/share/svxlink/sounds/pl_PL/
    sudo chown -R svxlink:daemon /usr/local/share/svxlink/sounds/pl_PL/
    sudo chmod -R 755 /usr/local/share/svxlink/sounds/pl_PL/
fi

# 6. Napraw uprawnienia WWW
sudo chown -R www-data:www-data $WWW_DIR
sudo chmod -R 755 $WWW_DIR

if [ ! -f /usr/local/bin/fix_svxlink_nodes.sh ]; then
    echo "Instaluję mechanizm odświeżania logów po restarcie..."
    
    # Kopiujemy nowy plik do systemu
    sudo cp "$GIT_DIR/fix_svxlink_nodes.sh" /usr/local/bin/
    sudo chmod +x /usr/local/bin/fix_svxlink_nodes.sh
    
    # Dodajemy wywołanie skryptu do autostartu (rc.local), jeśli nie ma
    if ! grep -q "fix_svxlink_nodes.sh" /etc/rc.local; then
        sudo sed -i '/^exit 0/i \/usr\/local\/bin\/fix_svxlink_nodes.sh &' /etc/rc.local
        echo "Dodano fix_svxlink_nodes.sh do autostartu."
    fi
fi

echo "--- KONIEC AKTUALIZACJI ---"