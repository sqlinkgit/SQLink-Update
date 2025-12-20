<?php
?>
<style>
    .dash-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
        margin-bottom: 20px;
    }
    .dash-tile {
        background: #262626;
        border: 1px solid #333;
        border-radius: 8px;
        padding: 15px 5px;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        transition: 0.2s;
    }
    .dash-tile:hover {
        background: #2e2e2e;
        border-color: #4CAF50;
    }
    .dash-icon {
        font-size: 28px;
        margin-bottom: 8px;
        height: 35px;
    }
    .dash-label {
        font-size: 10px;
        color: #888;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 2px;
    }
    .dash-value {
        font-size: 14px;
        font-weight: bold;
        color: #fff;
    }
    @media (max-width: 600px) {
        .dash-grid { grid-template-columns: repeat(1, 1fr); } /* Na telefonie jeden pod drugim */
    }
</style>

<div class="dash-grid">
    <div class="dash-tile">
        <div class="dash-icon">📡</div>
        <div class="dash-label">Częstotliwość</div>
        <div class="dash-value"><?php echo $radio['rx']; ?> MHz</div>
    </div>
    
    <div class="dash-tile">
        <div class="dash-icon">🌍</div>
        <div class="dash-label">Host</div>
        <div class="dash-value"><?php echo $vals['Host']; ?></div>
    </div>

    <div class="dash-tile">
        <div class="dash-icon">🆔</div>
        <div class="dash-label">Znak</div>
        <div class="dash-value"><?php echo $vals['Callsign']; ?></div>
    </div>
</div>

<div style="text-align:center; margin-bottom:25px; display:flex; justify-content:center; gap: 15px; flex-wrap: wrap;">
    <div style="background: #222; padding: 8px 15px; border-radius: 20px; border: 1px solid #444; display:flex; align-items:center; gap:8px;">
        <span style="font-size:16px;">📻</span>
        <span style="font-size:13px; color:#aaa;">Sprzęt:</span>
        <b style="color:#fff; font-size:14px;"><?php echo isset($radio['desc']) ? $radio['desc'] : 'SA818 Module'; ?></b>
    </div>

    <?php if(isset($radio['ctcss']) && $radio['ctcss'] != '0000'): ?>
    <div style="background: #222; padding: 8px 15px; border-radius: 20px; border: 1px solid #444; display:flex; align-items:center; gap:8px;">
        <span style="font-size:16px;">🔒</span>
        <span style="font-size:13px; color:#aaa;">CTCSS:</span>
        <b style="color:#FF9800; font-size:14px;"><?php echo $radio['ctcss']; ?></b>
    </div>
    <?php endif; ?>
</div>

<div id="live-monitor" class="live-box">
    <div class="live-status">STAN: CZUWANIE (Standby)</div>
    <div class="live-callsign">---</div>
    <div class="live-tg"></div>
</div>

<h3 style="color: #4CAF50; margin-top:20px;">Ostatnio Słyszani (Last Heard)</h3>
<table class="lh-table">
    <thead>
        <tr>
            <th>Godzina</th>
            <th>TG</th>
            <th>Znak</th>
        </tr>
    </thead>
    <tbody id="lh-content">
        <tr><td colspan="3">Ładowanie...</td></tr>
    </tbody>
</table>