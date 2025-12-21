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

<div class="help-section">
    <div class="help-title"><span class="help-icon">🔄</span> 2. Tryby Pracy: Reflector vs EchoLink</div>
    <div class="help-text">
        Hotspot obsługuje dwa główne systemy, które <strong>nie mogą działać jednocześnie</strong>.
        
        <div class="help-step">
            <strong>🅰️ Tryb A: Reflector (SQLink) - Domyślny</strong><br>
            Działa jak sieć przemienników. Jesteś tu zawsze, gdy nie używasz EchoLinka.<br>
            <ul>
                <li>Aby zmienić kanał rozmowny, wybierz przycisk z listy (np. <strong>🇵🇱 Ogólnopolska</strong>).</li>
                <li>Aby sprawdzić gdzie jesteś, kliknij <strong>ℹ️ Status (*#)</strong>.</li>
            </ul>
        </div>

        <div class="help-step" style="border-left-color: #2196F3;">
            <strong>🅱️ Tryb B: EchoLink (Moduł 2)</strong><br>
            Służy do połączeń z konkretnymi znakami na świecie.<br><br>
            1. Kliknij <strong>🚀 Aktywuj Moduł (2#)</strong>.<br>
            2. Wpisz numer węzła (Node ID) i kliknij <strong>📞 Połącz</strong>.<br>
            <hr style="border: 0; border-top: 1px dashed #555; margin: 10px 0;">
            🛑 <strong>WAŻNE - KONIEC ROZMOWY:</strong><br>
            Aby wrócić do sieci SQLink, musisz wyjść z EchoLinka przyciskiem <span style="color:#F44336; font-weight:bold;">Rozłącz (#)</span>.<br>
            <span style="color:#FF9800;">👉 Jeśli nadal jesteś w EchoLinku, naciśnij <strong>Rozłącz</strong> jeszcze raz! Musisz usłyszeć komunikat "Deactivating module EchoLink".</span>
        </div>
    </div>
</div>

<div class="help-section">
    <div class="help-title"><span class="help-icon">📱</span> 3. Zakładka DTMF (Pilot)</div>
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
    <div class="help-title"><span class="help-icon">🎚️</span> 4. Audio i WiFi</div>
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
    <div class="help-title"><span class="help-icon">☁️</span> 5. Aktualizacje Systemu</div>
    <div class="help-text">
        Twój hotspot posiada system automatycznych aktualizacji (OTA).
        <div class="help-step" style="border-left-color: #4CAF50;">
            <strong>Jak zaktualizować?</strong><br>
            Wejdź w zakładkę <strong>⚡ Zasilanie</strong> i kliknij zielony przycisk <strong>☁️ Pobierz Aktualizację</strong>.<br>
            System automatycznie pobierze najnowsze funkcje.
        </div>
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