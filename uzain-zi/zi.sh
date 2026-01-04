#!/bin/bash
# Zivpn UDP Module installer
# Creator Zahid Islam

echo -e "Updating server"
sudo apt-get update -y
systemctl stop zivpn.service 1> /dev/null 2> /dev/null
echo -e "Downloading UDP Service"
wget https://github.com/abcwifi/abcwifi.github.io/raw/refs/heads/master/uzain-zi/udp-zivpn-linux-amd64 -O /root/udp/log/zivpn 1> /dev/null 2> /dev/null
chmod +x /root/udp/log/zivpn
mkdir /root/udp/log 1> /dev/null 2> /dev/null
wget https://raw.githubusercontent.com/abcwifi/abcwifi.github.io/refs/heads/master/uzain-zi/config.json -O /root/udp/log/config.json 1> /dev/null 2> /dev/null

echo "Generating cert files:"
openssl req -new -newkey rsa:4096 -days 3650 -nodes -x509 -subj "/C=US/ST=Jakarta/L=Indonesia/O=Example Corp/OU=IT Department/CN=zivpn" -keyout "/root/udp/log/zivpn.key" -out "/root/udp/log/zivpn.crt"
sysctl -w net.core.rmem_max=16777216 1> /dev/null 2> /dev/null
sysctl -w net.core.wmem_max=16777216 1> /dev/null 2> /dev/null
cat <<EOF > /etc/systemd/system/zivpn.service
[Unit]
Description=zivpn VPN Server
After=network.target

[Service]
Type=simple
User=root
WorkingDirectory=/root/udp/log
ExecStart=/root/udp/log/zivpn server -c /root/udp/log/config.json
Restart=always
RestartSec=3
Environment=ZIVPN_LOG_LEVEL=info
CapabilityBoundingSet=CAP_NET_ADMIN CAP_NET_BIND_SERVICE CAP_NET_RAW
AmbientCapabilities=CAP_NET_ADMIN CAP_NET_BIND_SERVICE CAP_NET_RAW
NoNewPrivileges=true

[Install]
WantedBy=multi-user.target
EOF

echo -e "ZIVPN UDP Passwords"
read -p "Enter passwords separated by commas, example: pass1,pass2 (Press enter for Default 'zi'): " input_config

if [ -n "$input_config" ]; then
    IFS=',' read -r -a config <<< "$input_config"
    if [ ${#config[@]} -eq 1 ]; then
        config+=(${config[0]})
    fi
else
    config=("zi")
fi

new_config_str="\"config\": [$(printf "\"%s\"," "${config[@]}" | sed 's/,$//')]"

sed -i -E "s/\"config\": ?\[[[:space:]]*\"zi\"[[:space:]]*\]/${new_config_str}/g" /root/udp/log/config.json


systemctl enable zivpn.service
systemctl start zivpn.service
iptables -t nat -A PREROUTING -i $(ip -4 route ls|grep default|grep -Po '(?<=dev )(\S+)'|head -1) -p udp --dport 6000:19999 -j DNAT --to-destination :5667
sudo apt install ufw -y
ufw allow 6000:19999/udp
ufw allow 5667/udp
rm zi.* 1> /dev/null 2> /dev/null
echo -e "ZIVPN UDP Installed"
