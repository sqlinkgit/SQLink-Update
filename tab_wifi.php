<div class="panel-box" style="text-align:center; margin-bottom:15px;">
    <h4 class="panel-title blue">Status Połączenia</h4>
    <div id="wifi-tab-status" style="font-weight:bold; font-size:18px; color:#fff;">Ładowanie...</div>
    <div id="wifi-tab-ip" style="color:#4CAF50; margin-top:5px;">...</div>
</div>

<h3>Zapamiętane Sieci</h3>
<?php if (!empty($saved_wifi_list)): ?>
<table class="wifi-saved-table">
    <thead><tr><th>SSID</th><th>Akcja</th></tr></thead>
    <tbody>
        <?php foreach($saved_wifi_list as $sw): ?>
        <tr>
            <td><b><?php echo htmlspecialchars($sw); ?></b></td>
            <td style="text-align:right;">
                <form method="post" style="margin:0;">
                    <input type="hidden" name="active_tab" class="active-tab-input" value="WiFi">
                    <input type="hidden" name="ssid" value="<?php echo htmlspecialchars($sw); ?>">
                    <button type="submit" name="wifi_delete" class="btn-small-del" onclick="return confirm('Usunąć?')">X Usuń</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php else: ?>
<p style="color:#888;">Brak zapamiętanych sieci.</p>
<?php endif; ?>

<h3>Skanowanie</h3>
<form method="post" style="margin-bottom: 20px;">
    <input type="hidden" name="active_tab" class="active-tab-input" value="WiFi">
    <button type="submit" name="wifi_scan" class="btn btn-blue">Skanuj Sieci (Czekaj 5s...)</button>
</form>

<?php if (!empty($wifi_scan_results)): ?>
    <div style="max-height:200px; overflow-y:auto; border:1px solid #333;">
        <table class="lh-table"><tbody>
        <?php foreach($wifi_scan_results as $w): ?>
            <tr class="wifi-row" onclick="selectWifi('<?php echo htmlspecialchars($w['ssid']); ?>')">
                <td><b><?php echo htmlspecialchars($w['ssid']); ?></b></td>
                <td style="text-align:right; color:#<?php echo ($w['signal']>70?'4CAF50':($w['signal']>40?'FF9800':'F44336')); ?>;"><?php echo $w['signal']; ?>%</td>
            </tr>
        <?php endforeach; ?>
        </tbody></table>
    </div>
<?php elseif (isset($_POST['wifi_scan'])): ?>
    <div class="alert alert-error" style="text-align:left; background:#200;">
        <strong>⚠️ RAW OUTPUT:</strong>
        <pre style="font-size:10px;"><?php echo htmlspecialchars($raw_wifi_debug); ?></pre>
    </div>
<?php endif; ?>

<form method="post" style="margin-top:10px;">
    <input type="hidden" name="active_tab" class="active-tab-input" value="WiFi">
    <input type="text" id="wifi-ssid" name="ssid" placeholder="SSID ręcznie">
    <input type="password" name="pass" placeholder="Hasło" style="margin-top:5px;">
    <button type="submit" name="wifi_connect" class="btn btn-green">Połącz</button>
</form>