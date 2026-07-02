<a href="#" class="lihat-file-btn btn btn-primary" data-bs-toggle="popover" data-bs-html="true" data-bs-content=""
@php
       if (isset($pesananList) && $pesananList->isNotEmpty()) {
           foreach ($pesananList->values() as $i => $pesanan) {
               echo 'data-pesanan' . ($i + 1) . '="' . e(basename($pesanan)) . '" ';
           }
       } elseif (!empty($item->pesanan->url_surat)) {
           echo 'data-pesanan="' . e(basename($item->pesanan->url_surat)) . '" ';
       }
   @endphp
    data-pengajuan_pembayaran="{{ isset($pengajuan) ? e(basename($pengajuan)) : '' }}"
data-bast="{{ isset($urlBast) ? e(basename($urlBast)) : '' }}"
data-bap="{{ isset($urlBap) ? e(basename($urlBap)) : '' }}"
    @if (isset($ids) && is_array($ids) && count($ids) > 1) data-id='@json($ids)'
    @else
        data-id="{{ $item->id ?? '' }}" @endif
    data-status="{{ $item->id_status ?? '' }}" data-lampiran='@json(isset($lampiranList) && is_iterable($lampiranList)
            ? $lampiranList->map(fn($l) => [
                    'nama_file' => $l->nama_file,
                    'file_url' => basename($l->url_lampiran),
                ]
    )
            : []
    )'
    data-bukti='@json(isset($buktiPengembalians) && is_iterable($buktiPengembalians)
            ? $buktiPengembalians->map(fn($b) => [
                    'nama_file' => $b->nama_file,
                    'file_url' => basename($b->url_bukti),
                ]
    )
            : []
    )'>
    <i class="fa-solid fa-eye pt-1"></i> Lihat File
</a>
