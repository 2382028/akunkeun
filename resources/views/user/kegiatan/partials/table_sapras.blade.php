<div class="table-responsive">
    <table class="table table-bordered table-sm align-middle" style="font-size: 0.85rem;">
        <thead>
            <tr class="text-center bg-light">
                <th style="width:50px">No</th>
                <th>Sarana</th>
                <th>Jumlah</th>
                <th>Tanggal Digunakan</th>
                <th>Status</th>
                <th style="width:80px">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($sapras as $sapra)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $sapra->nama_barang }}</td>
                <td class="text-center">{{ $sapra->jumlah_asset }}</td>
                <td class="text-center">{{ $sapra->tgl_peminjaman }}</td>
                <td class="text-center">{{ $sapra->status }}</td>
                <td class="text-center">
                    <form class="ajax-delete" action="{{url('/h_sapras/' . $sapra->idPeminjaman)}}" method="post">
                        @method('delete')
                        @csrf
                        <input type="hidden" value="{{ $kegiatan->id }}" name="kegiatanId">
                        <input type="hidden" value="{{ $sapra->IdBarang }}" name="idAsset">
                        <button type="submit" class="btn btn-danger btn-sm text-white w-100" onclick="return confirm('Hapus Data?')"><i class="fa-solid fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center text-muted">Belum ada data sarana dan prasarana.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
