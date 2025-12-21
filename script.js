$.ajaxSetup({ cache: false });

function selectWifi(ssid) { document.getElementById('wifi-ssid').value = ssid; }

var dtmfBuffer = ""; 
var display = document.getElementById("dtmf-screen");

function typeKey(key) { dtmfBuffer += key; display.innerHTML = dtmfBuffer; }
function clearKey() { dtmfBuffer = ""; display.innerHTML = "..."; }
function submitKey() { if(dtmfBuffer.length > 0) { sendAjax(dtmfBuffer); clearKey(); } }

function submitTG() {
    if(dtmfBuffer.length > 0) {
        sendAjax("*91" + dtmfBuffer + "#");
        clearKey();
    }
}

function connectEchoLink() {
    var node = document.getElementById('el-node-id').value;
    if(node.length > 0) { sendAjax(node + "#"); }
}

function sendInstant(code) { sendAjax(code); }
function sendAjax(code) { $.post("index.php", {ajax_dtmf: code}, function(result) { console.log(result); }); }

function openTab(evt, tabName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tab-content");
    for (i = 0; i < tabcontent.length; i++) { tabcontent[i].style.display = "none"; }
    tablinks = document.getElementsByClassName("tab-btn");
    for (i = 0; i < tablinks.length; i++) { tablinks[i].className = tablinks[i].className.replace(" active", ""); }
    document.getElementById(tabName).style.display = "block";
    if(evt) { evt.currentTarget.className += " active"; } else { var btn = document.getElementById("btn-" + tabName); if(btn) btn.className += " active"; }
    var inputs = document.getElementsByClassName("active-tab-input");
    for(var j=0; j<inputs.length; j++) { inputs[j].value = tabName; }
    localStorage.setItem('activeTab', tabName);
}

document.addEventListener("DOMContentLoaded", function() {
    var storedTab = localStorage.getItem('activeTab');
    if (storedTab) { openTab(null, storedTab); } else { openTab(null, 'Dashboard'); }
    
    if ($(".alert").length > 0) {
        setTimeout(function() {
            $(".alert").slideUp(500, function(){ $(this).remove(); });
        }, 5000);
    }
});

function updateStats() {
    $.getJSON('index.php?ajax_stats=1', function(stats) {
        $("#t-temp").text(stats.temp + "°C"); $("#t-temp-bar").css("width", Math.min(stats.temp, 100) + "%").attr("class", "progress-fill " + (stats.temp>70?'p-red':(stats.temp>60?'p-orange':'')));
        $("#t-ram").text(stats.ram_percent + "%"); $("#t-ram-bar").css("width", stats.ram_percent + "%").attr("class", "progress-fill " + (stats.ram_percent>90?'p-red':(stats.ram_percent>70?'p-orange':'')));
        $("#t-disk").text(stats.disk_percent + "%"); $("#t-disk-bar").css("width", stats.disk_percent + "%").attr("class", "progress-fill " + (stats.disk_percent>90?'p-red':''));
        $("#t-hw").text(stats.hw.substring(0, 25) + (stats.hw.length>25?"...":""));
        $("#t-net-type").text(stats.net_type + (stats.net_type == "WiFi" ? " (" + stats.ssid + ")" : ""));
        $("#t-ip").text(stats.ip);
        $("#wifi-tab-status").text(stats.net_type + (stats.net_type == "WiFi" ? ": " + stats.ssid : ""));
        $("#wifi-tab-ip").text("IP: " + stats.ip);
    });
}

function loadLogsAndStatus() {
    $.get('logs.php?t=' + Date.now(), function(data) {
        
        var logLines = data.trim().split('\n');
        var reversedData = logLines.reverse().join('\n');
        $('#log-content').html(reversedData);

        let lastConnect = data.lastIndexOf("ReflectorLogic: Connection established");
        let lastDisconnect = data.lastIndexOf("ReflectorLogic: Disconnected");
        let lastAuthFail = data.lastIndexOf("ReflectorLogic: Authentication failed");
        let lastBad = Math.max(lastDisconnect, lastAuthFail);

        if (lastConnect > lastBad) {
            $("#main-status-text").text("ONLINE (Reflector)").removeClass("inactive").addClass("active");
            $("#main-status-dot").removeClass("red").addClass("green").addClass("blink");
            $("#ref-status").html("PODŁĄCZONY").css("color", "#4CAF50");
        } else {
            $("#main-status-text").text("OFFLINE (Reflector)").removeClass("active").addClass("inactive");
            $("#main-status-dot").removeClass("green").addClass("red").removeClass("blink");
            $("#ref-status").html("ROZŁĄCZONY").css("color", "#F44336");
        }

        let lastOn = data.lastIndexOf("EchoLink directory status changed to ON");
        let lastOff = Math.max(data.lastIndexOf("EchoLink directory status changed to ?"), data.lastIndexOf("Disconnected from EchoLink proxy"));
        if (lastOn > lastOff) { $("#el-live-status").text("CONNECTED").removeClass("el-disconnected").addClass("el-connected"); }
        else { $("#el-live-status").text("DISCONNECTED - Sprawdź Config!").removeClass("el-connected").addClass("el-disconnected"); }

        let isTalking = false;
        let currentCallsign = "---";
        let currentTG = "";
        let statusText = "STAN: CZUWANIE (Standby)";

        let lastStartPos = -1; let lastStopPos = -1;
        let talkerRegex = /Talker start on TG #(\d+): ([A-Z0-9-\/]+)/g;
        let match; while ((match = talkerRegex.exec(data)) !== null) { lastStartPos = match.index; currentTG = match[1]; currentCallsign = match[2]; }
        let stopRegex = /Talker stop on TG/g; while ((match = stopRegex.exec(data)) !== null) { lastStopPos = match.index; }

        if (lastStartPos > lastStopPos && lastStartPos !== -1) {
            isTalking = true;
            statusText = "NADAWANIE (TX)..."; 
        }

        let lastTxOn = data.lastIndexOf("Tx1: Turning the transmitter ON");
        let lastTxOff = data.lastIndexOf("Tx1: Turning the transmitter OFF");
        if (lastTxOn > lastTxOff && lastTxOn !== -1) {
            isTalking = true;
            statusText = "NADAWANIE (TX)..."; 
        }

        let lastSqOpen = data.lastIndexOf("Rx1: The squelch is OPEN");
        let lastSqClose = data.lastIndexOf("Rx1: The squelch is CLOSED");
        if (lastSqOpen > lastSqClose && lastSqOpen !== -1) {
            isTalking = true;
            statusText = "ODBIERANIE (RX - LOCAL)...";
            currentCallsign = "LOKALNIE"; 
        }

        $(".live-box").removeClass("talking rx-active tx-active");

        if (isTalking) {
            $(".live-status").text(statusText);
            $(".live-callsign").text(currentCallsign);
            if(currentTG) $(".live-tg").text("TG " + currentTG).css("color", "#FF9800");

            if (statusText.includes("RX") || statusText.includes("ODBIERANIE")) {
                $(".live-box").addClass("rx-active");
                $(".live-status, .live-callsign").css("color", "#4CAF50");
            } else {
                $(".live-box").addClass("tx-active");
                $(".live-status, .live-callsign").css("color", "#FF9800");
            }
        } else {
            $(".live-status").text("STAN: CZUWANIE (Standby)").css("color", "#666");
            $(".live-callsign").text("---").css("color", "#fff");
            $(".live-tg").text("");
        }
    });

    $.get('last_heard.php?t=' + Date.now(), function(data) { $('#lh-content').html(data); });

    $.getJSON('nodes.php?t=' + Date.now(), function(nodes) {
        let html = "";
        let myCall = GLOBAL_CALLSIGN;
        let sortedKeys = Object.keys(nodes).sort();
        if(nodes[myCall]) { sortedKeys = sortedKeys.filter(n => n !== myCall); sortedKeys.unshift(myCall); }
        if (sortedKeys.length === 0) { html = "<div style='color:#777; grid-column:1/-1; text-align:center;'>Brak danych...</div>"; }
        else {
            sortedKeys.forEach(call => {
                let info = nodes[call];
                let isMe = (call === myCall);
                let style = isMe ? "border-color:#4CAF50; background:#2e352e;" : "";
                let tgBadge = (info.tg && info.tg !== '' && info.tg !== '?') ? `<div style="margin-left:auto; background:#FF9800; color:#000; font-size:10px; padding:2px 5px; border-radius:3px; font-weight:bold;">TG ${info.tg}</div>` : '';
                html += `<div class="node-item" style="${style}"><div class="node-icon">📻</div><div class="node-name">${call} ${isMe ? '(TY)' : ''}</div>${tgBadge}</div>`;
            });
        }
        $("#nodes-content").html(html);
    });
}

setInterval(loadLogsAndStatus, 1500);
setInterval(updateStats, 3000);
loadLogsAndStatus();
updateStats();