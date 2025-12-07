<div class="dtmf-columns">
    <div class="panel-box">
        <h4 class="panel-title">Reflector / Grupy</h4>
        <div class="macro-grid">
            <button onclick="sendInstant('*91260#')" class="macro-btn">Ogólnopolska<span class="dtmf-sub">TG 260</span></button>

            <button onclick="sendInstant('*9126077#')" class="macro-btn">TG 26077<span class="dtmf-sub">Sierra Echo</span></button>
            <button onclick="sendInstant('*91235#')" class="macro-btn">TG 235<span class="dtmf-sub">Bridge UK</span></button>
            <button onclick="sendInstant('*91245#')" class="macro-btn">TG 245<span class="dtmf-sub">EchoLink</span></button>
            <button onclick="sendInstant('*912600#')" class="macro-btn">TG 2600<span class="dtmf-sub">Zagraniczna</span></button>

            <button onclick="sendInstant('*#')" class="macro-btn">Status (*#)</button>
            <button onclick="sendInstant('910#')" class="macro-btn red">Rozłącz</button>
        </div>
    </div>

    <div class="panel-box">
        <h4 class="panel-title blue">EchoLink (Moduł 2)</h4>
        <div style="margin-bottom:15px; border-bottom:1px solid #444; padding-bottom:15px;">
            <button onclick="sendInstant('2#')" class="macro-btn green" style="margin-bottom:10px; height: auto;">1. Aktywuj Moduł (2#)</button>
            <div class="node-input-group">
                <input type="text" id="el-node-id" class="node-input" placeholder="Nr Noda (np. 459342)">
                <button onclick="connectEchoLink()" class="macro-btn blue" style="width: auto; min-height: 40px;">Połącz</button>
            </div>
            <div class="macro-grid">
                <button onclick="sendInstant('9999#')" class="macro-btn">Test Echo (9999)</button>
                <button onclick="sendInstant('#')" class="macro-btn red">Rozłącz (#)</button>
            </div>
        </div>
        <div id="el-live-status">Sprawdzanie statusu...</div>
    </div>

    <div class="panel-box" style="grid-column: 1 / -1; border-color: #FF9800;">
        <h4 class="panel-title" style="color: #FF9800; border-color: #FF9800;">🦜 Papuga (Test Audio)</h4>
        <div style="display: flex; gap: 20px; align-items: center; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 200px;">
                <div class="macro-grid">
                    <button onclick="sendInstant('1#')" class="macro-btn orange">▶️ Włącz Papugę (1#)</button>
                    <button onclick="sendInstant('#')" class="macro-btn red">⏹️ Wyłącz / Stop (#)</button>
                </div>
            </div>
            <div style="flex: 1; font-size: 13px; color: #ccc; background: #222; padding: 10px; border-radius: 5px; border-left: 3px solid #FF9800;">
                <strong>Jak używać?</strong>
                <ol style="margin: 5px 0; padding-left: 20px; line-height: 1.6;">
                    <li>Kliknij <span style="color:#FF9800; font-weight:bold;">Włącz Papugę</span>. Usłyszysz komunikat "Moduł Papuga".</li>
                    <li>Wciśnij PTT w radiu, powiedz kilka słów ("Test, raz, dwa...") i puść PTT.</li>
                    <li>Hotspot powinien odesłać Twój głos. Jeśli słyszysz siebie czysto – audio jest OK.</li>
                    <li>Kliknij <span style="color:#F44336; font-weight:bold;">Wyłącz</span>, aby zakończyć test.</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<h3 style="border-top:1px solid #444; paddingTop:20px;">Klawiatura Numeryczna</h3>
<div class="dtmf-display" id="dtmf-screen">...</div>
<div class="dtmf-grid"><button onclick="typeKey('1')" class="dtmf-btn">1</button><button onclick="typeKey('2')" class="dtmf-btn">2</button><button onclick="typeKey('3')" class="dtmf-btn">3</button><button onclick="typeKey('4')" class="dtmf-btn">4</button><button onclick="typeKey('5')" class="dtmf-btn">5</button><button onclick="typeKey('6')" class="dtmf-btn">6</button><button onclick="typeKey('7')" class="dtmf-btn">7</button><button onclick="typeKey('8')" class="dtmf-btn">8</button><button onclick="typeKey('9')" class="dtmf-btn">9</button><button onclick="typeKey('*')" class="dtmf-btn">*</button><button onclick="typeKey('0')" class="dtmf-btn">0</button><button onclick="typeKey('#')" class="dtmf-btn">#</button><button onclick="clearKey()" class="dtmf-btn dtmf-clear">C</button><button onclick="submitTG()" class="dtmf-btn dtmf-tg">TG</button><button onclick="submitKey()" class="dtmf-btn dtmf-send">TX</button></div>
<p style="font-size:12px; color:#888; text-align:center;"><b>C</b>=Kasuj, <b>TG</b>=Ustaw Grupę (*91..#), <b>TX</b>=Wyślij Kod</p>
