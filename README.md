# Culture Bike

Sistem informasi booking sewa dan servis sepeda berbasis web.

Project UAS mata kuliah Rekayasa Perangkat Lunak
Program Studi Sistem Informasi — Universitas Muhammadiyah Pontianak

Dosen Pengampu: Riski Surtiyan Surya, S.Kom., M.Kom

---

## Tim

| Nama | Bagian |
|---|---|
| Meyza Putri Santhyca | Backend (auth, katalog, sewa, pembayaran, riwayat) |
| Utin Syarifah Nurzakia | Backend (servis, admin) |
| Aeyma Syahira | Laporan |
| Setya Ayu Lestari | Laporan |

Analisis dan perancangan dikerjakan bersama.

---

## Teknologi

- Laravel 12
- PHP 8.2+
- MySQL
- Blade
- Vite

---

## Cara Install

### 1. Clone repo

```bash
git clone https://github.com/Meyza-bit/UASRPLPROJECT4.git
cd UASRPLPROJECT4
```

### 2. Install dependency

```bash
composer install
npm install
```

### 3. Siapkan file .env

```bash
copy .env.example .env
php artisan key:generate
```

### 4. Buat database

Nyalakan MySQL (XAMPP / Laragon), lalu buat database bernama `uasrplproject4`.

Lewat phpMyAdmin: buka `localhost/phpmyadmin` → New → nama `uasrplproject4` → Create.

Atau lewat terminal:

```bash
mysql -u root -e "CREATE DATABASE uasrplproject4"
```

### 5. Sesuaikan .env

Buka file `.env`, pastikan bagian database seperti ini:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=uasrplproject4
DB_USERNAME=root
DB_PASSWORD=
```

### 6. Jalankan migration

```bash
php artisan migrate --seed
```

### 7. Jalankan project

Buka dua terminal.

Terminal 1:
```bash
php artisan serve
```

Terminal 2:
```bash
npm run dev
```

Buka http://127.0.0.1:8000

---

## Alur Kerja Tim

Repo ini punya tiga branch:

| Branch | Isi |
|---|---|
| `main` | Kode yang sudah jadi dan berjalan |
| `mey` | Kerjaan Meyza |
| `kia` | Kerjaan Utin (Kia) |

Kerjakan di branch masing-masing, jangan langsung di `main`.

```bash
git checkout mey        # atau kia
git add .
git commit -m "tambah halaman katalog"
git push
```

Kalau kerjaan sudah selesai dan mau digabung ke `main`, buat Pull Request di GitHub agar bisa dicek dulu oleh anggota lain.

Tulis pesan commit yang jelas. Contoh:

- `tambah halaman katalog sepeda`
- `perbaiki validasi form login`
- `update README`

Hindari pesan seperti `update`, `fix`, atau `asdasd`.

---

## Papan Kerja

Progress dan pembagian tugas ada di GitHub Projects: **UAS RPL - Culture Bike**

Daftar task ada di tab [Issues](https://github.com/Meyza-bit/UASRPLPROJECT4/issues).

Label yang dipakai:

| Label | Arti |
|---|---|
| `analisis` | Analisis & perancangan sistem |
| `BE-mey` | Backend bagian Meyza |
| `BE-kia` | Backend bagian Kia |
| `laporan` | Dokumen laporan |
| `customer` | Sisi customer |
| `admin` | Sisi admin |
| `prioritas` | Dikerjakan duluan |

---

## Troubleshooting

**`SQLSTATE[HY000] [1049] Unknown database`**
Database belum dibuat. Ulangi langkah 4.

**`Connection refused`**
MySQL belum menyala. Nyalakan lewat XAMPP / Laragon.

**Halaman tampil tanpa CSS**
`npm run dev` belum jalan. Jalankan di terminal terpisah.

**Perubahan di .env tidak terbaca**
```bash
php artisan config:clear
```
