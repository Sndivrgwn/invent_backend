# Invent – Sistem Manajemen Inventaris Barang TKJ

**Invent** adalah sistem  berbasis Laravel 11 yang dirancang untuk memudahkan pengelolaan inventaris barang di lingkungan sekolah, khususnya pada jurusan **Teknik Komputer dan Jaringan (TKJ)**. Sistem ini mendukung pengelolaan barang, proses peminjaman, pengembalian, hingga pelacakan histori.

---

##  Teknologi

- PHP 8+
- Laravel 11
- MySQL
- Node.js (untuk asset builder via Vite)

---

##  Fitur

- Manajemen barang (tambah, edit, hapus)
- Pengelompokan berdasarkan kategori, brand, status, kondisi
- Sistem peminjaman dan pengembalian barang
- Role-based access control (Admin, User)
- Dashboard ringkasan data
- Histori peminjaman
- Export data

---

##  Instalasi

```bash
# Clone repository
git clone https://github.com/Sndivrgwn/invent_backend.git
cd invent_backend

# Install dependency
composer install

# Copy dan konfigurasi file .env
cp .env.example .env
php artisan key:generate

# Setup database
php artisan migrate --seed

# Jalankan server
php artisan serve

# Jalankan Queue Worker
php artisan queue:work

# Jalankan Vite (frontend assets)
npm install
npm run dev
```


## Demo

http://invents.httpstjktsatu.my.id/dashboard
