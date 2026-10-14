# KelasQu

Aplikasi kelas online berbasis Laravel 10, Bootstrap 5, dan MySQL. KelasQu menyediakan pendaftaran/login, dashboard per peran, katalog dan manajemen kelas, materi serta progres, sertifikat, pengelolaan pengguna, pemulihan kata sandi, dan laporan.

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

## Alur penggunaan

1. Admin membuka **Kategori** dan membuat kategori kelas.
2. Admin membuka **Kelas** lalu membuat kelas sebagai draft.
3. Dari halaman edit kelas, tambahkan minimal satu materi. Isi materi berupa teks; tautan video opsional dibuka di tab baru.
4. Ubah status kelas ke **Publikasikan**. Kelas tanpa materi tidak dapat dipublikasikan.
5. Pendaftar memverifikasi alamat email melalui tautan email sebelum membuka dashboard. Siswa membuka **Jelajahi Kelas**, mencari kelas, lalu bergabung gratis.
6. Siswa membuka materi, menandai tiap materi selesai, dan menerima sertifikat setelah seluruh materi selesai. Sertifikat dapat dicetak atau disimpan ke PDF melalui browser.
7. Admin dapat mencari pengguna, mengubah peran, dan menonaktifkan akun. Sistem melindungi administrator aktif terakhir.

Pendaftaran kelas saat ini gratis; pembayaran, unggah video langsung, dan unggah berkas materi belum termasuk. Atur `MAIL_MAILER` dan kredensial SMTP yang valid di `.env` agar email verifikasi dan tautan pemulihan kata sandi dapat dikirim. Untuk pengembangan lokal, Mailpit Laragon dapat digunakan.

Untuk pengujian otomatis, aktifkan ekstensi `pdo_sqlite` dan `sqlite3` pada `php.ini`, lalu jalankan `php artisan test`. Suite tes menggunakan SQLite in-memory agar tidak mengubah database MySQL lokal.
