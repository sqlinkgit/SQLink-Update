<h3>🎓 Centrum Pomocy i Diagnostyki</h3>

<div class="help-section">
    <div class="help-title"><span class="help-icon">🖥️</span> 1. Dashboard - Co tu widzę?</div>
    <div class="help-text">
        Ekran główny (Dashboard) to Twój monitor stanu pracy hotspota.
        <ul>
            <li><strong>🚦 Pasek Statusu:</strong> (Pod nagłówkiem) Informuje, czy usługa SVXLink działa poprawnie.</li>
            <li><strong>📊 Telemetria:</strong> Zużycie procesora, pamięci i temperatura.
                <br><small>⚠️ Jeśli temperatura jest czerwona (>70°C), zapewnij malinie lepsze chłodzenie.</small>
            </li>
            <li><strong>📺 Live Monitor (Duży kafel):</strong> To serce systemu.
                <ul>
                    <li>⚪ <strong>Stan: CZUWANIE:</strong> Nikt nie rozmawia. Cisza w eterze.</li>
                    <li>🟢 <span style="color:#4CAF50; font-weight:bold;">ODBIERANIE (RX):</span> Ktoś nadaje z sieci (słyszysz to w swoim radiu).</li>
                    <li>🟠 <span style="color:#FF9800; font-weight:bold;">NADAWANIE (TX):</span> Ty nadajesz do radia (Twój głos leci w świat).</li>
                </ul>
            </li>
            <li><strong>📝 Last Heard:</strong> Historia ostatnich 20 stacji, które były aktywne w sieci.</li>
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
            <li><strong>🦜 Papuga (Test Audio):</strong> Narzędzie do sprawdzania, jak Cię słychać.
                <ol style="margin-top:5px; font-size:12px;">
                    <li>Kliknij <strong>▶️ Włącz Papugę</strong>.</li>
                    <li>Powiedz coś do radia (zrób tzw. "test modulacji").</li>
                    <li>Hotspot odegra Twój głos. Jeśli jest cicho/zniekształcony -> zajrzyj do zakładki Audio.</li>
                    <li>Kliknij <strong>⏹️ Wyłącz</strong> po zakończeniu.</li>
                </ol>
            </li>
            <li><strong>⌨️ Klawiatura:</strong> Pozwala wpisać dowolny kod DTMF (np. ukryte funkcje SVXLink).</li>
        </ul>
    </div>
</div>

<div class="help-section">
    <div class="help-title"><span class="help-icon">🎚️</span> 4. Audio, Radio i WiFi</div>
    <div class="help-text">
        <div class="help-warn">
            ⚠️ <strong>Ostrożnie z suwakami Audio!</strong> Zła konfiguracja może sprawić, że przestaniesz być słyszany.
        </div>
        <ul>
            <li><strong>🎙️ Suwak MIC Boost / ADC Gain:</strong> Reguluje głośność Twojego głosu w sieci. Jeśli koledzy mówią, że "przesterowujesz" lub "charczysz" - zmniejsz to.</li>
            <li><strong>🔊 Suwak TX Volume:</strong> Reguluje jak głośno słyszysz rozmówców w swoim radiu.</li>
            <li><strong>📶 WiFi:</strong> Możesz tu dodać nową sieć (np. z telefonu) lub usunąć stare, nieużywane sieci, aby hotspot łączył się szybciej.</li>
        </ul>
    </div>
</div>

<div class="help-section" style="border:none;">
    <div class="help-title"><span class="help-icon">🔧</span> Rozwiązywanie Problemów (Q&A)</div>
    <div class="help-text">
        <strong>Q: Nie mogę połączyć się z EchoLinkiem (Status: Disconnected).</strong><br>
        A: Jeśli używasz internetu mobilnego (GSM), operatorzy często blokują porty. Wejdź w zakładkę <strong>Config</strong> i kliknij zielony przycisk <strong>♻️ Znajdź i ustaw Auto-Proxy</strong>. System sam znajdzie obejście.<br><br>
        
        <strong>Q: Hotspot przestał gadać / Dashboard "wisi".</strong><br>
        A: Wejdź w zakładkę <strong>⚡ Zasilanie</strong> i kliknij niebieski przycisk <strong>Restart Usługi SvxLink</strong>. To "miękki restart" samego oprogramowania, trwa ok. 5-10 sekund.<br><br>

        <strong>Q: Słyszę komunikaty, ale nikt mnie nie słyszy.</strong><br>
        A: Sprawdź częstotliwość radia i ton CTCSS w zakładce <strong>📻 Radio</strong>. Upewnij się, że Twoje radio ręczne ma ustawiony taki sam ton nadawania.<br><br>

        <strong>Q: W logach widzę "Distortion detected".</strong><br>
        A: Twoje radio nadaje zbyt głośno do hotspota (przester). Zcisz radio (jeśli podłączone kablem) lub zmniejsz <em>ADC Gain</em> w zakładce Audio.
    </div>
</div>
