## 📥 Download Script
```bash
wget https://raw.githubusercontent.com/abcwifi/abcwifi.github.io/refs/heads/master/zum/zi.sh -O zi.sh
chmod +x zi.sh
./zi.sh help
```

### ▶️ Install 
```bash
bash <(wget -qO- https://raw.githubusercontent.com/abcwifi/abcwifi.github.io/refs/heads/master/zum/zi.sh) install
```

### 💾 Backup Konfigurasi
```bash
bash <(wget -qO- https://raw.githubusercontent.com/abcwifi/abcwifi.github.io/refs/heads/master/zum/zi.sh) backup
```

### ♻️ Restore Konfigurasi
```bash
bash <(wget -qO- https://raw.githubusercontent.com/potatonc/zivpn-udp/refs/heads/main/zi.sh) restore
```

### ❌ Uninstall ZIVPN UDP
```bash
bash <(wget -qO- https://raw.githubusercontent.com/abcwifi/abcwifi.github.io/refs/heads/master/zum/zi.sh) uninstall
```

### ➕ Tambah Akun
```bash
bash <(wget -qO- https://raw.githubusercontent.com/abcwifi/abcwifi.github.io/refs/heads/master/zum/zi.sh) add
```

### ➖ Hapus Akun
```bash
bash <(wget -qO- https://raw.githubusercontent.com/abcwifi/abcwifi.github.io/refs/heads/master/zum/zi.sh) del
```

### 📋 List Akun
```bash
bash <(wget -qO- https://raw.githubusercontent.com/abcwifi/abcwifi.github.io/refs/heads/master/zum/zi.sh) list
```

## 🔁 Backup & Restore
### 📦 Lokasi Backup
File backup konfigurasi disimpan di lokasi berikut:
```text
/root/config.json.zivpn
```


## ♻️ Restore dari Konfigurasi Lain
Jika ingin melakukan restore menggunakan konfigurasi lain, silakan ikuti langkah berikut:

1. Buat atau siapkan file konfigurasi yang diinginkan.
2. Simpan file konfigurasi tersebut ke lokasi berikut: **/root/config.json**
3. Pastikan struktur dan format konfigurasi **sesuai dengan standar**.
4. Jalankan perintah **restore** seperti yang telah dijelaskan pada bagian **penggunaan** di atas.



