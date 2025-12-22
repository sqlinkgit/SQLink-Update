#!/usr/bin/env python3
import sys
import os
import json

CONFIG_FILE = "/etc/svxlink/svxlink.conf"
INPUT_JSON = "/tmp/svx_new_settings.json"

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
        
        if in_section and stripped.startswith(f"{key}="):
            new_lines.append(f"{key}={value}\n")
            key_found = True
        else:
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
        
        if not added:
            final_lines.append(f"{key}={value}\n")
        return final_lines

    return new_lines

def main():
    if not os.path.exists(INPUT_JSON): sys.exit(1)
    with open(INPUT_JSON, 'r') as f: data = json.load(f)

    lines = load_lines(CONFIG_FILE)

    # 1. SMART ECHOLINK: Usuń z listy modułów, jeśli brak hasła
    modules_str = data.get('Modules', 'EchoLink,Parrot')
    el_pass = data.get('EL_Password', '')
    
    if not el_pass:
        # Usuwamy EchoLink z listy modułów
        modules_list = [m.strip() for m in modules_str.split(',')]
        modules_list = [m for m in modules_list if 'EchoLink' not in m]
        modules_str = ",".join(modules_list)

    # 2. MAPPING DLA ORANGE PI
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
            "UDP_HEARTBEAT_INTERVAL": "10" 
        },
        "SimplexLogic": {
            "CALLSIGN": data.get('Callsign'),
            "RGR_SOUND_ALWAYS": data.get('RogerBeep'),
            "MODULES": modules_str
        },
        "EchoLink": { 
            "CALLSIGN": data.get('EL_Callsign'),
            "PASSWORD": el_pass,
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
                if section == "EchoLink" and cfg_key == "PROXY_SERVER" and json_val == "":
                     lines = update_key_in_lines(lines, section, cfg_key, "")
                else:
                     lines = update_key_in_lines(lines, section, cfg_key, json_val)

    save_lines(CONFIG_FILE, lines)
    print("SUKCES (Orange Pi)")

if __name__ == "__main__":
    main()