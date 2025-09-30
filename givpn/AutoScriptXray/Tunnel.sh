#!/bin/sh
# Version : 2.2.0

echo ""
echo ""
echo "--- SSH Tunnel Auto Script ---"
echo "After this operation, Stunnel, Dropbear, Squid and Badvpn will be installed on your server."
read -p "Do you want to continue? [y/n]" CONT
if [[ ! $CONT =~ ^[Yy]$ ]]; then
  echo "Abort.";
  exit 100
fi

if [[ $EUID -ne 0 ]]; then
   echo -e "\e[95mYou must be root to do this.\e[0m" 1>&2
   exit 100
fi

apt-get update
apt-get upgrade -y

echo -e "\e[96mInstalling dependancies\e[0m"
apt-get install -y libnss3* libnspr4-dev gyp ninja-build git cmake libz-dev build-essential 
apt-get install -y pkg-config cmake-data net-tools libssl-dev dnsutils speedtest-cli psmisc
apt-get install -y dropbear stunnel4


echo -e "\e[96mChecking dropbear is installed\e[0m"
FILE=/etc/default/dropbear
if [ -f "$FILE" ]; then
    cp "$FILE" /etc/default/dropbear.bak
    rm "$FILE"
fi

echo -e "\e[96mCreating dropbear config\e[0m"
cat >> "$FILE" <<EOL
# disabled because OpenSSH is installed
# change to NO_START=0 to enable Dropbear
NO_START=0
# the TCP port that Dropbear listens on
DROPBEAR_PORT=444

# any additional arguments for Dropbear
DROPBEAR_EXTRA_ARGS="-p 80 -w -g"



# RSA hostkey file (default: /etc/dropbear/dropbear_rsa_host_key)
#DROPBEAR_RSAKEY="/etc/dropbear/dropbear_rsa_host_key"

# DSS hostkey file (default: /etc/dropbear/dropbear_dss_host_key)
#DROPBEAR_DSSKEY="/etc/dropbear/dropbear_dss_host_key"

# ECDSA hostkey file (default: /etc/dropbear/dropbear_ecdsa_host_key)
#DROPBEAR_ECDSAKEY="/etc/dropbear/dropbear_ecdsa_host_key"

# Receive window size - this is a tradeoff between memory and
# network performance
DROPBEAR_RECEIVE_WINDOW=65536
EOL




echo -e "\e[96mStarting dropdear services\e[0m"
/etc/init.d/dropbear start

echo -e "\e[96mChecking stunnel is installed\e[0m"
FILE3=/etc/stunnel/stunnel.conf
if [ -f "$FILE3" ]; then
	cp "$FILE3" /etc/stunnel/stunnel.conf.bak
	rm "$FILE3"
fi

echo -e "\e[96mCreating stunnel config\e[0m"
cat >> "$FILE3" <<EOL
cert = /etc/stunnel/stunnel.pem
client = no
socket = a:SO_REUSEADDR=1
socket = l:TCP_NODELAY=1
socket = r:TCP_NODELAY=1

[dropbear]
connect = 444
accept = 443
EOL

echo -e "\e[96mCreating keys\e[0m"
KEYFILE=/etc/stunnel/stunnel.pem
if [ ! -f "$KEYFILE" ]; then
	openssl genrsa -out key.pem 2048
	openssl req -new -x509 -key key.pem -out cert.pem -days 1095 -subj "/C=AU/ST=./L=./O=./OU=./CN=./emailAddress=."
	cat key.pem cert.pem >> /etc/stunnel/stunnel.pem
fi

echo -e "\e[96mEnabling stunnel services\e[0m"
sed -i 's/ENABLED=0/ENABLED=1/g' /etc/default/stunnel4

echo -e "\e[96mStarting stunnel services\e[0m"
/etc/init.d/stunnel4 start


echo -e "\e[96mInstalling squid\e[0m"
apt-get install -y squid

echo -e "\e[96mChecking squid is installed\e[0m"
FILE5=/etc/squid/squid.conf
if [ -f "$FILE5" ]; then
    cp "$FILE5" /etc/squid/squid.conf.bak
    rm "$FILE5"
fi

echo -e "\e[96mConfiguring squid\e[0m"
cat >> "$FILE5" <<EOL
acl localhost src 127.0.0.1/32 ::1
acl to_localhost dst 127.0.0.0/8 0.0.0.0/32 ::1
acl SSL_ports port 443
acl Safe_ports port 80
acl Safe_ports port 21
acl Safe_ports port 443
acl Safe_ports port 1025-65535

acl CONNECT method CONNECT
acl SSH dst ${pubip}
http_access allow SSH
http_access allow manager localhost
http_access deny manager
http_access allow localhost
http_access deny all
http_port 8080
http_port 3128
coredump_dir /var/spool/squid
refresh_pattern ^ftp: 1440 20% 10080
refresh_pattern ^gopher: 1440 0% 1440
refresh_pattern -i (/cgi-bin/|\?) 0 0% 0
refresh_pattern . 0 20% 4320
EOL

/etc/init.d/dropbear restart
/etc/init.d/stunnel4 restart
service squid restart
service ssh restart

echo " "
echo -e "\e[96mInstallation has been completed!!\e[0m"
echo " "
echo "--------------------------- Configuration Setup Server -------------------------"
echo " "
echo "Server Information"
echo "   - IP address 	: ${pubip}"
echo "   - SSH 		: 22"
echo "   - Dropbear 		: 80"
echo "   - Stunnel 		: 443"
echo "   - Badvpn 		: 7300"
echo "   - Squid 		: 8080/3128"
echo " "
echo -e "\e[95mCreate users and reboot your vps before use.\e[0m"
echo " "
