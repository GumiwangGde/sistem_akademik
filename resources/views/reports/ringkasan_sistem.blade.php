<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Ringkasan Sistem</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            padding: 20px 0;
            border-bottom: 2px solid #1e40af;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            color: #1e40af;
        }
        .header p {
            margin: 5px 0;
            color: #4b5563;
        }
        .section {
            margin-top: 20px;
        }
        .section h2 {
            font-size: 20px;
            color: #1e40af;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 5px;
        }
        .section p {
            font-size: 14px;
            line-height: 1.6;
        }
        .metrics {
            margin-top: 10px;
        }
        .metrics ul {
            list-style: none;
            padding: 0;
        }
        .metrics li {
            margin-bottom: 10px;
            font-size: 14px;
        }
        .metrics li strong {
            color: #1e40af;
        }
        .metrics .sub-list {
            margin-left: 20px;
            list-style-type: disc;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 12px;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>Laporan Ringkasan Sistem</h1>
            <p>Sistem Manajemen Universitas</p>
            <p>Dibuat pada {{ $tanggal }}</p>
            <p>Disusun oleh Admin</p>
        </div>

        <!-- Gambaran Umum -->
        <div class="section">
            <h2>Gambaran Umum</h2>
            <p>
                Laporan ini memberikan ringkasan metrik utama dari Sistem Manajemen Universitas per {{ $tanggal }}. 
                Data mencakup jumlah pengguna (terkategori sebagai admin, dosen, dan mahasiswa), dosen, mahasiswa, mata kuliah, kelas, dan ruangan.
            </p>
        </div>

        <!-- Metrik Sistem -->
        <div class="section">
            <h2>Metrik Sistem</h2>
            <div class="metrics">
                <ul>
                    <li>
                        <strong>Total Pengguna:</strong> {{ $total_pengguna }}<br>
                        Pengguna terdaftar di sistem, meliputi:
                        <ul class="sub-list">
                            <li>Admin: {{ $total_admin }}</li>
                            <li>Dosen (berdasarkan email): {{ $total_dosen_user }}</li>
                            <li>Mahasiswa (berdasarkan email): {{ $total_mahasiswa_user }}</li>
                        </ul>
                    </li>
                    <li><strong>Total Dosen:</strong> {{ $total_dosen }}<br>Dosen terdaftar di sistem.</li>
                    <li><strong>Total Mahasiswa:</strong> {{ $total_mahasiswa }}<br>Mahasiswa yang terdaftar di sistem.</li>
                    <li><strong>Total Mata Kuliah:</strong> {{ $total_matakuliah }}<br>Mata kuliah yang ditawarkan di sistem.</li>
                    <li><strong>Total Kelas:</strong> {{ $total_kelas }}<br>Kelas yang aktif atau tidak aktif.</li>
                    <li><strong>Total Ruangan:</strong> {{ $total_ruang }}<br>Ruangan yang tersedia untuk penjadwalan.</li>
                </ul>
            </div>
        </div>

        <!-- Ringkasan -->
        <div class="section">
            <h2>Ringkasan</h2>
            <p>
                Sistem saat ini mengelola total {{ $total_pengguna }} pengguna, dengan {{ $total_admin }} admin, 
                {{ $total_dosen_user }} dosen (berdasarkan email), dan {{ $total_mahasiswa_user }} mahasiswa (berdasarkan email). 
                Terdapat {{ $total_dosen }} dosen dan {{ $total_mahasiswa }} mahasiswa, dengan {{ $total_matakuliah }} mata kuliah 
                yang didistribusikan di {{ $total_kelas }} kelas, menggunakan {{ $total_ruang }} ruangan. 
                Laporan ini berfungsi sebagai gambaran untuk pengawasan dan perencanaan administratif.
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Laporan ini dihasilkan oleh Sistem Manajemen Universitas</p>
        </div>
    </div>
</body>
</html>