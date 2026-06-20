#!/bin/bash

# Keluar dari skrip jika ada error
set -e

echo "=== Memulai Setup Server Laravel (Ubuntu 24.04) ==="

# 1. Update paket dan instal dependensi PHP 8.3 + Nginx
echo "Menginstal Nginx, PHP 8.3, dan extension pendukung..."
sudo apt update
sudo apt install -y php8.3-cli php8.3-fpm php8.3-sqlite3 php8.3-curl php8.3-xml php8.3-mbstring php8.3-zip php8.3-bcmath php8.3-intl php8.3-gd php8.3-mysql nginx git unzip

# 2. Instal Composer secara global
if ! command -v composer &> /dev/null; then
    echo "Menginstal Composer..."
    curl -sS https://getcomposer.org/installer | php
    sudo mv composer.phar /usr/local/bin/composer
else
    echo "Composer sudah terinstal."
fi

# 3. Tentukan path proyek
PROJECT_PATH="/var/www/project-lms"

if [ -d "$PROJECT_PATH" ]; then
    echo "Mengonfigurasi perizinan folder di $PROJECT_PATH..."
    sudo chown -R www-data:www-data "$PROJECT_PATH"
    sudo chmod -R 775 "$PROJECT_PATH/storage"
    sudo chmod -R 775 "$PROJECT_PATH/bootstrap/cache"
    
    # Perizinan untuk SQLite agar web server (www-data) bisa menulis database
    sudo chmod 775 "$PROJECT_PATH/database"
    if [ -f "$PROJECT_PATH/database/database.sqlite" ]; then
        sudo chmod 664 "$PROJECT_PATH/database/database.sqlite"
    fi
    echo "Perizinan folder selesai diatur."
else
    echo "PERINGATAN: Folder proyek di $PROJECT_PATH belum ditemukan. Silakan pindahkan kode proyek Anda ke folder tersebut terlebih dahulu, kemudian jalankan kembali bagian perizinan."
fi

# 4. Membuat Konfigurasi Nginx
echo "Membuat konfigurasi server block Nginx..."
cat << 'EOF' | sudo tee /etc/nginx/sites-available/project-lms
server {
    listen 80;
    server_name _; # Menerima koneksi dari IP VM apa saja
    root /var/www/project-lms/public;

    index index.php index.html index.htm;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
    }

    location ~ /\.ht {
        deny all;
    }
}
EOF

# Aktifkan konfigurasi Nginx dan matikan default site jika ada
echo "Mengaktifkan konfigurasi Nginx..."
sudo ln -sf /etc/nginx/sites-available/project-lms /etc/nginx/sites-enabled/
if [ -f /etc/nginx/sites-enabled/default ]; then
    sudo rm -f /etc/nginx/sites-enabled/default
fi

echo "Menguji konfigurasi Nginx..."
sudo nginx -t

echo "Memuat ulang Nginx..."
sudo systemctl reload nginx

echo "=== Setup Server Selesai dengan Sukses! ==="
