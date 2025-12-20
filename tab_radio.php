<?php
$CTCSS_MAP = [
    "0000" => "Brak (CSQ)", "0670" => "67.0 Hz", "0719" => "71.9 Hz", "0744" => "74.4 Hz", "0770" => "77.0 Hz",
    "0797" => "79.7 Hz", "0825" => "82.5 Hz", "0854" => "85.4 Hz", "0885" => "88.5 Hz", "0915" => "91.5 Hz",
    "0948" => "94.8 Hz", "0974" => "97.4 Hz", "1000" => "100.0 Hz", "1035" => "103.5 Hz", "1072" => "107.2 Hz",
    "1109" => "110.9 Hz", "1148" => "114.8 Hz", "1188" => "118.8 Hz", "1230" => "123.0 Hz", "1273" => "127.3 Hz",
    "1318" => "131.8 Hz", "1365" => "136.5 Hz", "1413" => "141.3 Hz", "1462" => "146.2 Hz", "1514" => "151.4 Hz",
    "1567" => "156.7 Hz", "1622" => "162.2 Hz", "1679" => "167.9 Hz", "1738" => "173.8 Hz", "1799" => "179.9 Hz",
    "1862" => "186.2 Hz", "1928" => "192.8 Hz", "2035" => "203.5 Hz", "2107" => "210.7 Hz", "2181" => "218.1 Hz",
    "2257" => "225.7 Hz", "2336" => "233.6 Hz", "2418" => "241.8 Hz", "2503" => "250.3 Hz"
];
?>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">

    <div class="panel-box" style="border-top: 3px solid #2196F3;">
        <h4 class="panel-title blue">⚙️ Programowanie Modułu SA818</h4>
        <div style="font-size: 12px; color: #aaa; margin-bottom: 15px; font-style: italic;">
            Zmiana tych ustawień przeprogramuje fizycznie moduł radiowy. <br>SvxLink zostanie na chwilę zatrzymany.
        </div>
        
        <form method="post">
            <input type="hidden" name="active_tab" class="active-tab-input" value="Radio">
            
            <div class="form-group">
                <label>Opis Sprzętu (Tylko wizualne)</label>
                <input type="text" name="radio_desc" value="<?php echo isset($radio['desc']) ? htmlspecialchars($radio['desc']) : ''; ?>" placeholder="np. OrangePi + SA818">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                <div class="form-group">
                    <label>RX Freq (MHz)</label>
                    <input type="text" name="rx" value="<?php echo htmlspecialchars($radio['rx']); ?>">
                </div>
                <div class="form-group">
                    <label>TX Freq (MHz)</label>
                    <input type="text" name="tx" value="<?php echo htmlspecialchars($radio['tx']); ?>">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                <div class="form-group">
                    <label>Ton CTCSS</label>
                    <select name="ctcss">
                        <?php
                            foreach($CTCSS_MAP as $code => $label) {
                                $sel = ($radio['ctcss'] == $code) ? 'selected' : '';
                                echo "<option value='$code' $sel>$label</option>";
                            }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Squelch (1-8)</label>
                    <select name="sq">
                        <?php foreach([1,2,3,4,5,6,7,8] as $s) {
                            $sel = ($radio['sq'] == $s) ? 'selected' : '';
                            echo "<option value='$s' $sel>$s</option>";
                        } ?>
                    </select>
                </div>
            </div>

            <button type="submit" name="save_radio" class="btn btn-green">💾 Zaprogramuj Radio</button>
        </form>
    </div>

    <div>
        <div class="panel-box" style="border-left: 5px solid #FF9800; background: #26201b;">
            <h4 class="panel-title" style="color: #FF9800; border: none;">⚠️ Ważne Informacje SA818</h4>
            <div style="font-size: 13px; color: #ddd; line-height: 1.6;">
                <ul style="list-style: none; padding: 0; margin-top: 10px;">
                    <li style="margin-bottom: 8px;">
                        📶 <b>Simplex:</b><br>
                        Dla pracy simplex ustaw <b>RX</b> i <b>TX</b> na tę samą częstotliwość.
                    </li>
                    <li style="margin-bottom: 8px;">
                        🔇 <b>Squelch:</b><br>
                        Zalecana wartość to <b>2-4</b>. Zbyt niska (1) może otwierać blokadę od zakłóceń komputera.
                    </li>
                    <li>
                        ⚡ <b>Czas programowania:</b><br>
                        Po kliknięciu "Zaprogramuj", usługa SvxLink zostanie zrestartowana. Przerwa w działaniu potrwa około 3-5 sekund.
                    </li>
                </ul>
            </div>
        </div>
    </div>

</div>