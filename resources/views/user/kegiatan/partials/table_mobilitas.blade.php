<div class="table-responsive">
    <table class="table table-bordered table-sm align-middle" style="font-size: 0.85rem;">
        <thead>
            <tr class="text-center bg-light">
                <th style="width:50px">No</th>
                <th>Mobilitas</th>
                <th>Tanggal Digunakan</th>
                <th>Kegunaan</th>
                <th style="width:80px">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($mobilitas as $mobil)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $mobil->mobilitas }}</td>
                <td class='text-center'>{{$mobil->tgl_mulai}}</td>
                <td class='text-center'>{{$mobil->tujuan_penggunaan}}</td>
                <td class='text-center'>
                <form class="ajax-delete" action="{{url('/h_mobilitas_kegiatan/' . $mobil->id)}}" method="post">
                     @method('delete')
                    @csrf
                    <input type="hidden" value="{{ $kegiatan->id }}" name="kegiatanId">
                    <button type="submit" class="btn btn-danger btn-sm text-white w-100" onclick="return confirm('Hapus Data Mobilitas?')"><i class="fa-solid fa-trash"></i></button>
                </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center text-muted">Belum ada data mobilitas.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
