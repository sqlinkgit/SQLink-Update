#!/bin/bash

# Ustawienia
GIT_DIR="/root/SQLink-Update"
WWW_DIR="/var/www/html"

echo "--- START AKTUALIZACJI ---"
date

# 1. Pobierz zmiany z GitHub
if [ -d "$GIT_DIR" ]; then
    cd $GIT_DIR
    # Resetuje lokalne zmiany i wymusza wersję z chmury
    git reset --hard
    git pull origin main
else
    echo "BŁĄD: Nie widzę folderu z Gitem ($GIT_DIR)."
    exit 1
fi

# 2. SAMO-AKTUALIZACJA SKRYPTU (To jest ta nowość!)
# Sprawdzamy, czy plik w gicie różni się od tego w systemie
if ! cmp -s "$GIT_DIR/update_dashboard.sh" "/usr/local/bin/update_dashboard.sh"; then
    echo "Znaleziono nową wersję skryptu aktualizacji. Instaluję..."
    sudo cp "$GIT_DIR/update_dashboard.sh" /usr/local/bin/
    sudo chmod +x /usr/local/bin/update_dashboard.sh
    echo "Skrypt zaktualizowany. Nowe funkcje zadziałają przy następnym kliknięciu."
fi

# 3. Kopiuj pliki na stronę WWW
echo "Kopiuję pliki dashboardu na stronę..."
sudo cp $GIT_DIR/*.php $WWW_DIR/
sudo cp $GIT_DIR/*.css $WWW_DIR/
sudo cp $GIT_DIR/*.js $WWW_DIR/
sudo cp $GIT_DIR/*.png $WWW_DIR/

# 4. Obsługa Dźwięków (Przygotowane na przyszłość)
if [ -d "$GIT_DIR/sounds" ]; then
    echo "Wykryto folder dźwięków - aktualizuję komunikaty..."
    # Odkomentuj poniższą linię, gdy wrzucisz dźwięki do Gita:
    # sudo cp -r $GIT_DIR/sounds/* /usr/share/svxlink/sounds/pl_PL/
fi

# 5. Napraw uprawnienia
sudo chown -R www-data:www-data $WWW_DIR
sudo chmod -R 755 $WWW_DIR

echo "--- KONIEC AKTUALIZACJI ---"
