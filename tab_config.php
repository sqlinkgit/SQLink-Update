<h3>Konfiguracja SvxLink</h3>
<form method="post">
    <input type="hidden" name="active_tab" class="active-tab-input" value="SvxConfig">
    
    <div class="form-grid-layout">
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
            <div class="form-group"><label>Hasło EchoLink</label><input type="password" name="EL_Password" id="el-pass" value="<?php echo $vals_el['Password']; ?>"></div>
            <div class="form-group"><label>Nazwa Sysop (Widoczna)</label><input type="text" name="EL_Sysop" value="<?php echo $vals_el['Sysop']; ?>"></div>
            <div class="form-group"><label>Opis Stacji</label><input type="text" name="EL_Desc" value="<?php echo $vals_el['Desc']; ?>"></div>
            <div class="form-group"><label>Proxy (IP)</label><input type="text" name="EL_ProxyHost" value="<?php echo $vals_el['Proxy']; ?>" placeholder="np. 44.31.61.106"><small style="color:#888; font-size:10px;">Zostaw puste aby wyłączyć proxy.</small></div>
            <div style="margin-top:5px; display:flex; gap:10px; align-items:center;">
                <button type="submit" name="auto_proxy" class="btn btn-green" style="margin:0; padding:8px; font-size:12px;" onclick="return confirm('Skrypt pobierze listę publicznych proxy, znajdzie serwer Ready i zrestartuje SvxLink. Kontynuować?')">♻️ Auto-Proxy</button>
            </div>
        </div>

        <div class="panel-box box-full">
            <h4 class="panel-title green">Lokalizacja i Operator</h4>
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px;">
                <div class="form-group" style="margin:0;"><label>Imię Operatora</label><input type="text" name="qth_name" value="<?php echo isset($radio['qth_name']) ? $radio['qth_name'] : ''; ?>"></div>
                <div class="form-group" style="margin:0;"><label>Miasto (QTH)</label><input type="text" name="qth_city" value="<?php echo isset($radio['qth_city']) ? $radio['qth_city'] : ''; ?>"></div>
                <div class="form-group" style="margin:0;"><label>QTH Locator</label><input type="text" name="qth_loc" value="<?php echo isset($radio['qth_loc']) ? $radio['qth_loc'] : ''; ?>" placeholder="np. JO91SV"></div>
            </div>
            <small style="color:#888; font-size:10px; display:block; margin-top:5px;">Dane te zostaną wysłane do sieci i będą widoczne na mapie.</small>
        </div>

        <div class="panel-box box-full">
            <h4 class="panel-title green">Zaawansowane / Audio</h4>
            
            <div class="form-group" style="margin-bottom: 20px;">
                <label style="text-align:center; margin-bottom:10px;">Aktywne Moduły</label>
                <input type="hidden" name="Modules" id="input-modules" value="<?php echo $vals['Modules']; ?>">
                
                <div class="mod-grid">
                    <div class="mod-btn" id="btn-ModuleHelp" onclick="toggleModule('ModuleHelp')" style="max-width:120px;">Pomoc (Help)</div>
                    <div class="mod-btn" id="btn-ModuleParrot" onclick="toggleModule('ModuleParrot')" style="max-width:120px;">Papuga (Parrot)</div>
                    <div class="mod-btn" id="btn-ModuleEchoLink" onclick="toggleModule('ModuleEchoLink')" style="max-width:120px;">EchoLink</div>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 15px;">
                <div class="form-group"><label>TG Timeout (s)</label><input type="text" name="TgTimeout" value="<?php echo $vals['TgTimeout']; ?>"></div>
                <div class="form-group"><label>Tmp Timeout (s)</label><input type="text" name="TmpTimeout" value="<?php echo $vals['TmpTimeout']; ?>"></div>
                <div class="form-group"><label>Beep 3-ton</label><select name="Beep3Tone"><option value="1" <?php if($vals['Beep3Tone']=='1') echo 'selected'; ?>>TAK</option><option value="0" <?php if($vals['Beep3Tone']=='0') echo 'selected'; ?>>NIE</option></select></div>
                <div class="form-group"><label>Zapowiedź TG</label><select name="AnnounceTG"><option value="1" <?php if($vals['AnnounceTG']=='1') echo 'selected'; ?>>TAK</option><option value="0" <?php if($vals['AnnounceTG']=='0') echo 'selected'; ?>>NIE</option></select></div>
                <div class="form-group"><label>Info Link</label><select name="RefStatusInfo"><option value="1" <?php if($vals['RefStatusInfo']=='1') echo 'selected'; ?>>TAK</option><option value="0" <?php if($vals['RefStatusInfo']=='0') echo 'selected'; ?>>NIE</option></select></div>
                <div class="form-group"><label>Roger Beep</label><select name="RogerBeep"><option value="1" <?php if($vals['RogerBeep']=='1') echo 'selected'; ?>>TAK</option><option value="0" <?php if($vals['RogerBeep']=='0') echo 'selected'; ?>>NIE</option></select></div>
            </div>
        </div>
    </div>
    <button type="submit" name="save_svx_full" class="btn btn-blue" style="margin-top:20px;">Zapisz Wszystko i Restartuj</button>
</form>