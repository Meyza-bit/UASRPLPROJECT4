<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pesan Servis Sepeda</title>
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
            display: flex;
            justify-content: space-between;
            align-items: center;
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
            background: #fdf1d9;
            border-radius: 12px;
            padding: 30px 40px;
        }

        h1 {
            color: #7a1f1f;
            font-size: 24px;
            margin-bottom: 5px;
        }

        .tabs {
            display: inline-flex;
            background: #f3e6c9;
            border-radius: 20px;
            padding: 4px;
            margin-bottom: 25px;
        }

        .tabs span {
            padding: 8px 20px;
            border-radius: 16px;
            font-size: 14px;
            color: #9a8a6a;
        }

        .tabs span.active {
            background: #e8a33d;
            color: #fff;
            font-weight: bold;
        }

        .layout {
            display: flex;
            gap: 30px;
            align-items: flex-start;
        }

        .layanan-section {
            flex: 2;
        }

        .layanan-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .layanan-card {
            background: #fff;
            border-radius: 10px;
            padding: 18px;
            border: 1px solid #eee2c8;
        }

        .layanan-card strong {
            font-size: 16px;
            color: #3a2a1a;
        }

        .layanan-card .harga {
            color: #7a1f1f;
            font-weight: bold;
            font-size: 18px;
            margin: 8px 0 12px;
        }

        .btn-tambah {
            width: 100%;
            padding: 10px;
            background: #f3e6c9;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-tambah.selected {
            background: #e8a33d;
            color: #fff;
        }

        .keranjang-box {
            flex: 1;
            background: #fff;
            border-radius: 10px;
            padding: 20px;
        }

        .keranjang-box h3 {
            margin-top: 0;
            color: #7a1f1f;
        }

        .keranjang-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #f0f0f0;
            font-size: 14px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            font-size: 16px;
            margin-top: 15px;
            padding-top: 10px;
            border-top: 2px solid #eee2c8;
        }

        .total-row .nilai {
            color: #7a1f1f;
        }

        .form-group {
            margin-top: 18px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            color: #6a5a4a;
            margin-bottom: 5px;
        }

        .form-group input[type="date"],
        .form-group input[type="time"],
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #e0d5b8;
            border-radius: 8px;
            background: #fdfbf5;
        }

        .btn-submit {
            width: 100%;
            margin-top: 20px;
            padding: 14px;
            background: #2e5339;
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

        .alert-error ul {
            margin: 0;
            padding-left: 18px;
        }

        .empty-cart {
            color: #9a8a6a;
            font-style: italic;
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="navbar">
    <div class="brand">🚲 Culture Bike</div>
</div>

<div class="container">

    <h1>Pesan Sewa & Servis Sepeda</h1>

    <div class="tabs">
        <span>Sewa Sepeda</span>
        <span class="active">Servis Sepeda</span>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('servis.store') }}" method="POST" id="formServis">
        @csrf

        <div class="layout">

            {{-- KIRI: Daftar layanan --}}
            <div class="layanan-section">
                <h3>Daftar Layanan Servis</h3>

                @php
                    $daftarLayanan = [
                        ['nama' => 'Tune-Up Lengkap', 'deskripsi' => 'Pemeriksaan menyeluruh, setel rem & gir untuk performa optimal.', 'harga' => 50000],
                        ['nama' => 'Ganti Komponen Baru', 'deskripsi' => 'Penggantian suku cadang berkualitas tinggi sesuai kebutuhan.', 'harga' => 15000],
                        ['nama' => 'Ganti Rantai', 'deskripsi' => 'Pemasangan rantai baru untuk kelancaran gowes Anda.', 'harga' => 35000],
                        ['nama' => 'Tambal / Ganti Ban', 'deskripsi' => 'Solusi cepat untuk ban bocor atau aus di perjalanan.', 'harga' => 10000],
                    ];
                @endphp

                <div class="layanan-grid">
                    @foreach ($daftarLayanan as $i => $layanan)
                        <div class="layanan-card">
                            <strong>{{ $layanan['nama'] }}</strong>
                            <p style="font-size:13px; color:#7a6a5a;">{{ $layanan['deskripsi'] }}</p>
                            <div class="harga">Rp {{ number_format($layanan['harga'], 0, ',', '.') }}</div>
                            <label style="display:none;">
                                <input
                                    type="checkbox"
                                    name="layanan_checkbox[]"
                                    value="{{ $i }}"
                                    class="checkbox-layanan"
                                    data-nama="{{ $layanan['nama'] }}"
                                    data-harga="{{ $layanan['harga'] }}"
                                >
                            </label>
                            <button type="button" class="btn-tambah" data-index="{{ $i }}">
                                🛒 Tambah ke Keranjang
                            </button>
                        </div>
                    @endforeach
                </div>