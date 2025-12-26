<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

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

<div id="map-overlay">
    <button class="map-close-btn" onclick="closeGridMapper()">❌ ZAMKNIJ MAPĘ</button>
    <div id="map-container"></div>
</div>

<h3>Aktywne Węzły (Network)</h3>

<div style="text-align: center; margin-bottom: 15px;">
    <button class="map-btn-trigger" onclick="openGridMapper()">🌍 Grid Mapper Node (Pokaż Mapę)</button>
</div>

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