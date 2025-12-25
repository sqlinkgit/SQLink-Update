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

function initModuleToggles() {
    var input = document.getElementById('input-modules');
    if(!input) return;
    
    var currentModules = input.value.split(',').map(s => s.trim());
    
    var btnIds = ['ModuleHelp', 'ModuleParrot', 'ModuleEchoLink'];
    
    btnIds.forEach(function(modName) {
        var btn = document.getElementById('btn-' + modName);
        if(btn) {
            var shortName = modName.replace('Module', '');
            if (currentModules.includes(modName) || currentModules.includes(shortName)) {
                btn.classList.add('active');
            } else {
                btn.classList.remove('active');
            }
        }
    });

    var elPassInput = document.getElementById('el-pass');
    if(elPassInput) {
        elPassInput.addEventListener('input', function() {
            if(this.value.length > 0) {
                var elBtn = document.getElementById('btn-ModuleEchoLink');
                if(elBtn && !elBtn.classList.contains('active')) {
                    toggleModule('ModuleEchoLink');
                }
            }
        });
    }
}

function toggleModule(modName) {
    var btn = document.getElementById('btn-' + modName);
    var input = document.getElementById('input-modules');
    if(!btn || !input) return;
    
    var isActive = btn.classList.contains('active');
    var currentList = input.value.split(',').map(s => s.trim()).filter(s => s !== "");
    
    if (isActive) {
        btn.classList.remove('active');
        var shortName = modName.replace('Module', '');
        currentList = currentList.filter(s => s !== modName && s !== shortName);
    } else {
        btn.classList.add('active');
        if (!currentList.includes(modName)) {
            currentList.push(modName);
        }
    }
    
    input.value = currentList.join(',');
}

document.addEventListener("DOMContentLoaded", function() {
    var storedTab = localStorage.getItem('activeTab');
    if (storedTab) { openTab(null, storedTab); } else { openTab(null, 'Dashboard'); }
    
    if ($(".alert").length > 0) {
        setTimeout(function() {
            $(".alert").slideUp(500, function(){ $(this).remove(); });
        }, 5000);
    }
    
    initModuleToggles();
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

        let lastConnect = Math.max(
            data.lastIndexOf("ReflectorLogic: Connection established"),
            data.lastIndexOf("ReflectorLogic: Connected nodes"),
            data.lastIndexOf("ReflectorLogic: Talker start")
        );

        let lastDisconnect = Math.max(
            data.lastIndexOf("ReflectorLogic: Disconnected"),
            data.lastIndexOf("ReflectorLogic: Authentication failed")
        );
        
        let isOnline = false;
        if (data.length < 50) {
             $("#main-status-text").text("SYSTEM START...").removeClass("inactive").addClass("active");
             $("#main-status-dot").removeClass("red").addClass("orange").addClass("blink");
        } else {
             if (lastConnect > lastDisconnect || (lastConnect === -1 && lastDisconnect === -1)) {
                isOnline = true;
            } else {
                isOnline = false;
            }
        }

        if (isOnline) {
            $("#main-status-text").text("ONLINE (Reflector)").removeClass("inactive").addClass("active");
            $("#main-status-dot").removeClass("red").removeClass("orange").addClass("green").addClass("blink");
            $("#ref-status").html("PODŁĄCZONY").css("color", "#4CAF50");
        } else if (data.length >= 50) {
            $("#main-status-text").text("OFFLINE (Reflector)").removeClass("active").addClass("inactive");
            $("#main-status-dot").removeClass("green").removeClass("orange").addClass("red").removeClass("blink");
            $("#ref-status").html("ROZŁĄCZONY").css("color", "#F44336");
        }

        let lastOn = data.lastIndexOf("EchoLink directory status changed to ON");
        let lastOff = Math.max(data.lastIndexOf("EchoLink directory status changed to ?"), data.lastIndexOf("Disconnected from EchoLink proxy"));
        
        if (lastOn > lastOff) { 
            $("#el-live-status").text("CONNECTED").removeClass("el-disconnected").addClass("el-connected"); 
        } else if (lastOff > -1) { 
            $("#el-live-status").text("DISCONNECTED").removeClass("el-connected").addClass("el-disconnected"); 
        }

        let isTalking = false;
        let currentCallsign = "---";
        let currentTG = "";
        let statusText = "STAN: CZUWANIE (Standby)";

        let lastStartPos = -1; let lastStopPos = -1;
        let talkerRegex = /Talker start on TG #(\d+): ([A-Z0-9-\/]+)/g;
        let match; 
        
        while ((match = talkerRegex.exec(data)) !== null) { 
            lastStartPos = match.index; 
            currentTG = match[1]; 
            currentCallsign = match[2]; 
        }
        
        let stopRegex = /Talker stop on TG/g; 
        while ((match = stopRegex.exec(data)) !== null) { 
            lastStopPos = match.index; 
        }

        if (lastStartPos > lastStopPos && lastStartPos !== -1) {
            isTalking = true;
            statusText = "NADAWANIE (TX)..."; 
        }

        let lastTxOn = data.lastIndexOf("Tx1: Turning the transmitter ON");
        let lastTxOff = data.lastIndexOf("Tx1: Turning the transmitter OFF");
        
        if (lastTxOn > lastTxOff && lastTxOn !== -1) {
            if(!isTalking) {
                isTalking = true;
                statusText = "NADAWANIE (TX)..."; 
            }
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
}


var cachedNodesData = {};

function updateNodes() {
    $.getJSON('nodes.php', function(data) {
        if (!data || !data.nodes) return;
        cachedNodesData = data.nodes;
        
        var myCall = GLOBAL_CALLSIGN;
        var nodeKeys = Object.keys(data.nodes).sort();
        
        var html = "";
        
        if (nodeKeys.length === 0) {
            html = "<div style='grid-column:1/-1;text-align:center;color:#777;'>Brak aktywnych węzłów</div>";
        } else {
            nodeKeys.forEach(function(call) {
                var isMe = (call === myCall);
                var cssClass = isMe ? "node-item is-me" : "node-item";
                
                html += `<div class="${cssClass}" onclick="window.open('https://www.qrz.com/db/${call}', '_blank')" onmouseenter="showTooltip(event, '${call}')" onmouseleave="hideTooltip()" onmousemove="moveTooltip(event)">
                            <span class="node-icon">📻</span>
                            <span class="node-name">${call}</span>
                         </div>`;
            });
        }
        
        $("#nodes-content").html(html);
    });
}

function showTooltip(e, callsign) {
    if (!cachedNodesData[callsign]) return;
    
    var info = cachedNodesData[callsign];
    var tooltip = document.getElementById('node-tooltip');
    
    $("#nt-callsign").text(callsign);
    $("#nt-sw").text((info.sw || "") + " " + (info.swVer || ""));
    
    var name = "---";
    if (info.Sysop) {
         name = info.Sysop;
    } else if (info.sysop) {
         name = info.sysop;
    } else if (info.qth && info.qth.length > 0 && info.qth[0].name) {
         name = info.qth[0].name;
    }
    $("#nt-name").text(name);

    var activeTg = (info.tg && info.tg !== 0) ? info.tg : "Brak (Czuwanie)";
    $("#nt-tg").text(activeTg);
    
    var locator = "---";
    if (info.Locator) {
        locator = info.Locator;
    } else if (info.qth && info.qth.length > 0 && info.qth[0].pos && info.qth[0].pos.loc) {
        locator = info.qth[0].pos.loc;
    }
    $("#nt-qth").text(locator);

    var location = "---";
    if (info.Location) {
        location = info.Location;
    } else if (info.nodeLocation) {
        location = info.nodeLocation;
    }
    $("#nt-loc").text(location);
    
    var monitored = "---";
    if (info.monitoredTGs && Array.isArray(info.monitoredTGs) && info.monitoredTGs.length > 0) {
        monitored = info.monitoredTGs.join(", ");
    }
    $("#nt-monitored").text(monitored);
    
    $("#nt-ver").text(info.projVer || "---");

    tooltip.style.display = 'block';
    moveTooltip(e);
}

function moveTooltip(e) {
    var tooltip = document.getElementById('node-tooltip');
    if(tooltip.style.display === 'block') {
        var x = e.clientX + 15;
        var y = e.clientY + 15;
        
        if (x + 240 > window.innerWidth) { x = e.clientX - 240; }
        if (y + 300 > window.innerHeight) { y = e.clientY - 300; }
        
        tooltip.style.left = x + 'px';
        tooltip.style.top = y + 'px';
    }
}

function hideTooltip() {
    document.getElementById('node-tooltip').style.display = 'none';
}

setInterval(loadLogsAndStatus, 1500);
setInterval(updateStats, 3000);
setInterval(updateNodes, 5000);

loadLogsAndStatus();
updateStats();
updateNodes();