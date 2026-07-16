@extends('user.templates.sidebar')
@section('content')
    <section id="beranda" class="pb-5 pt-4">
        <div class="container">
            <div class="row mb-3 align-items-center">
                <div class="col-md-6">
                    <h4 class="fw-bold text-secondary">Riwayat Pemeliharaan</h4>
                </div>
                <div class="col-md-6 text-end">
                    <a href="{{ url('/pemeliharaan-pegawai/pengajuan') }}"
                        class="btn btn-primary btn-sm d-inline-flex align-items-center gap-2 shadow-sm px-3 py-2">
                        <i class="fa fa-plus"></i>
                        <span>Ajukan Pemeliharaan Baru</span>
                    </a>

                </div>
            </div>

            <div class="card shadow-sm rounded-0 border-0">
                <div class="card-body content">
                    <div class="col-md-12">
                        @if ($riwayat->count() > 0)
                            <div class="table-responsive">
                                <table id="example1" class="table table-bordered data-table table-sm" style="width: 100%; font-size: 13px;">
                                    <thead>
                                        <tr class="text-center small">
                                            <th>No.</th>
                                            <th>Nama BMN</th>
                                            <th>Kategori</th>
                                            <th>Kode</th>
                                            <th>NUP</th>
                                            <th>Status</th>
                                            <th>Keterangan</th>
                                            <th>Penyedia</th>
                                            <th>Tanggal Diajukan</th>
                                            <th>Terakhir Diperbarui</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($riwayat as $item)
                                            <tr>
                                                <td class='text-center'>{{ $loop->iteration }}</td>
                                                @php
                                                    $isRuangan = $item->bmn_type === 'ruangan';
                                                    $bmn = $item->bmn;
                                                @endphp

                                                <td>{{ $isRuangan ? $bmn->nama_ruangan : $bmn->nama_bmn }}</td>
                                                <td>{{ $isRuangan ? 'Gedung' : $bmn->kategori_bmn }}</td>
                                                <td>{{ $isRuangan ? $bmn->kode_ruangan : $bmn->kode_bmn }}</td>
                                                <td>{{ $isRuangan ? '-' : $bmn->nup_bmn }}</td>
                                                <td class='text-center'
                                                    data-order="{{ $item->id_ref_status_pemeliharaan }}">
                                                    @php
                                                        $status = $item->id_ref_status_pemeliharaan;

                                                        $colorClass = match (true) {
                                                            $status === 1
                                                                => 'bg-primary text-white', // Biru - Pejabat Pemeliharaan
                                                            in_array($status, [3, 8, 10, 13, 14])
                                                                => 'bg-warning text-dark', // Oranye - Pejabat Pengadaan
                                                            in_array($status, [4, 16])
                                                                => 'bg-success text-white', // Hijau - PPK
                                                            in_array($status, [6, 9, 12, 15, 18])
                                                                => 'bg-purple text-white', // Ungu - Penyedia
                                                            $status === 19 => 'bg-teal text-white', // Teal - Bendahara
                                                            $status === 21
                                                                => 'bg-info text-dark', // Abu-abu - Bendahara
                                                            default
                                                                => 'bg-danger text-white', // Merah - Status tak terdefinisi
                                                        };
                                                    @endphp
                                                    <span class="badge {{ $colorClass }}">
                                                        {{ $item->id_ref_status_pemeliharaan > 14 ? 'Selesai' : $item->status->deskripsi_status ?? '-' }}
                                                    </span>

                                                    @if (in_array($item->id_ref_status_pemeliharaan, [2, 5]) && $item->penolakan->last()?->alasan_penolakan)
                                                        <br>
                                                        <small
                                                            class="text-danger">{{ $item->penolakan->last()->alasan_penolakan }}</small>
                                                    @endif

                                                </td>
                                                <td>{{ $item->keterangan ?? '-' }}</td>
                                                <td>{{ $item->penyedia ? $item->penyedia->nama_CV : '-' }}</td>
                                                <td class="text-center">
                                                    {{ \Carbon\Carbon::parse($item->created_at)->locale('id')->translatedFormat('d F Y') }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $item->updated_at != $item->created_at
                                                        ? \Carbon\Carbon::parse($item->updated_at)->locale('id')->translatedFormat('d F Y')
                                                        : '-' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center text-muted">Belum ada riwayat pemeliharaan.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        $(document).ready(function() {
            $.fn.dataTable.ext.errMode = 'none';
            $('#example1').DataTable({
                order: [
                    [5, 'asc']
                ]
            });
        });
    </script>

@endsection
