<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pembayaran Servis</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', Arial, sans-serif;
        }

        body {
            background-color: #fdf6e3;
            margin: 0;
            padding: 0;
            color: #3a2a1a;
        }

        .navbar {
            background: #fff;
            padding: 15px 40px;
            border-bottom: 1px solid #eee;
        }

        .navbar .brand {
            color: #7a1f1f;
            font-weight: bold;
            font-size: 20px;
        }

        .container {
            max-width: 1000px;
            margin: 30px auto;
            padding: 0 20px;
        }

        h1 {
            color: #7a1f1f;
            font-size: 26px;
        }

        .layout {
            display: flex;
            gap: 25px;
            align-items: flex-start;
        }

        .card {
            background: #fff;
            border-radius: 12px;
            padding: 25px;
        }

        .ringkasan {
            flex: 1;
        }

        .ringkasan h3 {
            color: #7a1f1f;
            margin-top: 0;
        }

        .layanan-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            font-size: 18px;
            margin-top: 15px;
            padding-top: 12px;
            border-top: 2px solid #eee2c8;
            color: #7a1f1f;
        }

        .subtotal-row {
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
            font-size: 14px;
        }

        .pembayaran-box {
            flex: 1;
        }

        .qris-wrapper {
            text-align: center;
        }

        .qris-wrapper img {
            width: 220px;
            height: 220px;
            border: 1px solid #eee2c8;
        }

        .timer {
            background: #fbdcdc;
            color: #7a1f1f;
            padding: 10px 15px;
            border-radius: 8px;
            text-align: center;
            margin: 15px 0;
            font-size: 14px;
        }

        .upload-box {
            border: 2px dashed #e0d5b8;
            border-radius: 10px;
            padding: 30px;
            text-align: center;
            background: #fdf1d9;
            cursor: pointer;
        }

        .upload-box p {
            margin: 5px 0;
        }

        .instruksi {
            margin-top: 20px;
            font-size: 14px;
        }

        .instruksi ol {
            padding-left: 20px;
        }

        .btn-submit {
            width: 100%;
            margin-top: 20px;
            padding: 14px;
            background: #7a1f1f;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
        }

        .alert {
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>

<div class="navbar">
    <div class="brand">🚲 Culture Bike</div>
</div>

<div class="container">
    <h1>Pembayaran</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-error">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="layout">

        {{-- KIRI: Ringkasan Pesanan --}}
        <div class="card ringkasan">
            <h3>Ringkasan Pesanan</h3>

            @foreach ($pesanan->detail as $item)
                <div class="layanan-item">
                    <span>🔧 {{ $item->jenis_layanan }}</span>
                    <span>Rp {{ number_format($item->harga_layanan, 0, ',', '.') }}</span>
                </div>
            @endforeach

            <div class="subtotal-row">
                <span>Subtotal Servis</span>
                <span>Rp {{ number_format($pesanan->detail->sum('harga_layanan'), 0, ',', '.') }}</span>
            </div>
            <div class="subtotal-row">
                <span>Biaya Admin</span>
                <span>Rp {{ number_format($pesanan->biaya_admin, 0, ',', '.') }}</span>
            </div>

            <div class="total-row">
                <span>Total Pembayaran</span>
                <span>Rp {{ number_format($pesanan->total_pembayaran, 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- KANAN: Pembayaran --}}
        <div class="card pembayaran-box">
            <h3>Pembayaran</h3>

            <div class="qris-wrapper">
                {{-- Placeholder QRIS, nanti bisa diganti gambar QRIS asli toko --}}
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=CultureBike-Pesanan-{{ $pesanan->id }}" alt="QRIS">
                <p style="font-size:13px; color:#7a6a5a;">Scan QRIS untuk pembayaran instan</p>
            </div>

            <div class="timer">⏱️ Selesaikan pembayaran dalam 23:59:59</div>

            <form action="{{ route('pembayaran.servis.store', $pesanan->id) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <label style="font-size:14px; font-weight:bold;">Upload Bukti Pembayaran</label>
                <div class="upload-box" onclick="document.getElementById('inputBukti').click()">
                    <div id="fileLabel">
                        📤<br>
                        <strong>Klik atau pilih file</strong><br>
                        <span style="font-size:12px; color:#9a8a6a;">Mendukung JPG, PNG, PDF (Maks. 5MB)</span>
                    </div>
                    <input type="file" id="inputBukti" name="bukti_bayar" accept=".jpg,.jpeg,.png,.pdf" style="display:none;" required>
                </div>

                <div class="instruksi">
                    <strong>Instruksi Pembayaran</strong>
                    <ol>
                        <li>Scan QRIS atau transfer bank.</li>
                        <li>Upload bukti pembayaran pada area di atas.</li>
                        <li>Admin akan memverifikasi pembayaran Anda.</li>
                        <li>Pesanan diproses setelah pembayaran dikonfirmasi.</li>
                    </ol>
                </div>

                <button type="submit" class="btn-submit">Konfirmasi Pembayaran</button>
            </form>
        </div>

    </div>
</div>

<script>
    const inputBukti = document.getElementById('inputBukti');
    const fileLabel = document.getElementById('fileLabel');

    inputBukti.addEventListener('change', function () {
        if (this.files.length > 0) {
            fileLabel.innerHTML = `✅<br><strong>${this.files[0].name}</strong><br><span style="font-size:12px; color:#9a8a6a;">Klik untuk ganti file</span>`;
        }
    });
</script>

</body>
</html>