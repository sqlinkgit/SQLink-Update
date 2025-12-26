#!/usr/bin/env python3
import sys
import os
import json

CONFIG_FILE = "/etc/svxlink/svxlink.conf"
INPUT_JSON = "/tmp/svx_new_settings.json"
RADIO_JSON = "/var/www/html/radio_config.json"
NODE_INFO_FILE = "/etc/svxlink/node_info.json"

def load_lines(path):
    if not os.path.exists(path): return []
    with open(path, 'r', encoding='utf-8', errors='ignore') as f: return f.readlines()

def save_lines(path, lines):
    with open(path, 'w', encoding='utf-8') as f: f.writelines(lines)

def update_key_in_lines(lines, section, key, value):
    new_lines = []
    in_section = False
    key_found = False
    section_header = f"[{section}]"
    section_exists = False
    
    for line in lines:
        if line.strip() == section_header:
            section_exists = True
            break
            
    if not section_exists:
        lines.append(f"\n{section_header}\n")

    for line in lines:
        stripped = line.strip()
        if stripped.startswith("[") and stripped.endswith("]"):
            in_section = (stripped == section_header)
        
        if in_section and "=" in stripped and not stripped.startswith(("#", ";")):
            parts = stripped.split("=", 1)
            current_key = parts[0].strip()
            if current_key == key:
                new_lines.append(f"{key}={value}\n")
                key_found = True
                continue 

        new_lines.append(line)

    if not key_found:
        final_lines = []
        in_tgt_sec = False
        added = False
        for l in new_lines:
            s = l.strip()
            if s == section_header:
                in_tgt_sec = True
                final_lines.append(l)
                continue
            if in_tgt_sec and s.startswith("["):
                if not added:
                    final_lines.append(f"{key}={value}\n")
                    added = True
                in_tgt_sec = False
            final_lines.append(l)
        if in_tgt_sec and not added:
             final_lines.append(f"{key}={value}\n")
             added = True
        if not added:
             final_lines.append(f"{key}={value}\n")
        return final_lines

    return new_lines

def main():
    if not os.path.exists(INPUT_JSON): sys.exit(1)
    with open(INPUT_JSON, 'r') as f: data = json.load(f)

    lines = load_lines(CONFIG_FILE)

    
    modules_str = data.get('Modules')
    if modules_str is not None:
        
        el_pass = data.get('EL_Password', '')
        if not el_pass:
            modules_list = [m.strip() for m in modules_str.split(',')]
            modules_list = [m for m in modules_list if 'EchoLink' not in m]
            modules_str = ",".join(modules_list)
        
        
        clean_modules = []
        for m in modules_str.split(','):
            m = m.strip()
            if m.startswith("Module"):
                clean_modules.append(m.replace("Module", ""))
            else:
                clean_modules.append(m)
        
        
        data['Modules'] = ",".join(clean_modules)

    qth_name = data.get('qth_name', '')
    qth_city = data.get('qth_city', '')
    qth_loc = data.get('qth_loc', '')

    rx_freq = ""
    tx_freq = ""
    ctcss = "0"
    if os.path.exists(RADIO_JSON):
        try:
            with open(RADIO_JSON, 'r') as rf:
                rdata = json.load(rf)
                rx_freq = rdata.get("rx", "")
                tx_freq = rdata.get("tx", "")
                ctcss = rdata.get("ctcss", "0")
        except: pass

    
    is_echolink = "0"
    if data.get('Modules') and "EchoLink" in data['Modules']:
        is_echolink = "1"

    node_info_data = {
        "Location": qth_city,
        "Locator": qth_loc,
        "Sysop": qth_name,
        "LAT": "0.0", "LONG": "0.0",
        "TXFREQ": tx_freq, "RXFREQ": rx_freq, "CTCSS": ctcss,
        "DefaultTG": data.get('DefaultTG', '0'),
        "Mode": "FM", "Type": "1", 
        "Echolink": is_echolink,
        "Website": "http://sqlink.pl",
        "LinkedTo": "SQLink"
    }

    try:
        with open(NODE_INFO_FILE, 'w') as nf: json.dump(node_info_data, nf, indent=4)
        os.chmod(NODE_INFO_FILE, 0o644) 
    except Exception as e: print(f"Error writing node_info.json: {e}")

    loc_parts = []
    if qth_city: loc_parts.append(qth_city)
    if qth_loc: loc_parts.append(qth_loc)
    if qth_name: loc_parts.append(f"(Op: {qth_name})")
    location_str = ", ".join(loc_parts)

    mapping = {
        "ReflectorLogic": {
            "CALLSIGN": data.get('Callsign'),
            "AUTH_KEY": data.get('Password'),
            "HOSTS": data.get('Host'),
            "HOST_PORT": data.get('Port'),
            "DEFAULT_TG": data.get('DefaultTG'),
            "MONITOR_TGS": data.get('MonitorTGs'),
            "TG_SELECT_TIMEOUT": data.get('TgTimeout'),
            "TMP_MONITOR_TIMEOUT": data.get('TmpTimeout'),
            "TGSTBEEP_ENABLE": data.get('Beep3Tone'),
            "TGREANON_ENABLE": data.get('AnnounceTG'),
            "REFCON_ENABLE": data.get('RefStatusInfo'),
            "UDP_HEARTBEAT_INTERVAL": "10",
            "LOCATION": f'"{location_str}"',
            "NODE_INFO_FILE": NODE_INFO_FILE
        },
        "SimplexLogic": {
            "CALLSIGN": data.get('Callsign'),
            "RGR_SOUND_ALWAYS": data.get('RogerBeep'),
            "MODULES": data.get('Modules')
        },
        "ModuleEchoLink": {
            "CALLSIGN": data.get('EL_Callsign'),
            "PASSWORD": data.get('EL_Password'),
            "SYSOPNAME": data.get('EL_Sysop'),
            "LOCATION": data.get('EL_Location'),
            "DESCRIPTION": data.get('EL_Desc'),
            "PROXY_SERVER": data.get('EL_ProxyHost'),
            "TIMEOUT": data.get('EL_ModTimeout'),
            "LINK_IDLE_TIMEOUT": data.get('EL_IdleTimeout')
        }
    }

    for section, keys in mapping.items():
        for cfg_key, json_val in keys.items():
            if json_val is not None:
                lines = update_key_in_lines(lines, section, cfg_key, str(json_val))

    save_lines(CONFIG_FILE, lines)

    radio_data = {}
    if os.path.exists(RADIO_JSON):
        with open(RADIO_JSON, 'r') as f:
            try: radio_data = json.load(f)
            except: pass

    if 'qth_name' in data: radio_data['qth_name'] = qth_name
    if 'qth_city' in data: radio_data['qth_city'] = qth_city
    if 'qth_loc' in data: radio_data['qth_loc'] = qth_loc

    with open(RADIO_JSON, 'w') as f: json.dump(radio_data, f, indent=4)

    print("SUKCES")

if __name__ == "__main__":
    main()