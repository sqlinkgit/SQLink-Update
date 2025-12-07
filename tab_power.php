<h4 class="panel-title">Zarządzanie Zasilaniem</h4>
<form method="post">
    <input type="hidden" name="active_tab" class="active-tab-input" value="Power">
    
    <button type="submit" name="restart_srv" class="btn btn-blue" style="margin-bottom:15px;">Restart Usługi SvxLink</button>
    
    <div style="height:10px;"></div>
    
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap:15px; margin-bottom: 20px;">
        <button type="submit" name="reboot_device" class="btn btn-orange" onclick="return confirm('Czy na pewno chcesz zrestartować CAŁY system?')">🔄 Restart Urządzenia</button>
        <button type="submit" name="shutdown_device" class="btn btn-red" onclick="return confirm('Czy na pewno chcesz WYŁĄCZYĆ urządzenie?')">🛑 Wyłącz Urządzenie</button>
    </div>

    <hr style="border: 0; border-top: 1px solid #444; margin: 20px 0;">
    <h4 class="panel-title" style="color: #FF9800; border: none;">Aktualizacja Systemu</h4>
    <button type="submit" name="git_update" class="btn btn-green">☁️ Pobierz Aktualizację Dashboardu (GitHub)</button>
</form>
