# KelasQu

Aplikasi kelas online berbasis Laravel 10, Bootstrap 5, dan MySQL. KelasQu menyediakan pendaftaran dan login, dashboard terpisah untuk admin dan siswa, serta daftar pengguna yang hanya dapat diakses admin.

## Persiapan database

Buat database MySQL bernama `kelasqu` (misalnya melalui HeidiSQL/phpMyAdmin Laragon):

```sql
CREATE DATABASE kelasqu CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Pastikan konfigurasi `.env` sesuai dengan MySQL lokal:

```env
APP_NAME=KelasQu
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kelasqu
DB_USERNAME=root
DB_PASSWORD=
```

## Menjalankan aplikasi di Laragon

```bash
composer install
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Buka alamat yang ditampilkan oleh `artisan serve` (umumnya `http://127.0.0.1:8000`). Bootstrap dan ikon dimuat dari CDN, jadi browser memerlukan koneksi internet.

Seeder membuat akun admin awal:

- Email: `admin@kelasqu.test`
- Kata sandi: `KelasQuAdmin2026!`

Ubah `KELASQU_ADMIN_EMAIL` dan `KELASQU_ADMIN_PASSWORD` di `.env` sebelum menjalankan seeder di lingkungan bersama/produksi. Pendaftaran publik selalu membuat akun dengan peran siswa; peran admin tidak dapat dipilih dari form pendaftaran.

Untuk pengujian otomatis, aktifkan ekstensi `pdo_sqlite` dan `sqlite3` pada `php.ini`, lalu jalankan `php artisan test`. Suite tes menggunakan SQLite in-memory agar tidak mengubah database MySQL lokal.
