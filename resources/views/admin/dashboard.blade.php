@extends('admin.templates.sidebar')

@section('contain')
    <!-- Awal Dashboard -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">

                <h4>Dashboard, {{ auth('administrator')->user()->username }} [{{ auth('administrator')->user()->role }}]
                </h4>


            </div>
        </div>
        <div class="row text-white justify-content-between">
            <div class="col-md-12 mb-3">
                <div class="card" style="height: min-content">
                    <div class="card-body">
                        <h5 class="card-title mb-4" style="color: black">Perjalanan Dinas</h5>

                        <div class="row row row-cols-1 row-cols-md-3 g-4">
                            <div class="col-md-6">
                                <div class="card" style=" background: #082A99">
                                    <div class="row g-0">
                                        <div class="col-md-12">
                                            @if (auth('administrator')->user()->role == 'Master')
                                                <a href="{{ url('/monitoring') }}"
                                                    class="accordion-button collapsed text-decoration-none custom-button remove-button py-3">
                                                    <div class="col-md-2">
                                                        <img src="{{ asset('/assets/images/file.png') }}"
                                                            class="img-fluid rounded-start" alt="..." width="80">
                                                    </div>
                                                    <h1 class="card-title">{{ $perjadinMaster }}</h1>
                                                    <p class="card-text">Semua Pengajuan - Perjalanan Dinas Langsung</p>
                                                </a>
                                            @elseif (auth('administrator')->user()->role == 'BMN')
                                                <a href="{{ url('/perjadin-mobilitas/' . 'pengajuan') }}"
                                                    class="nav-link px-1 sidebar-link py-1 text-white" aria-current="true">
                                                    <div class="col-md-2">
                                                        <img src="{{ asset('/assets/images/file.png') }}"
                                                            class="img-fluid rounded-start" alt="..." width="80">
                                                    </div>
                                                    <h1 class="card-title">{{ $perjadinBMN }}</h1>
                                                    <p class="card-text">Pengajuan BMN - Perjalanan Dinas Langsung</p>
                                                </a>
                                            @elseif (auth('administrator')->user()->role == 'HKT')
                                                <a href="{{ url('/perjadin-HKT/' . 'pengajuan') }}"
                                                    class="nav-link px-1 sidebar-link py-1 text-white" aria-current="true">
                                                    <div class="col-md-2">
                                                        <img src="{{ asset('/assets/images/file.png') }}"
                                                            class="img-fluid rounded-start" alt="..." width="80">
                                                    </div>
                                                    <h1 class="card-title">{{ $perjadinHKT }}</h1>
                                                    <p class="card-text">Pengajuan HKT - Perjalanan Dinas Langsung</p>
                                                </a>
                                            @elseif (auth('administrator')->user()->role == 'Keuangan')
                                                <a href="{{ url('/perjadin-keuangan/' . 'verifikasi-2') }}"
                                                    class="nav-link px-1 sidebar-link py-1 text-white" aria-current="true">
                                                    <div class="col-md-2">
                                                        <img src="{{ asset('/assets/images/file.png') }}"
                                                            class="img-fluid rounded-start" alt="..." width="80">
                                                    </div>
                                                    <h1 class="card-title">{{ $perjadinKeu }}</h1>
                                                    <p class="card-text">Pengajuan Keuangan - Perjalanan Dinas Langsung</p>
                                                </a>
                                            @elseif (auth('administrator')->user()->role == 'Bendahara')
                                                <a href="{{ url('/perjadin-bendahara/' . 'approval-1') }}"
                                                    class="nav-link px-1 sidebar-link py-1 text-white" aria-current="true">
                                                    <div class="col-md-2">
                                                        <img src="{{ asset('/assets/images/file.png') }}"
                                                            class="img-fluid rounded-start" alt="..." width="80">
                                                    </div>
                                                    <h1 class="card-title">{{ $perjadinBend }}</h1>
                                                    <p class="card-text">Pengajuan Bendahara - Perjalanan Dinas Langsung</p>
                                                </a>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card" style=" background: #082A99">
                                    <div class="row g-0">

                                        <div class="col-md-12">

                                            @if (auth('administrator')->user()->role == 'Master')
                                                <a href="{{ url('/monitoring?tipe=kegiatan') }}"
                                                    class="accordion-button collapsed text-decoration-none custom-button remove-button py-3">

                                                    <div class="col-md-2">
                                                        <img src="{{ asset('/assets/images/file.png') }}"
                                                            class="img-fluid rounded-start" alt="..." width="80">
                                                    </div>
                                                    <h1 class="card-title">{{ $kegiatanMaster }}</h1>
                                                    <p class="card-text">Semua Pengajuan - Perjalanan Dinas Kegiatan</p>
                                                </a>
                                            @elseif (auth('administrator')->user()->role == 'BMN')
                                                <a href="{{ url('/kegiatan-mobilitas/' . 'pengajuan') }}"
                                                    class="nav-link px-1 sidebar-link py-1 text-white" aria-current="true">
                                                    <div class="col-md-2">
                                                        <img src="{{ asset('/assets/images/file.png') }}"
                                                            class="img-fluid rounded-start" alt="..." width="80">
                                                    </div>
                                                    <h1 class="card-title">{{ $kegiatanBMN }}</h1>
                                                    <p class="card-text">Pengajuan BMN - Perjalanan Dinas Kegiatan</p>
                                                </a>
                                            @elseif (auth('administrator')->user()->role == 'HKT')
                                                <a href="{{ url('/kegiatan-HKT/' . 'pengajuan') }}"
                                                    class="nav-link px-1 sidebar-link py-1 text-white" aria-current="true">
                                                    <div class="col-md-2">
                                                        <img src="{{ asset('/assets/images/file.png') }}"
                                                            class="img-fluid rounded-start" alt="..." width="80">
                                                    </div>
                                                    <h1 class="card-title">{{ $kegiatanHKT }}</h1>
                                                    <p class="card-text">Pengajuan HKT - Perjalanan Dinas Kegiatan</p>
                                                </a>
                                            @elseif (auth('administrator')->user()->role == 'Keuangan')
                                                <a href="{{ url('/kegiatan-keuangan/' . 'verifikasi-2') }}"
                                                    class="nav-link px-1 sidebar-link py-1 text-white" aria-current="true">
                                                    <div class="col-md-2">
                                                        <img src="{{ asset('/assets/images/file.png') }}"
                                                            class="img-fluid rounded-start" alt="..."
                                                            width="80">
                                                    </div>
                                                    <h1 class="card-title">{{ $kegiatanKeu }}</h1>
                                                    <p class="card-text">Pengajuan Keuangan - Perjalanan Dinas Kegiatan</p>
                                                </a>
                                            @elseif (auth('administrator')->user()->role == 'Bendahara')
                                                <a href="{{ url('/kegiatan-bendahara/' . 'approval-1') }}"
                                                    class="nav-link px-1 sidebar-link py-1 text-white"
                                                    aria-current="true">
                                                    <div class="col-md-2">
                                                        <img src="{{ asset('/assets/images/file.png') }}"
                                                            class="img-fluid rounded-start" alt="..."
                                                            width="80">
                                                    </div>
                                                    <h1 class="card-title">{{ $kegiatanBend }}</h1>
                                                    <p class="card-text">Pengajuan Bendahara - Perjalanan Dinas Kegiatan
                                                    </p>
                                                </a>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card-body">

                                    <canvas id="combinedChart" width="100" height="50"></canvas>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card-body">

                                    <canvas id="statusChart"></canvas>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card-body">

                                    <canvas id="kegiatanChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <!-- Judul Card Utama -->
                        <h5 class="card-title mb-4">Barang Milik Negara (BMN)</h5>

                        <!-- Time Series -->
                        <div class="mb-4">
                            <canvas id="timeSeriesChartBMN" style="max-height: 300px;"></canvas>
                        </div>

                        <!-- Card KPI dan Chart -->
                        <div class="row">
                            <!-- Kolom Kiri: Pemeliharaan -->
                            <div class="col-md-6 border-end">
                                <h6 class="mb-3">Pemeliharaan</h6>
                                <div class="row text-white">
                                    <div class="col-3 mb-3">
                                        <div class="card bg-primary">
                                            <div class="card-body p-2 text-center">
                                                <h6>Pengajuan</h6>
                                                <h4>{{ $pemeliharaanKpi['pemeliharaanPengajuan'] }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3 mb-3">
                                        <div class="card bg-warning">
                                            <div class="card-body p-2 text-center">
                                                <h6>Berlangsung</h6>
                                                <h4>{{ $pemeliharaanKpi['pemeliharaanBerlangsung'] }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3 mb-3">
                                        <div class="card bg-danger">
                                            <div class="card-body p-2 text-center">
                                                <h6>Ditolak</h6>
                                                <h4>{{ $pemeliharaanKpi['pemeliharaanDitolak'] }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3 mb-3">
                                        <div class="card bg-success">
                                            <div class="card-body p-2 text-center">
                                                <h6>Selesai</h6>
                                                <h4>{{ $pemeliharaanKpi['pemeliharaanSelesai'] }}</h4>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 d-flex align-items-center justify-content-center mt-3">
                                        <canvas id="pemeliharaanPieChart" style="max-height: 250px;"></canvas>
                                    </div>
                                </div>
                            </div>

                            <!-- Kolom Kanan: Sewa -->
                            <div class="col-md-6 ps-4">
                                <h6 class="mb-3">Sewa</h6>
                                <div class="row text-white">
                                    <div class="col-3 mb-3">
                                        <div class="card bg-primary">
                                            <div class="card-body p-2 text-center">
                                                <h6>Pengajuan</h6>
                                                <h4>{{ $pemesananKpi['pengajuan'] }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3 mb-3">
                                        <div class="card bg-warning">
                                            <div class="card-body p-2 text-center">
                                                <h6>Berlangsung</h6>
                                                <h4>{{ $pemesananKpi['berlangsung'] }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3 mb-3">
                                        <div class="card bg-danger">
                                            <div class="card-body p-2 text-center">
                                                <h6>Ditolak/Dibatalkan</h6>
                                                <h4>{{ $pemesananKpi['ditolak/dibatalkan'] }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3 mb-3">
                                        <div class="card bg-success">
                                            <div class="card-body p-2 text-center">
                                                <h6>Selesai</h6>
                                                <h4>{{ $pemesananKpi['selesai'] }}</h4>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 d-flex align-items-center justify-content-center mt-3">
                                        <canvas id="pemesananBarChart" style="max-height: 250px;"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- row KPI -->
                    </div> <!-- card-body -->
                </div> <!-- card -->
            </div> <!-- col-md-12 -->
        </div> <!-- row -->


    </div>
    <!-- Akhir Dashboard -->

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
    <script>
        const ctxTimeSeriesBMN = document.getElementById('timeSeriesChartBMN').getContext('2d');
        const labels = {!! json_encode($timeSeriesBMN->keys()) !!};
        const data = {
            labels: labels,
            datasets: [{
                    label: 'Pemeliharaan',
                    data: {!! json_encode($timeSeriesBMN->pluck('pemeliharaan')) !!},
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    tension: 0.3
                },
                {
                    label: 'Sewa',
                    data: {!! json_encode($timeSeriesBMN->pluck('pemesanan')) !!},
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    tension: 0.3
                }
            ]
        };
        new Chart(ctxTimeSeriesBMN, {
            type: 'line',
            data: data,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top'
                    },
                    title: {
                        display: true,
                        text: 'Traffic Pengajuan Pemeliharaan dan Sewa BMN per Bulan',
                        font: {
                            size: 15,
                            weight: 'bold'
                        },
                        padding: {
                            top: 10,
                            bottom: 30
                        }
                    },
                },

                scales: {
                    y: {
                        beginAtZero: true
                    },
                }
            }
        });
        const ctxPemesananBar = document.getElementById('pemesananBarChart').getContext('2d');
        const pemesananBarData = @json($pemesananBarData);

        new Chart(ctxPemesananBar, {
            type: 'bar',
            data: {
                labels: Object.keys(pemesananBarData),
                datasets: [{
                    label: 'Jumlah Tersewa (per Malam)',
                    data: Object.values(pemesananBarData),
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                plugins: {
                    title: {
                        display: true,
                        text: 'Jumlah Malam Tersewa per Tarif',
                        font: {
                            size: 15,
                            weight: 'bold'
                        },
                        padding: {
                            top: 10,
                            bottom: 30
                        }
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Tarif Kamar (Rp)'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Jumlah Malam'
                        }
                    }
                }
            }
        });
        // === Pemeliharaan Pie Chart ===
        const ctxPemeliharaan = document.getElementById('pemeliharaanPieChart').getContext('2d');
        const pemeliharaanPieData = @json($pemeliharaanPieData);

        new Chart(ctxPemeliharaan, {
            type: 'pie',
            data: {
                labels: Object.keys(pemeliharaanPieData),
                datasets: [{
                    data: Object.values(pemeliharaanPieData),
                    backgroundColor: [
                        '#007bff', '#ffc107', '#dc3545', '#28a745', '#6f42c1', '#20c997'
                    ]
                }]
            },
            options: {
                plugins: {
                    title: {
                        display: true,
                        text: 'Kategori BMN',
                        font: {
                            size: 15,
                            weight: 'bold'
                        },
                        padding: {
                            top: 10,
                            bottom: 30
                        }
                    }
                }
            }
        });


        // === Kegiatan Pie Chart ===
        const ctxKegiatan = document.getElementById('kegiatanChart').getContext('2d');
        const kegiatanData = [
            {{ $countPengajuanKegiatan }},
            {{ $countProsesKegiatan }},
            {{ $countDitolakKegiatan }},
            {{ $countSelesaiKegiatan }},
        ];

        new Chart(ctxKegiatan, {
            type: 'pie',
            data: {
                labels: ['Pengajuan', 'Proses', 'Ditolak', 'Selesai'],
                datasets: [{
                    label: 'Status Pengajuan Kegiatan',
                    data: kegiatanData.every(v => v === 0) ? [1] : kegiatanData,
                    backgroundColor: kegiatanData.every(v => v === 0) ? ['#d3d3d3'] : ['#36a2eb', '#ffcd56',
                        '#ff6384', '#4bc0c0'
                    ],
                    hoverOffset: 4
                }]
            },
            options: {
                plugins: {
                    title: {
                        display: true,
                        text: 'Status Pengajuan Kegiatan',
                        font: {
                            size: 15,
                            weight: 'bold'
                        },
                        padding: {
                            top: 10,
                            bottom: 30
                        }
                    },
                    legend: {
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: t => kegiatanData.every(v => v === 0) ?
                                'Kosong' : t.label + ': ' + t.raw + ' Kegiatan'
                        }
                    }
                }
            }
        });

        // === Combined Line Chart ===
        const ctxCombined = document.getElementById('combinedChart').getContext('2d');
        new Chart(ctxCombined, {
            type: 'line',
            data: {
                labels: [
                    @foreach ($monthsOrder as $month)
                        '{{ $month }}',
                    @endforeach
                ],
                datasets: [{
                    label: 'Perjadin',
                    data: [
                        @foreach ($monthsOrder as $month)
                            {{ $combinedData->has($month) ? $combinedData[$month]['perjadin'] : 0 }},
                        @endforeach
                    ],
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3,
                }, {
                    label: 'Kegiatan',
                    data: [
                        @foreach ($monthsOrder as $month)
                            {{ $combinedData->has($month) ? $combinedData[$month]['kegiatan'] : 0 }},
                        @endforeach
                    ],
                    backgroundColor: 'rgba(255, 159, 64, 0.2)',
                    borderColor: 'rgba(255, 159, 64, 1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3,
                }]
            },
            options: {
                plugins: {
                    title: {
                        display: true,
                        text: 'Traffic Pengajuan Perjadin dan Kegiatan Per Bulan',
                        font: {
                            size: 15,
                            weight: 'bold'
                        },
                        padding: {
                            top: 10,
                            bottom: 30
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // === Status Perjadin Pie Chart ===
        const ctxStatus = document.getElementById('statusChart').getContext('2d');
        const statusData = [
            {{ $countPengajuan }},
            {{ $countProses }},
            {{ $countDitolak }},
            {{ $countSelesai }},
        ];

        new Chart(ctxStatus, {
            type: 'pie',
            data: {
                labels: ['Pengajuan', 'Proses', 'Ditolak', 'Selesai'],
                datasets: [{
                    label: 'Status Pengajuan Perjadin',
                    data: statusData.every(v => v === 0) ? [1] : statusData,
                    backgroundColor: statusData.every(v => v === 0) ? ['#d3d3d3'] : ['#36a2eb', '#ffcd56',
                        '#ff6384', '#4bc0c0'
                    ],
                    hoverOffset: 4
                }]
            },
            options: {
                plugins: {
                    title: {
                        display: true,
                        text: 'Status Pengajuan Perjadin',
                        font: {
                            size: 15,
                            weight: 'bold'
                        },
                        padding: {
                            top: 10,
                            bottom: 30
                        }
                    },
                    legend: {
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: t => statusData.every(v => v === 0) ?
                                'Kosong' : t.label + ': ' + t.raw + ' Pengajuan'
                        }
                    }
                }
            }
        });
    </script>
@endsection
