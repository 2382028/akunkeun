<div class="table-responsive">
    <table class="table table-bordered table-sm data-table align-middle" style="font-size: 0.85rem;">
        <thead>
            <tr class="text-center bg-light">
                <th style="width: 50px">No</th>
                <th>Nama Lengkap</th>
                <th>Pangkat/Golongan</th>
                <th>Sebagai</th>
                <th>Status</th>
                <th style="width:80px">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <!-- Pegawai -->
            @foreach ($perangkatPegawais as $perangkatPegawai)
            <tr>
                <td class="text-center"></td>
                <td>{{ $perangkatPegawai->nama_lengkap }}</td>
                <td>{{ $perangkatPegawai->pangkat }} - {{ $perangkatPegawai->golongan }}</td>
                <td class="text-center">{{ $perangkatPegawai->sebagai }} ({{ $perangkatPegawai->posisi }})</td>
                <td class="text-center">{{ $perangkatPegawai->status }}</td>
                <td class="text-center">
                    <form class="ajax-delete" action="{{url('/h_peserta_kegiatan/'. $perangkatPegawai->idPerangkat)}}" method="post">
                        @method('DELETE')
                        @csrf
                        <input type="hidden" value="{{ $kegiatan->id }}" name="kegiatanId">
                        <button type="submit" class="btn btn-danger btn-sm text-white w-100" onclick="return confirm('Hapus Data Peserta?')"><i class="fa-solid fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            @endforeach

            <!-- Non-Pegawai -->
            @foreach ($perangkatNonPegawais as $perangkatNonPegawai)
            <tr>
                <td class="text-center"></td>
                <td>{{ $perangkatNonPegawai->nama_lengkap }}</td>
                <td>{{ $perangkatNonPegawai->pangkat }} - {{ $perangkatNonPegawai->golongan }}</td>
                <td class="text-center">{{ $perangkatNonPegawai->sebagai }} ({{ $perangkatNonPegawai->posisi }})</td>
                <td class="text-center">{{ $perangkatNonPegawai->status }}</td>
                <td class="text-center">
                    <form class="ajax-delete" action="{{url('/h_peserta_kegiatan/' . $perangkatNonPegawai->idPerangkat)}}" method="post">
                        @method('DELETE')
                        @csrf
                        <input type="hidden" value="{{ $kegiatan->id }}" name="kegiatanId">
                        <button type="submit" class="btn btn-danger btn-sm text-white w-100" onclick="return confirm('Hapus Data Peserta?')"><i class="fa-solid fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            @endforeach
            
            @if($perangkatPegawais->isEmpty() && $perangkatNonPegawais->isEmpty())
            <tr>
                <td colspan="6" class="text-center text-muted">Belum ada data kepanitiaan.</td>
            </tr>
            @endif
        </tbody>
    </table>
</div>
