<h3>Konfiguracja SvxLink</h3>
<form method="post">
    <input type="hidden" name="active_tab" class="active-tab-input" value="SvxConfig">
    <div class="form-grid">
        <div class="panel-box">
            <h4 class="panel-title">Reflector (SQLink)</h4>
            <div class="form-group"><label>Znak Noda</label><input type="text" name="Callsign" value="<?php echo $vals['Callsign']; ?>"></div>
            <div class="form-group"><label>Hasło</label><input type="password" name="Password" value="<?php echo $vals['Password']; ?>"></div>
            <div class="form-group"><label>Host</label><input type="text" name="Host" value="<?php echo $vals['Host']; ?>"></div>
            <div class="form-group"><label>Domyślna Grupa (TG)</label><input type="text" name="DefaultTG" value="<?php echo $vals['DefaultTG']; ?>"></div>
            
            <div class="form-group">
                <label>Monitorowane Grupy (TG)</label>
                <input type="text" name="MonitorTGs" value="<?php echo $vals['MonitorTGs']; ?>" placeholder="np. 260, 26077">
                <small style="color:#888; font-size:10px;">Oddzielone przecinkami</small>
            </div>
        </div>
        
        <div class="panel-box">
            <h4 class="panel-title blue">EchoLink</h4>
            <div class="form-group"><label>Znak EchoLink</label><input type="text" name="EL_Callsign" value="<?php echo $vals_el['Callsign']; ?>"></div>
            <div class="form-group"><label>Hasło EchoLink</label><input type="password" name="EL_Password" value="<?php echo $vals_el['Password']; ?>"></div>
            <div class="form-group"><label>Nazwa Sysop (Widoczna)</label><input type="text" name="EL_Sysop" value="<?php echo $vals_el['Sysop']; ?>"></div>
            <div class="form-group"><label>Opis Stacji</label><input type="text" name="EL_Desc" value="<?php echo $vals_el['Desc']; ?>"></div>
            <div class="form-group"><label>Proxy (IP)</label><input type="text" name="EL_ProxyHost" value="<?php echo $vals_el['Proxy']; ?>" placeholder="np. 44.31.61.106"><small style="color:#888; font-size:10px;">Zostaw puste aby wyłączyć proxy.</small></div>
            <div style="margin-top:5px; display:flex; gap:10px; align-items:center;">
                <button type="submit" name="auto_proxy" class="btn btn-green" style="margin:0; padding:8px; font-size:12px;" onclick="return confirm('Skrypt pobierze listę publicznych proxy, znajdzie serwer Ready i zrestartuje SvxLink. Kontynuować?')">♻️ Znajdź i ustaw Auto-Proxy</button>
                <a href="http://www.echolink.org/proxylist.jsp" target="_blank" style="color:#2196F3; text-decoration:underline; font-size:11px;">Lista WWW</a>
            </div>
        </div>

        <div class="panel-box" style="grid-column: 1 / -1;">
            <h4 class="panel-title green">Zaawansowane / Audio</h4>
            
            <div class="form-group" style="margin-bottom: 20px;">
                <label>Aktywne Moduły (Ładowane przy starcie)</label>
                <input type="text" name="Modules" value="<?php echo $vals['Modules']; ?>" style="border-color: #4CAF50;">
                <small style="color:#888; font-size:10px;">Wpisz nazwy modułów oddzielone przecinkami (np. Help,Parrot,EchoLink)</small>
            </div>

            <div class="form-grid" style="grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));">
                <div class="form-group"><label>TG Timeout (s)</label><input type="text" name="TgTimeout" value="<?php echo $vals['TgTimeout']; ?>"></div>
                <div class="form-group"><label>Tmp Timeout (s)</label><input type="text" name="TmpTimeout" value="<?php echo $vals['TmpTimeout']; ?>"></div>
                <div class="form-group"><label>Beep 3-ton</label><select name="Beep3Tone"><option value="1" <?php if($vals['Beep3Tone']=='1') echo 'selected'; ?>>TAK</option><option value="0" <?php if($vals['Beep3Tone']=='0') echo 'selected'; ?>>NIE</option></select></div>
                <div class="form-group"><label>Zapowiedź TG</label><select name="AnnounceTG"><option value="1" <?php if($vals['AnnounceTG']=='1') echo 'selected'; ?>>TAK</option><option value="0" <?php if($vals['AnnounceTG']=='0') echo 'selected'; ?>>NIE</option></select></div>
                <div class="form-group"><label>Info Link</label><select name="RefStatusInfo"><option value="1" <?php if($vals['RefStatusInfo']=='1') echo 'selected'; ?>>TAK</option><option value="0" <?php if($vals['RefStatusInfo']=='0') echo 'selected'; ?>>NIE</option></select></div>
                <div class="form-group"><label>Roger Beep</label><select name="RogerBeep"><option value="1" <?php if($vals['RogerBeep']=='1') echo 'selected'; ?>>TAK</option><option value="0" <?php if($vals['RogerBeep']=='0') echo 'selected'; ?>>NIE</option></select></div>
            </div>
        </div>
    </div>
    <button type="submit" name="save_svx_full" class="btn btn-blue">Zapisz Wszystko i Restartuj</button>
</form>
