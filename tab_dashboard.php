<div style="display:flex; justify-content:center; gap:40px; margin-top:10px; margin-bottom:20px; text-align:center; border-bottom: 1px solid #333; padding-bottom: 20px;">
    <div><div style="font-size:30px;">📡</div><p style="margin:5px 0; font-size:12px;">Freq: <b><?php echo $radio['rx']; ?></b></p></div>
    <div><div style="font-size:30px;">🌍</div><p style="margin:5px 0; font-size:12px;">Host: <b><?php echo $vals['Host']; ?></b></p></div>
    <div><div style="font-size:30px;">🆔</div><p style="margin:5px 0; font-size:12px;">Znak: <b><?php echo $vals['Callsign']; ?></b></p></div>
</div>
<div id="live-monitor" class="live-box"><div class="live-status">STAN: CZUWANIE (Standby)</div><div class="live-callsign">---</div><div class="live-tg"></div></div>
<h3 style="color: #4CAF50;">Ostatnio Słyszani (Last Heard)</h3>
<table class="lh-table"><thead><tr><th>Godzina</th><th>TG</th><th>Znak</th></tr></thead><tbody id="lh-content"><tr><td colspan="3">Ładowanie...</td></tr></tbody></table>
