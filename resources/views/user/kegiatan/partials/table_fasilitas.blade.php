<div class="table-responsive">
    <table class="table table-bordered table-sm align-middle" style="font-size: 0.85rem;">
        <thead>
            <tr class="text-center bg-light">
                <th style="width:50px">No</th>
                <th>Jenis Fasilitas</th>
                <th>Jumlah</th>
                <th>Satuan</th>
                <th>Tipe Pembayaran</th>
                <th>Keterangan</th>
                <th>Pelaksana</th>
                <th style="width:80px">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kebutuhans as $item)
            <tr class="text-center">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->nama }}</td>
                <td>{{ $item->jumlah_frekuensi }}</td>
                <td>{{ $item->satuan }}</td>
                <td>{{ $item->tipe_pendanaan }}</td>
                <td>{{ $item->ket }}</td>
                <td>{{ $item->pelaksana }}</td>
                <td>
                    <form class="ajax-delete" action="{{ url('/h_fasilitas_kegiatan/' . $item->idKebutuhan) }}" method="post">
                        @method('delete')
                        @csrf
                        <input type="hidden" value="{{ $kegiatan->id }}" name="kegiatanId">
                        <button type="submit" class="btn btn-danger btn-sm text-white w-100" onclick="return confirm('Hapus Data Fasilitas?')"><i class="fa fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center text-muted">Belum ada data fasilitas tambahan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
