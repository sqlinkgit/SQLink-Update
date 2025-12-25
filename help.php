<h3>🎓 Centrum Dowodzenia i Pomocy (SQLink Orange Pi Edition)</h3>
<div style="text-align: center; margin-bottom: 20px; font-size: 0.9em; color: #888; background: #222; padding: 5px; border-radius: 4px; border: 1px solid #444;">
    ℹ️ System zaprojektowany dla: <strong style="color: #FF9800;">Orange Pi Zero</strong> + Karta <strong style="color: #2196F3;">CM108 USB</strong>
</div>

<div class="help-section">
    <div class="help-title"><span class="help-icon">🖥️</span> 1. Twój Kokpit (Dashboard)</div>
    <div class="help-text">
        To tutaj sprawdzasz puls swojego urządzenia. Wszystko powinno świecić na zielono!
        <ul>
            <li><strong>🚦 Pasek Statusu:</strong> To ten kolorowy pasek na samej górze. Jeśli jest <span style="color:#4CAF50; font-weight:bold;">ZIELONY</span>, system działa. Jeśli <span style="color:#F44336; font-weight:bold;">CZERWONY</span>, coś się popsuło (zrób restart w zakładce Zasilanie).</li>
            
            <li><strong>🌡️ Temperatura:</strong> Orange Pi Zero lubi być ciepłe, ale bez przesady.
                <br><small>✅ 35°C - 60°C: Jest OK.<br>🔥 > 75°C: Za gorąco! Zapewnij mu trochę powietrza.</small>
            </li>
            
            <li><strong>📺 Wielki Monitor (Live):</strong> Tu widzisz, co się dzieje w eterze:
                <ul>
                    <li>⚪ <strong>Cisza (Standby):</strong> Nikt nie gada, nuda.</li>
                    <li>🟢 <span style="color:#4CAF50; font-weight:bold;">ODBIERANIE (RX):</span> Ty mówisz do radia (Hotspot Cię słyszy).</li>
                    <li>🟠 <span style="color:#FF9800; font-weight:bold;">NADAWANIE (TX):</span> Ktoś mówi z internetu (Słyszysz to w radiu).</li>
                </ul>
            </li>
        </ul>
    </div>
</div>

<div class="help-section" style="border-left: 5px solid #FF9800;">
    <div class="help-title"><span class="help-icon">🆘</span> 2. Tryb Ratunkowy WiFi (Ważne!)</div>
    <div class="help-text">
        Twoje Orange Pi Zero nie ma gniazda Ethernet, więc co zrobić, gdy zmienisz router lub pójdziesz z nim w teren?
        <br><br>
        <div style="border: 3px solid #FF9800; padding: 15px; border-radius: 8px;">
            <strong>🚨 Jak odzyskać łączność bez monitora?</strong><br><br>
            1. Włącz Hotspota tam, gdzie nie ma Twojej domowej sieci WiFi.<br>
            2. Poczekaj cierpliwie około <strong>2 minuty</strong> (system musi "zrozumieć", że nie ma internetu).<br>
            3. Hotspot automatycznie stworzy własną sieć WiFi!<br><br>
            📱 <strong>Szukaj sieci (SSID):</strong> <span style="color:#FF9800; font-size:1.1em; font-weight:bold;">SQLink_WiFi_AP</span><br>
            🔐 <strong>Hasło:</strong> <code>sqlink123</code><br>
            🌐 <strong>Adres strony:</strong> <a href="http://192.168.4.1" target="_blank" style="color:#FF9800; font-weight:bold;">192.168.4.1</a><br><br>
            Połącz się telefonem, wejdź na ten adres, skonfiguruj nowe WiFi w zakładce "WiFi" i zrób Restart. Gotowe!
        </div>
    </div>
</div>

<div class="help-section">
    <div class="help-title"><span class="help-icon">🔄</span> 3. Dwa Światy: Reflektor i EchoLink</div>
    <div class="help-text">
        Pamiętaj: Możesz być tylko w jednym miejscu naraz!
        <div class="help-step">
            <strong>🅰️ Świat A: Reflektor (SQLink)</strong><br>
            To jest Twój "dom". Jesteś tu zawsze po uruchomieniu.<br>
            Rozmawiasz z polskimi stacjami na grupach (np. Ogólnopolska).
        </div>
        <div class="help-step" style="border-left-color: #2196F3;">
            <strong>🅱️ Świat B: EchoLink (Światowy)</strong><br>
            Chcesz pogadać z kimś z USA, Japonii czy innego miasta?<br>
            1. Wejdź w zakładkę EchoLink.<br>
            2. Wybierz numer węzła i kliknij <strong>📞 Połącz</strong>.<br>
            <hr style="border: 0; border-top: 1px dashed #ccc; margin: 10px 0;">
            🛑 <strong>BARDZO WAŻNE:</strong> Kiedy skończysz rozmawiać, <strong>MUSISZ SIĘ ROZŁĄCZYĆ!</strong><br>
            👉 Kliknij przycisk <span style="color:#F44336; font-weight:bold;">📵 Rozłącz (#)</span> lub wpisz <strong>#</strong> na klawiaturze radia.<br>
            Dopiero gdy usłyszysz "Deactivating module EchoLink", wracasz do polskiej sieci.
        </div>
    </div>
</div>

<div class="help-section">
    <div class="help-title"><span class="help-icon">📱</span> 4. Zakładka DTMF (Pilot)</div>
    <div class="help-text">
        Tutaj sterujesz hotspotem bez dotykania mikrofonu radia.
        <ul>
            <li><strong>👥 Grupy Rozmowne:</strong> Kliknięcie kafelka (np. TG 260) natychmiast przełącza Cię na tę grupę.</li>
            <li><strong>🦜 Papuga (Test Audio):</strong> Narzędzie do sprawdzania, jak Cię słychać.</li>
            <li><strong>⌨️ Klawiatura:</strong> Pozwala wpisać dowolny kod DTMF (np. ukryte funkcje SVXLink).</li>
        </ul>
    </div>
</div>

<div class="help-section">
    <div class="help-title"><span class="help-icon">🎚️</span> 5. Audio i WiFi</div>
    <div class="help-text">
        <div class="help-warn">
            ⚠️ <strong>Ostrożnie z suwakami Audio!</strong> Zła konfiguracja może sprawić, że przestaniesz być słyszany.
        </div>
        <ul>
            <li><strong>🎙️ Suwak MIC Boost / ADC Gain:</strong> Reguluje głośność Twojego głosu w sieci.</li>
            <li><strong>🔊 Suwak TX Volume:</strong> Reguluje jak głośno słyszysz rozmówców w swoim radiu.</li>
            <li><strong>📶 WiFi:</strong> Możesz tu dodać nową sieć (np. z telefonu) lub usunąć stare, nieużywane sieci.</li>
        </ul>
    </div>
</div>

<div class="help-section">
    <div class="help-title"><span class="help-icon">⚡</span> 6. Zasilanie i Aktualizacje</div>
    <div class="help-text">
        W zakładce <strong>Zasilanie</strong> masz centrum sterowania życiem systemu.
        <ul>
            <li><strong>🔄 Reboot / Wyłącz:</strong> Bezpieczne zamykanie systemu. Nie wyrywaj wtyczki z prądu, bo karta pamięci tego nie lubi!</li>
            <li><strong>☁️ Aktualizuj System:</strong> Kliknij zielony przycisk, żeby pobrać nowości. Hotspot sam połączy się z GitHubem i ściągnie poprawki.</li>
            <li><strong>♻️ Restart Usługi SvxLink:</strong> "Lekarstwo na wszystko". Jeśli dashboard się zawiesi albo dźwięk zniknie - kliknij to. Trwa to tylko 5-10 sekund.</li>
        </ul>
    </div>
</div>

<div class="help-section">
    <div class="help-title" style="color: #BA68C8;"><span class="help-icon">💡</span> 7. Wskazówki i Nowe Funkcje (Warto wiedzieć)</div>
    <div class="help-text">
        Oto kilka przydatnych funkcji, które ułatwią Ci życie z Hotspotem:
        <ul>
            <li style="margin-bottom: 8px;"><strong>🌍 Twoja Wizytówka w Sieci:</strong>
                <br>W zakładce <strong>Config</strong> uzupełnij nową sekcję <em>"Lokalizacja i Operator"</em>. Dzięki temu Twoje Imię i Miasto będą widoczne dla innych kolegów w sieci (w dymkach informacyjnych i na mapie).
            </li>
            <li style="margin-bottom: 8px;"><strong>🖱️ Szybki Podgląd QRZ:</strong>
                <br>W zakładce <strong>Nodes</strong> (Węzły) kafelki stacji są interaktywne. <strong>Kliknij w znak stacji</strong>, aby natychmiast otworzyć jej profil na QRZ.com w nowym oknie.
            </li>
            <li><strong>🎛️ Wygodne Moduły:</strong>
                <br>W Konfiguracji nie musisz już wpisywać nazw modułów ręcznie. Użyj przycisków, aby włączać/wyłączać funkcje (Help, Parrot, EchoLink). 
                <br><span style="color:#4CAF50; font-weight:bold;">Zielony</span> = Włączony, <span style="color:#666; font-weight:bold;">Szary</span> = Wyłączony.
            </li>
        </ul>
    </div>
</div>

<div class="help-section" style="border:none;">
    <div class="help-title"><span class="help-icon">🔧</span> Szybka Pomoc (Q&A)</div>
    <div class="help-text">
        <strong>❓ Nie mogę połączyć się z EchoLinkiem (Status: Disconnected).</strong><br>
        ✅ Jeśli używasz internetu mobilnego (GSM), operatorzy często blokują porty. Wejdź w zakładkę <strong>Config</strong> i kliknij zielony przycisk <strong>♻️ Znajdź i ustaw Auto-Proxy</strong>.<br><br>
        
        <strong>❓ Hotspot przestał gadać / Dashboard "wisi".</strong><br>
        ✅ Wejdź w zakładkę <strong>⚡ Zasilanie</strong> i kliknij niebieski przycisk <strong>Restart Usługi SvxLink</strong>.<br><br>

        <strong>❓ Słyszę komunikaty, ale nikt mnie nie słyszy.</strong><br>
        ✅ Sprawdź częstotliwość radia i ton CTCSS w zakładce <strong>📻 Radio</strong>.<br><br>

        <strong>❓ W logach widzę "Distortion detected".</strong><br>
        ✅ Twoje radio nadaje zbyt głośno do hotspota (przester). Zcisz radio (jeśli podłączone kablem) lub zmniejsz <em>ADC Gain</em> w zakładce Audio.
    </div>
</div>