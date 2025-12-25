<div id="node-tooltip" class="node-tooltip">
    <div class="nt-header">
        <span id="nt-callsign"></span>
        <span id="nt-sw"></span>
    </div>
    <div class="nt-body">
        
        <div class="nt-row">
            <span class="nt-label">Operator:</span>
            <span class="nt-val hl" id="nt-name">---</span>
        </div>

        <div class="nt-row">
            <span class="nt-label">Aktualna Grupa (TG):</span>
            <span class="nt-val" id="nt-tg">---</span>
        </div>

        <div class="nt-row">
            <span class="nt-label">Lokator QTH:</span>
            <span class="nt-val" id="nt-qth">---</span>
        </div>

        <div class="nt-row">
            <span class="nt-label">Miasto / Opis:</span>
            <span class="nt-val" id="nt-loc">---</span>
        </div>
        
        <div class="nt-row">
            <span class="nt-label">Monitorowane:</span>
            <span class="nt-val" id="nt-monitored">---</span>
        </div>
        
        <div class="nt-row">
            <span class="nt-label">Wersja:</span>
            <span class="nt-val" id="nt-ver">---</span>
        </div>
        
        <div class="qrz-logo-container">
            <img src="qrz.png" alt="QRZ" class="qrz-img">
        </div>
    </div>
</div>

<h3>Aktywne Węzły (Network)</h3>
<div class="nodes-container" id="nodes-content">
    <div style="grid-column: 1/-1; text-align: center; color: #777; padding: 20px;">
        Ładowanie listy węzłów...
    </div>
</div>

<script>
    if(typeof updateNodes === "function") {
        updateNodes();
    }
</script>