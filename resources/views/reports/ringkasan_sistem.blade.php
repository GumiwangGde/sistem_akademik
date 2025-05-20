<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Ringkasan Sistem</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.3/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9fafb;
            color: #333;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px;
        }
        .header {
            text-align: center;
            padding: 40px 0;
            border-bottom: 3px solid #1e40af;
            margin-bottom: 40px;
        }
        .header h1 {
            font-size: 36px;
            font-weight: 700;
            color: #1e40af;
            margin-bottom: 10px;
        }
        .header p {
            color: #6b7280;
            font-size: 18px;
        }
        .section {
            margin-top: 40px;
        }
        .section h2 {
            font-size: 24px;
            color: #1e40af;
            font-weight: 600;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 8px;
            margin-bottom: 20px;
        }
        .section p {
            font-size: 16px;
            line-height: 1.8;
            color: #4b5563;
        }
        .metrics {
            margin-top: 30px;
        }
        .metrics ul {
            list-style: none;
            padding: 0;
        }
        .metrics li {
            margin-bottom: 15px;
            font-size: 16px;
            color: #4b5563;
        }
        .metrics li strong {
            color: #1e40af;
            font-weight: 600;
        }
        .metrics .sub-list {
            margin-left: 20px;
            list-style-type: disc;
        }
        .footer {
            text-align: center;
            margin-top: 60px;
            font-size: 14px;
            color: #6b7280;
        }
        .card {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .card h3 {
            font-size: 22px;
            font-weight: 600;
            color: #1e40af;
        }
        .card p {
            font-size: 16px;
            color: #4b5563;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>Laporan Ringkasan Sistem</h1>
            <p>Sistem Informasi Akademik PENS</p>
            <p class="text-gray-500">{{ $tanggal }} | Disusun oleh Admin</p>
        </div>

        <!-- Gambaran Umum -->
        <div class="section">
            <h2>Gambaran Umum</h2>
            <p>
                Laporan ini memberikan ringkasan metrik utama dari SIAKAD per <strong>{{ $tanggal }}</strong>.
                Data mencakup informasi tentang jumlah pengguna (terkategori sebagai admin, dosen, dan mahasiswa), dosen, mahasiswa, mata kuliah, kelas, dan ruangan yang ada di sistem.
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
                Sistem saat ini mengelola total <strong>{{ $total_pengguna }}</strong> pengguna, dengan <strong>{{ $total_admin }}</strong> admin, 
                <strong>{{ $total_dosen_user }}</strong> dosen (berdasarkan email), dan <strong>{{ $total_mahasiswa_user }}</strong> mahasiswa (berdasarkan email). 
                Terdapat <strong>{{ $total_dosen }}</strong> dosen dan <strong>{{ $total_mahasiswa }}</strong> mahasiswa, dengan <strong>{{ $total_matakuliah }}</strong> mata kuliah 
                yang didistribusikan di <strong>{{ $total_kelas }}</strong> kelas, menggunakan <strong>{{ $total_ruang }}</strong> ruangan. 
                Laporan ini berfungsi sebagai gambaran untuk pengawasan dan perencanaan administratif yang lebih baik.
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Laporan ini dihasilkan oleh Sistem Informasi Akademik PENS</p>
        </div>
    </div>

    <script>
        // Data for User Distribution
        const userDistributionData = {
            labels: ['Admin', 'Dosen', 'Mahasiswa'],
            datasets: [{
                label: 'Distribusi Pengguna',
                data: [
                    {{ $total_admin }},
                    {{ $total_dosen_user }},
                    {{ $total_mahasiswa_user }}
                ],
                backgroundColor: ['#ef4444', '#3b82f6', '#10b981'],
                borderColor: '#fff',
                borderWidth: 1
            }]
        };

        // Data for System Overview
        const systemOverviewData = {
            labels: ['Dosen', 'Mahasiswa', 'Mata Kuliah', 'Kelas', 'Ruangan'],
            datasets: [{
                label: 'Gambaran Umum Sistem',
                data: [
                    {{ $total_dosen }},
                    {{ $total_mahasiswa }},
                    {{ $total_matakuliah }},
                    {{ $total_kelas }},
                    {{ $total_ruang }}
                ],
                backgroundColor: '#3b82f6',
                borderColor: '#fff',
                borderWidth: 1
            }]
        };

        // User Distribution Pie Chart
        const ctx1 = document.getElementById('userDistributionChart').getContext('2d');
        const userDistributionChart = new Chart(ctx1, {
            type: 'pie',
            data: userDistributionData,
            options: {
                responsive: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw;
                            }
                        }
                    }
                }
            }
        });

        // System Overview Bar Chart
        const ctx2 = document.getElementById('systemOverviewChart').getContext('2d');
        const systemOverviewChart = new Chart(ctx2, {
            type: 'bar',
            data: systemOverviewData,
            options: {
                responsive: true,
                scales: {
                    x: { beginAtZero: true },
                    y: { beginAtZero: true }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw;
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
