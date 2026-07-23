<div class="table-responsive">
    <table class="table table-bordered table-sm align-middle" style="font-size: 0.85rem;">
        <thead>
            <tr class="text-center bg-light">
                <th style="width:50px">No</th>
                <th>Nama Dokumen</th>
                <th>Lampiran</th>
                <th style="width:80px">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($dokumens as $dokumen)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $dokumen->nama_dokumen }}</td>
                <td class="text-center">
                    <a href="{{ url('kegiatan/getDokumen/' . basename($dokumen->file)) }}" target="_blank" class="btn btn-sm btn-info text-white"><i class="fa fa-file"></i> Lihat Lampiran</a>
                </td>
                <td class="text-center">
                    <form class="ajax-delete" action="{{url('/h_dokumen_kegiatan/' . $dokumen->id)}}" method="post">
                        @method('delete')
                        @csrf
                        <input type="hidden" value="{{ $kegiatan->id }}" name="kegiatanId">
                        <button type="submit" class="btn btn-danger btn-sm text-white w-100" onclick="return confirm('Hapus Data?')"><i class="fa-solid fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center text-muted">Belum ada data dokumen pendukung.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
