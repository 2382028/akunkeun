@extends('admin.templates.sidebar')

@section('contain')
    <h4>Rekapitulasi BMN per Kategori hingga {{ \Carbon\Carbon::parse($tanggal)->format('d-m-Y') }}</h4>

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Kategori</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
                <tr>
                    <td>{{ $item->kategori_bmn }}</td>
                    <td>{{ $item->jumlah }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
