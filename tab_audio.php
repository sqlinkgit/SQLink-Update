<?php echo isset($audio_msg) ? $audio_msg : ''; ?>
<h3>Mikser Audio</h3>
<form method="post">
    <input type="hidden" name="active_tab" class="active-tab-input" value="Audio">
    <div class="form-grid">
        <div class="audio-card highlight">
            <h4 class="audio-title green">Odbiór (RX)</h4>
            <div class="switch-row"><div class="switch-label">MIC1</div><input type="checkbox" name="Mic1_Cap_Sw" value="1" <?php if($audio['Mic1_Cap_Sw']) echo "checked"; ?>></div>
            <div class="slider-group"><div class="slider-header"><span>Mic1 Boost</span><span class="slider-val"><?php echo $audio['Mic1_Boost']; ?>/7</span></div><input type="range" name="boost1_vol" min="0" max="7" value="<?php echo $audio['Mic1_Boost']; ?>" oninput="document.getElementById('v_b1').innerText=this.value"></div>
            <div class="slider-group"><div class="slider-header"><span>ADC Gain</span><span class="slider-val"><?php echo $audio['ADC_Gain']; ?>/7</span></div><input type="range" name="adc_vol" min="0" max="7" value="<?php echo $audio['ADC_Gain']; ?>"></div>
        </div>
        <div class="audio-card">
            <h4 class="audio-title">Inne Wejścia</h4>
            <div class="switch-row"><div class="switch-label">MIC2</div><input type="checkbox" name="Mic2_Cap_Sw" value="1" <?php if($audio['Mic2_Cap_Sw']) echo "checked"; ?>></div>
            <div class="slider-group"><div class="slider-header"><span>Mic2 Boost</span><span class="slider-val"><?php echo $audio['Mic2_Boost']; ?>/7</span></div><input type="range" name="boost2_vol" min="0" max="7" value="<?php echo $audio['Mic2_Boost']; ?>"></div>
            <div class="switch-row"><div class="switch-label">Line In</div><input type="checkbox" name="LineIn_Cap_Sw" value="1" <?php if($audio['LineIn_Cap_Sw']) echo "checked"; ?>></div>
            <div class="switch-row"><div class="switch-label">Mixer</div><input type="checkbox" name="Mixer_Cap_Sw" value="1" <?php if($audio['Mixer_Cap_Sw']) echo "checked"; ?>></div>
        </div>
        <div class="audio-card highlight" style="border-color:#2196F3;">
            <h4 class="audio-title" style="color:#2196F3;">Nadawanie (TX)</h4>
            <div class="slider-group"><div class="switch-row"><div class="switch-label">Line Out</div><input type="checkbox" name="LineOut_Sw" value="1" <?php if($audio['LineOut_Sw']) echo "checked"; ?>></div><div class="slider-header"><span>Line Out Volume</span><span class="slider-val"><?php echo $audio['LineOut_Vol']; ?>/31</span></div><input type="range" name="out_vol" min="0" max="31" value="<?php echo $audio['LineOut_Vol']; ?>"></div>
            <div class="slider-group"><div class="switch-row"><div class="switch-label">DAC</div><input type="checkbox" name="DAC_Sw" value="1" <?php if($audio['DAC_Sw']) echo "checked"; ?>></div><div class="slider-header"><span>DAC Vol</span><span class="slider-val"><?php echo $audio['DAC_Vol']; ?>/63</span></div><input type="range" name="dac_vol" min="0" max="63" value="<?php echo $audio['DAC_Vol']; ?>"></div>
        </div>
        <div class="audio-card">
            <h4 class="audio-title">Odsłuch (Loopback)</h4>
            <div class="slider-group"><div class="switch-row"><div class="switch-label">Mic1 PB</div><input type="checkbox" name="Mic1_PB_Sw" value="1" <?php if($audio['Mic1_PB_Sw']) echo "checked"; ?>></div><input type="range" name="mic1_pb_vol" min="0" max="7" value="<?php echo $audio['Mic1_PB_Vol']; ?>"></div>
            <div class="slider-group"><div class="switch-row"><div class="switch-label">Mic2 PB</div><input type="checkbox" name="Mic2_PB_Sw" value="1" <?php if($audio['Mic2_PB_Sw']) echo "checked"; ?>></div><input type="range" name="mic2_pb_vol" min="0" max="7" value="<?php echo $audio['Mic2_PB_Vol']; ?>"></div>
            <div class="slider-group"><div class="switch-row"><div class="switch-label">LineIn PB</div><input type="checkbox" name="LineIn_PB_Sw" value="1" <?php if($audio['LineIn_PB_Sw']) echo "checked"; ?>></div><input type="range" name="linein_pb_vol" min="0" max="7" value="<?php echo $audio['LineIn_PB_Vol']; ?>"></div>
        </div>
    </div>
    <button type="submit" name="save_audio" class="btn btn-green">Zapisz Ustawienia Audio</button>
</form>
<hr style="border:0; border-top:1px solid #444; margin:30px 0;">
<form method="post"><input type="hidden" name="active_tab" class="active-tab-input" value="Audio"><button type="submit" name="reset_audio_defaults" class="btn btn-red" onclick="return confirm('UWAGA: To przywróci ustawienia audio do zdefiniowanych wartości domyślnych i zrestartuje SvxLink. Połączenie zostanie przerwane na chwilę. Kontynuować?')">⚠️ Przywróć Domyślne Audio (Z pliku)</button></form>
