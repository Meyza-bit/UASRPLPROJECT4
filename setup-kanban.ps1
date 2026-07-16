# ============================================
#  Setup Kanban - UAS RPL Culture Bike
#  Repo: Meyza-bit/UASRPLPROJECT4
# ============================================

$gh    = "C:\Program Files\GitHub CLI\gh.exe"
$repo  = "Meyza-bit/UASRPLPROJECT4"
$owner = "Meyza-bit"

Write-Host ""
Write-Host "=== [1/4] Bikin label ===" -ForegroundColor Cyan

$labels = @(
    @{n="analisis";  c="8B5CF6"; d="Analisis & perancangan sistem"},
    @{n="BE-mey";    c="B91C1C"; d="Backend - Mey"},
    @{n="BE-kia";    c="EA580C"; d="Backend - Kia"},
    @{n="laporan";   c="0891B2"; d="Dokumen laporan"},
    @{n="customer";  c="F59E0B"; d="Sisi customer"},
    @{n="admin";     c="7C2D12"; d="Sisi admin"},
    @{n="prioritas"; c="DC2626"; d="Harus duluan"}
)

foreach ($l in $labels) {
    & $gh label create $l.n --repo $repo --color $l.c --description $l.d --force 2>&1 | Out-Null
    Write-Host "  + $($l.n)" -ForegroundColor DarkGray
}

Write-Host ""
Write-Host "=== [2/4] Bikin issue ===" -ForegroundColor Cyan

$issues = @(
    # ---------- ANALISIS (bareng semua) ----------
    @{t="[Analisis] Identifikasi kebutuhan fungsional & non-fungsional"
      b="Daftar kebutuhan sistem sewa & servis sepeda Culture Bike.`n`n- [ ] Kebutuhan fungsional (customer)`n- [ ] Kebutuhan fungsional (admin)`n- [ ] Kebutuhan non-fungsional`n`nDikerjakan bareng tim."
      l="analisis,prioritas"},

    @{t="[Analisis] Use Case Diagram"
      b="Aktor: Customer, Admin.`n`n- [ ] Identifikasi aktor & use case`n- [ ] Gambar diagram`n- [ ] Skenario use case (naratif)"
      l="analisis,prioritas"},

    @{t="[Analisis] Activity Diagram - alur sewa"
      b="Alur: pilih katalog -> pesan -> pilih tanggal & durasi -> pembayaran -> upload bukti -> verifikasi admin -> pesanan diproses."
      l="analisis"},

    @{t="[Analisis] Activity Diagram - alur servis"
      b="Alur: pesan servis -> pilih jenis layanan & jadwal -> pembayaran -> verifikasi admin -> servis diproses."
      l="analisis"},

    @{t="[Analisis] ERD / Class Diagram"
      b="Entitas kira-kira: users, sepeda, kategori, penyewaan, servis, pembayaran, pengembalian.`n`n- [ ] Tentukan entitas & atribut`n- [ ] Relasi & kardinalitas`n- [ ] Normalisasi"
      l="analisis,prioritas"},

    @{t="[Analisis] Sequence Diagram - pemesanan"
      b="Minimal alur pemesanan sewa. Tambahan: alur pembayaran & verifikasi admin."
      l="analisis"},

    # ---------- BACKEND MEY (sewa) ----------
    @{t="[BE-Mey] Setup autentikasi (register, login, logout)"
      b="Mockup: Masuk.png, Daftar Akun.png`n`n- [ ] Migration & model User (email, no_hp, password)`n- [ ] Form register + validasi + checkbox syarat`n- [ ] Form login + lupa password`n- [ ] Middleware auth`n- [ ] Navbar beda saat login vs belum login"
      l="BE-mey,customer,prioritas"},

    @{t="[BE-Mey] Halaman Profil customer"
      b="Mockup: Profil.png`n`n- [ ] Tampil data diri`n- [ ] Edit profil`n- [ ] Ganti password"
      l="BE-mey,customer"},

    @{t="[BE-Mey] CRUD Unit Sepeda + kategori"
      b="Mockup: Katalog.png, Katalog Premium.png, Katalog Standar.png`n`n- [ ] Migration sepeda (kode CB-xxx, nama, kategori, harga per jam/3jam/6jam, status, gambar)`n- [ ] Seeder 16 unit`n- [ ] Filter Semua / Premium / Standar`n- [ ] Badge status Tersedia / Habis`n- [ ] Pagination`n- [ ] Versi login & belum login"
      l="BE-mey,customer,prioritas"},

    @{t="[BE-Mey] Modul Pemesanan Sewa"
      b="Mockup: Pesan (Sewa Sepeda FIX).png, Pesan (Sewa Tanggal).png, Pesan (Sewa waktu).png`n`n- [ ] Pilih unit sepeda`n- [ ] Pilih tanggal (kalender)`n- [ ] Pilih durasi (1 jam / 3 jam / 6 jam)`n- [ ] Hitung total otomatis`n- [ ] Cek ketersediaan unit"
      l="BE-mey,customer"},

    @{t="[BE-Mey] Modul Pembayaran Sewa"
      b="Mockup: Pembayaran Sewa.png, Pembayaran Berhasil.png`n`n- [ ] Ringkasan pesanan`n- [ ] QRIS + instruksi pembayaran`n- [ ] Countdown timer 24 jam`n- [ ] Upload bukti (JPG/PNG/PDF maks 5MB)`n- [ ] Halaman pembayaran berhasil"
      l="BE-mey,customer"},

    @{t="[BE-Mey] Halaman Riwayat & Status Pesanan"
      b="Mockup: Riwayat.png, Status Pesanan.png`n`n- [ ] List riwayat + badge Proses/Selesai`n- [ ] Detail/rincian pesanan`n- [ ] Nomor transaksi (format CB-YYYYMMDD-001)`n- [ ] Cetak nota`n- [ ] Review setelah selesai"
      l="BE-mey,customer"},

    # ---------- BACKEND KIA (servis + admin) ----------
    @{t="[BE-Kia] Modul Pemesanan Servis"
      b="Mockup: Pesan (Servis Sepeda).png, Pesan serv sepdda.png`n`n- [ ] Migration servis`n- [ ] Form pesan servis`n- [ ] Pilih jenis layanan`n- [ ] Pilih jadwal`n- [ ] Opsi jemput di rumah"
      l="BE-kia,customer,prioritas"},

    @{t="[BE-Kia] Modul Pembayaran Servis"
      b="Mockup: Pembayaran Servis.png`n`n- [ ] Ringkasan servis`n- [ ] QRIS + upload bukti`n- [ ] Terhubung ke status pesanan"
      l="BE-kia,customer"},

    @{t="[BE-Kia] Dashboard Admin"
      b="Mockup: Dashboard Admin.png`n`n- [ ] Layout sidebar admin`n- [ ] Kartu statistik (total unit, sewa aktif, servis masuk, total pendapatan)`n- [ ] Tabel pesanan terbaru`n- [ ] Panel status unit (tersedia/disewa)`n- [ ] Role & middleware admin"
      l="BE-kia,admin,prioritas"},

    @{t="[BE-Kia] Admin - Manajemen Unit"
      b="Mockup: Manajemen Unit.png, Manage Regis Unit baru.png`n`n- [ ] Grid kartu unit + gambar`n- [ ] Tambah unit baru`n- [ ] Edit & hapus unit`n- [ ] Toggle aktif/non-aktif`n- [ ] Search + filter kategori`n- [ ] Upload gambar"
      l="BE-kia,admin"},

    @{t="[BE-Kia] Admin - Pesanan Masuk (Sewa & Servis)"
      b="Mockup: Pesanan Masuk (SEWA).png, Pesanan Masuk (Service).png`n`n- [ ] Tab sewa & servis`n- [ ] Lihat bukti pembayaran`n- [ ] Approve / reject pesanan`n- [ ] Update status pesanan"
      l="BE-kia,admin"},

    @{t="[BE-Kia] Admin - Pengembalian Sepeda"
      b="Mockup: Pengembalian Sepeda.png, Pengembalian Sepeda(cek status).png`n`n- [ ] List sepeda yang lagi disewa`n- [ ] Form pengembalian`n- [ ] Cek kondisi unit`n- [ ] Hitung denda telat`n- [ ] Update status unit jadi tersedia"
      l="BE-kia,admin"},

    @{t="[BE-Kia] Admin - Daftar Pengguna"
      b="Mockup: Daftar Pengguna.png`n`n- [ ] Tabel user`n- [ ] Search`n- [ ] Detail user + riwayat transaksinya"
      l="BE-kia,admin"},

    @{t="[BE-Kia] Admin - Laporan"
      b="Mockup: Laporan.png`n`n- [ ] Laporan pendapatan per periode`n- [ ] Laporan penyewaan`n- [ ] Laporan servis`n- [ ] Export (PDF/Excel)"
      l="BE-kia,admin"},

    # ---------- LAPORAN ----------
    @{t="[Laporan] BAB I - Pendahuluan"
      b="- [ ] Latar belakang`n- [ ] Rumusan masalah`n- [ ] Tujuan`n- [ ] Manfaat`n- [ ] Batasan masalah`n`nPIC: Aeyma & Ayu"
      l="laporan"},

    @{t="[Laporan] BAB II - Landasan Teori"
      b="- [ ] Teori RPL / SDLC`n- [ ] Teori Laravel & MVC`n- [ ] Teori UML`n- [ ] Penelitian terkait`n`nPIC: Aeyma & Ayu"
      l="laporan"},

    @{t="[Laporan] BAB III - Analisis & Perancangan"
      b="Nunggu output dari task analisis.`n`n- [ ] Analisis sistem berjalan`n- [ ] Analisis sistem usulan`n- [ ] Masukin semua diagram UML`n- [ ] Perancangan database (ERD + tabel)`n- [ ] Perancangan antarmuka (mockup)`n`nPIC: Aeyma & Ayu"
      l="laporan"},

    @{t="[Laporan] BAB IV - Implementasi & Pengujian"
      b="- [ ] Screenshot implementasi tiap halaman`n- [ ] Penjelasan fitur`n- [ ] Blackbox testing`n- [ ] Tabel hasil pengujian`n`nPIC: Aeyma & Ayu"
      l="laporan"},

    @{t="[Laporan] BAB V - Penutup + lampiran"
      b="- [ ] Kesimpulan`n- [ ] Saran`n- [ ] Daftar pustaka`n- [ ] Lampiran mockup`n- [ ] Lampiran source code / link repo`n`nPIC: Aeyma & Ayu"
      l="laporan"},

    # ---------- SETUP ----------
    @{t="[Setup] Konfigurasi database & environment"
      b="- [ ] Setup .env (DB, APP_NAME)`n- [ ] Bikin database culture_bike`n- [ ] Sepakati konvensi commit & alur branch`n- [ ] README isi cara install"
      l="prioritas"}
)

$urls = @()
$i = 0
foreach ($iss in $issues) {
    $i++
    $out = & $gh issue create --repo $repo --title $iss.t --body $iss.b --label $iss.l 2>&1
    $url = ($out | Select-String -Pattern "https://github.com/\S+" | ForEach-Object { $_.Matches[0].Value })
    if ($url) {
        $urls += $url
        Write-Host "  [$i/$($issues.Count)] OK  $($iss.t)" -ForegroundColor DarkGray
    } else {
        Write-Host "  [$i/$($issues.Count)] GAGAL  $($iss.t)" -ForegroundColor Red
        Write-Host "        $out" -ForegroundColor Red
    }
    Start-Sleep -Milliseconds 700
}

Write-Host ""
Write-Host "Total issue kebikin: $($urls.Count) / $($issues.Count)" -ForegroundColor Green

Write-Host ""
Write-Host "=== [3/4] Bikin project board ===" -ForegroundColor Cyan

$projOut = & $gh project create --owner $owner --title "UAS RPL - Culture Bike" --format json 2>&1
try {
    $proj = $projOut | ConvertFrom-Json
    $projNum = $proj.number
    Write-Host "  Board kebikin: #$projNum -> $($proj.url)" -ForegroundColor Green
} catch {
    Write-Host "  GAGAL bikin board:" -ForegroundColor Red
    Write-Host "  $projOut" -ForegroundColor Red
    Write-Host "  Issue tetep aman kok, board bisa dibikin manual." -ForegroundColor Yellow
    exit
}

Write-Host ""
Write-Host "=== [4/4] Masukin issue ke board ===" -ForegroundColor Cyan

$j = 0
foreach ($u in $urls) {
    $j++
    & $gh project item-add $projNum --owner $owner --url $u 2>&1 | Out-Null
    Write-Host "  [$j/$($urls.Count)] $u" -ForegroundColor DarkGray
    Start-Sleep -Milliseconds 500
}

Write-Host ""
Write-Host "=== SELESAI ===" -ForegroundColor Green
Write-Host "Board  : $($proj.url)"
Write-Host "Issues : https://github.com/$repo/issues"
Write-Host ""
Write-Host "Sisanya manual dikit di web:" -ForegroundColor Yellow
Write-Host "  1. Buka board -> rename kolom jadi: Backlog / Analisis / In Progress / Review / Done"
Write-Host "  2. Assign issue ke orangnya masing-masing"
Write-Host "  3. Invite Kia, Aeyma, Ayu jadi collaborator di Settings -> Collaborators"
Write-Host ""
