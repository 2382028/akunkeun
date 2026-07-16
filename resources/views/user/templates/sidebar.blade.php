@php
  $activeVersi = \App\Models\Versi::where('status', 'aktif')->first() ?? (object)['id'=>'-1','versi'=>'Default Versi'];
  $isLoginPegawai = request()->is('akses');
  $namaBersihSidebar = '';
  if(auth('pegawai')->check()){
    $nl = auth('pegawai')->user()->nama_lengkap;
    $np = explode(',', $nl)[0];
    $namaBersihSidebar = trim(preg_replace('/\s+/', ' ', preg_replace('/\b(Dr\.|Drs\.|Ir\.|Prof\.|H\.|Hj\.|Dr|Drs|Ir|Prof|H|Hj)\b/i', '', $np)));
  }
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="icon" href="{{ asset('/assets/images/icon akunkeun.png') }}" type="image/x-icon">
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <link rel="stylesheet" href="{{asset('/assets/css/bootstrap.min.css')}}" />
  <link rel="stylesheet" href="{{asset('/assets/css/style.css')}}" />
  <link rel="stylesheet" href="{{asset('/assets/css/style-user-sidebar.css')}}" />
  <link rel="stylesheet" href="{{asset('/vanila/main.css')}}">
  <link rel="stylesheet" href="{{asset('/assets/css/dataTables.bootstrap5.min.css')}}" />
  <link rel="stylesheet" href="{{asset('/assets/css/main.css')}}">
  <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <title>{{ $title }} | Aplikasi Keuangan dan Urusan Kegiatan</title>
  <style>
    .unread { background-color: #f8f9fa; font-weight: bold; }
    .read { background-color: #ffffff; font-weight: normal; }
  </style>
</head>
<body class="user-sidebar-layout sidebar-open">

{{-- TOP NAVBAR --}}
<nav class="user-topbar">
  <button class="sidebar-toggler-btn" id="sidebarToggler"><i class="fa-solid fa-bars"></i></button>
  <a class="topbar-brand" href="{{ url('/') }}">
    <img src="{{ asset('/assets/images/LLDIKTI4 final1.png') }}" alt="LLDIKTI4" class="logo-lldikti">
    <span class="brand-divider"></span>
    <img src="{{ asset('/assets/images/brand-logo.png') }}" alt="Akunkeun" class="logo-akunkeun">
  </a>
  <div class="topbar-right">
    @auth('pegawai')
    <div class="dropdown">
      <a class="topbar-notif dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false" style="text-decoration:none;">
        <i class="fa-solid fa-bell"></i>
        <span id="notif" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:0.6rem;">0</span>
      </a>
      <ul class="dropdown-menu dropdown-menu-end p-3" style="width:400px;max-height:400px;overflow-y:auto;">
        <li class="dropdown-header text-center fw-bold text-primary">Notifikasi Terbaru</li>
        <div id="notif-list"></div>
        <div id="notif-list-zero"></div>
        <li class="dropdown-footer text-center" id="mark-all-read-item" style="display:none;">
          <a href="#" id="mark-all-read" class="text-primary">Tandai Semua sudah dibaca</a>
        </li>
      </ul>
    </div>
    <div class="dropdown">
      <a class="topbar-profile-btn dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="text-decoration:none;">
        <i class="fa-solid fa-circle-user"></i>
        <div class="d-flex flex-column text-start">
          <span class="topbar-profile-name" style="line-height: 1;">{{ $namaBersihSidebar }}</span>
          @if(session('versi'))
            <span class="text-muted fw-normal" style="font-size: 0.65rem; margin-top: 2px;">TA {{ \App\Models\Versi::find(session('versi'))->versi ?? '-' }}</span>
          @endif
        </div>
      </a>
      <ul class="dropdown-menu dropdown-menu-end">
        <li><a class="dropdown-item" href="{{ url('/profile') }}"><i class="fa-solid fa-user me-2"></i>Profile</a></li>
        <li><a class="dropdown-item" href="{{ url('/profile/ubah-password') }}"><i class="fa-solid fa-key me-2"></i>Ubah Password</a></li>
        <li><hr class="dropdown-divider"></li>
        <form action="{{ url('/logout') }}" method="post">@csrf
          <li><button type="submit" class="dropdown-item text-danger"><i class="fa-solid fa-sign-out-alt me-2"></i>Keluar</button></li>
        </form>
      </ul>
    </div>
    @endauth
  </div>
</nav>

{{-- SIDEBAR OVERLAY --}}
<div class="user-sidebar-overlay" id="sidebarOverlay"></div>

{{-- LEFT SIDEBAR --}}
<aside class="user-sidebar" id="userSidebar">
  <ul class="sidebar-menu">
    {{-- Dashboard --}}
    <li class="sidebar-item">
      <a href="{{ url('/') }}" class="sidebar-link {{ (isset($active) && $active=='index') ? 'active' : '' }}">
        <i class="fa-solid fa-gauge"></i><span>Dashboard</span>
      </a>
    </li>

    {{-- Pengajuan Kegiatan --}}
    <li class="sidebar-item">
      <button class="sidebar-toggle" data-bs-toggle="collapse" data-bs-target="#menuKegiatan" aria-expanded="{{ (isset($active) && in_array($active, ['perjadin_biasa','perjadin_kegiatan','riwayat_pengajuan'])) ? 'true' : 'false' }}">
        <i class="fa-solid fa-calendar-check"></i><span>Pengajuan Kegiatan</span>
        <i class="fa-solid fa-chevron-right toggle-icon"></i>
      </button>
      <div class="collapse {{ (isset($active) && in_array($active, ['perjadin_biasa','perjadin_kegiatan','riwayat_pengajuan'])) ? 'show' : '' }}" id="menuKegiatan">
        <ul class="sidebar-submenu">
          @if ($activeVersi && $activeVersi->id != session('versi'))
            <li><a href="#" onclick="showAlert(event)" class="sidebar-link"><i class="fa-solid fa-plane-departure"></i>Perjalanan Dinas</a></li>
            <li><a href="#" onclick="showAlert(event)" class="sidebar-link"><i class="fa-solid fa-clipboard-list"></i>Program Kegiatan</a></li>
          @else
            <li><a href="{{ url('/perjadin') }}" class="sidebar-link {{ (isset($active) && $active=='perjadin_biasa') ? 'active' : '' }}"><i class="fa-solid fa-plane-departure"></i>Perjalanan Dinas</a></li>
            <li><a href="{{ url('/kegiatan') }}" class="sidebar-link {{ (isset($active) && $active=='perjadin_kegiatan') ? 'active' : '' }}"><i class="fa-solid fa-clipboard-list"></i>Program Kegiatan</a></li>
          @endif
          <li><a href="{{ url('/perjadin/riwayat/semua') }}" class="sidebar-link {{ (isset($active) && $active=='riwayat_pengajuan') ? 'active' : '' }}"><i class="fa-solid fa-clock-rotate-left"></i>Riwayat Pengajuan</a></li>
        </ul>
      </div>
    </li>

    {{-- Pemeliharaan & Peminjaman --}}
    <li class="sidebar-item">
      <button class="sidebar-toggle" data-bs-toggle="collapse" data-bs-target="#menuBMN" aria-expanded="{{ (isset($active) && in_array($active, ['peminjaman','pemeliharaan','barang_saya','riwayat_pemeliharaan'])) ? 'true' : 'false' }}">
        <i class="fa-solid fa-screwdriver-wrench"></i><span>Pemeliharaan & Peminjaman</span>
        <i class="fa-solid fa-chevron-right toggle-icon"></i>
      </button>
      <div class="collapse {{ (isset($active) && in_array($active, ['peminjaman','pemeliharaan','barang_saya','riwayat_pemeliharaan'])) ? 'show' : '' }}" id="menuBMN">
        <ul class="sidebar-submenu">
          <li><a href="{{ url('/fasilitas') }}" class="sidebar-link {{ (isset($active) && $active=='peminjaman') ? 'active' : '' }}"><i class="fa-solid fa-hand-holding"></i>Pengajuan Peminjaman</a></li>
          <li><a href="{{ url('/pemeliharaan-pegawai/pengajuan') }}" class="sidebar-link {{ (isset($active) && $active=='pemeliharaan') ? 'active' : '' }}"><i class="fa-solid fa-tools"></i>Pengajuan Pemeliharaan</a></li>
          <li><a href="{{ url('/riwayat_barang/pengajuan') }}" class="sidebar-link {{ (isset($active) && $active=='barang_saya') ? 'active' : '' }}"><i class="fa-solid fa-box-open"></i>Barang Saya</a></li>
          <li><a href="{{ url('/pemeliharaan-pegawai') }}" class="sidebar-link {{ (isset($active) && $active=='riwayat_pemeliharaan') ? 'active' : '' }}"><i class="fa-solid fa-history"></i>Riwayat Pemeliharaan</a></li>
        </ul>
      </div>
    </li>

    {{-- Pengaturan --}}
    <li class="sidebar-item">
      <button class="sidebar-toggle" data-bs-toggle="collapse" data-bs-target="#menuSetting" aria-expanded="{{ (isset($active) && in_array($active, ['profile','ubah_password'])) ? 'true' : 'false' }}">
        <i class="fa-solid fa-gear"></i><span>Pengaturan</span>
        <i class="fa-solid fa-chevron-right toggle-icon"></i>
      </button>
      <div class="collapse {{ (isset($active) && in_array($active, ['profile','ubah_password'])) ? 'show' : '' }}" id="menuSetting">
        <ul class="sidebar-submenu">
          <li><a href="{{ url('/profile') }}" class="sidebar-link {{ (isset($active) && $active=='profile') ? 'active' : '' }}"><i class="fa-solid fa-user"></i>Profile</a></li>
          <li><a href="{{ url('/profile/ubah-password') }}" class="sidebar-link {{ (isset($active) && $active=='ubah_password') ? 'active' : '' }}"><i class="fa-solid fa-key"></i>Ubah Password</a></li>
          <li>
            <form action="{{ url('/logout') }}" method="post" class="m-0">@csrf
              <button type="submit" class="sidebar-link w-100 border-0 bg-transparent text-start"><i class="fa-solid fa-sign-out-alt"></i>Keluar</button>
            </form>
          </li>
        </ul>
      </div>
    </li>
  </ul>
</aside>

{{-- MAIN CONTENT --}}
<div class="user-main-content">
  <div class="content-body">
    @yield('content')

    @if(session()->has('success'))
    <div aria-live="polite" aria-atomic="true" class="position-relative">
      <div class="toast-container end-0 mt-4 pt-4 position-fixed" style="z-index:1060;">
        <div class="show toast" role="alert" aria-live="assertive" aria-atomic="true" id="myToast">
          <div class="toast-header"><strong class="me-auto">Pesan Sobat Akunkeun</strong><button type="button" class="btn-close" data-bs-dismiss="toast"></button></div>
          <div class="toast-body">{{ session('success') }}</div>
        </div>
      </div>
    </div>
    @endif
    @if(session()->has('error'))
    <div aria-live="polite" aria-atomic="true" class="position-relative">
      <div class="toast-container end-0 mt-4 pt-4 position-fixed" style="z-index:1060;">
        <div class="show toast text-bg-danger" role="alert" aria-live="assertive" aria-atomic="true" id="myToast">
          <div class="toast-header"><strong class="me-auto">Kesalahan</strong><button type="button" class="btn-close" data-bs-dismiss="toast"></button></div>
          <div class="toast-body">{{ session('error') }}</div>
        </div>
      </div>
    </div>
    @endif
    @if($errors->any())
    <div aria-live="polite" aria-atomic="true" class="position-relative">
      <div class="toast-container end-0 mt-4 pt-4 position-fixed" style="z-index:1060;">
        <div class="show toast text-bg-danger" role="alert" aria-live="assertive" aria-atomic="true" id="myToast">
          <div class="toast-header"><strong class="me-auto">Kesalahan Validasi</strong><button type="button" class="btn-close" data-bs-dismiss="toast"></button></div>
          <div class="toast-body">@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>
        </div>
      </div>
    </div>
    @endif
  </div>
  <div class="content-footer">copyright {{ $activeVersi->versi }}, Akunkeun - Aplikasi Kegiatan dan Urusan Keuangan</div>
</div>

{{-- Version Alert Modal --}}
<div class="modal fade" id="versionAlertModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title">Akses Tidak Diperbolehkan</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body">
      <p>Anda tidak dapat mengakses halaman ini karena Tahun Anggaran yang sedang Anda pilih tidak sesuai dengan Tahun Anggaran yang aktif.</p>
      <p><strong>Tahun Anggaran yang dipilih: </strong><span id="currentVersion"></span></p>
      <p><strong>Tahun Anggaran yang diaktifkan: </strong><span id="requiredVersion"></span></p>
    </div>
    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button></div>
  </div></div>
</div>

<script src="{{asset('/assets/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('/assets/js/navbar.js')}}"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="{{asset('/assets/js/aos.js')}}"></script>
<script src="{{asset('/assets/js/script1.js')}}"></script>
<script src="{{asset('/vanila/main.js')}}"></script>
<script src="{{asset('/assets/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('/assets/js/dataTables.bootstrap5.min.js')}}"></script>
<script src="{{asset('/assets/js/data-table.js')}}"></script>
<script src="{{asset('/assets/js/preventSelect2.js')}}"></script>
<script src="{{asset('/assets/js/toast.js')}}"></script>
<script src="{{asset('/assets/js/preventSurtug.js')}}"></script>
<script src="{{asset('/assets/js/preventTable.js')}}"></script>
<script src="{{asset('/assets/js/preventDokumen.js')}}"></script>
<script src="{{asset('/assets/js/nonPegawai.js')}}"></script>
<script src="{{asset('/assets/js/preventSelect.js')}}"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/30.0.0/classic/ckeditor.js"></script>

{{-- Sidebar toggle --}}
<script>
document.getElementById('sidebarToggler').addEventListener('click', function(){
  if (window.innerWidth >= 992) {
    document.body.classList.toggle('sidebar-open');
  } else {
    document.getElementById('userSidebar').classList.toggle('show');
    document.getElementById('sidebarOverlay').classList.toggle('show');
  }
});

document.getElementById('sidebarOverlay').addEventListener('click', function(){
  document.getElementById('userSidebar').classList.remove('show');
  this.classList.remove('show');
});

// Handle resize events to ensure layout state makes sense
window.addEventListener('resize', function() {
  if (window.innerWidth >= 992) {
    document.getElementById('sidebarOverlay').classList.remove('show');
    document.getElementById('userSidebar').classList.remove('show');
  }
});
</script>

{{-- Fasilitas data scripts --}}
<script>
$(document).ready(function() {
  let refFasilitasData = [];
  $.ajax({ url: '/get-data-fasilitas', method: 'GET', dataType: 'json', success: function(data) { refFasilitasData = data; }});
  $('#uraian').change(function() {
    const sv = $(this).val();
    const sf = refFasilitasData.find(f => f.nama_fasilitas === sv);
    const sat = sf ? sf.satuan : 'Kali';
    $('#conditional_fields').html(`<div class="row"><div class="col-md-12 mb-3"><label class="detail-fields">Jumlah <span class="text-danger">*</span></label><div class="input-group"><input type="number" min="0" class="form-control" name="jumlah_frekuensi" required></div></div><div class="col-md-12 mb-3"><label class="form-label">Satuan<span class="text-danger">*</span></label><input type="text" class="form-control" name="satuan" value="${sat}" readonly></div><div class="col-md-12 mb-3"><label class="detail-fields">Tipe Pendanaan<span class="text-danger">*</span></label><div class="input-group"><select class="form-select" name="tipe_pendanaan"><option value="Bayar di awal" selected>Dibayar di Awal</option><option value="Reimburse">Reimburse</option></select></div></div><div class="col-md-12 mb-3"><label class="form-label">Keterangan Fasilitas <span class="text-danger">*</span><span class="text-secondary small">(Contoh : Perkiraan Harga)</span></label><input type="text" name="keterangan" class="form-control" required></div></div>`);
  });
  $('#uraian_pelaksana').change(function() {
    const sv = $(this).val();
    const sf = refFasilitasData.find(f => f.nama_fasilitas === sv);
    const sat = sf ? sf.satuan : 'Kali';
    $('#conditional_fields_pelaksana').html(`<div class="row"><div class="col-md-12 mb-3"><label class="detail-fields">Jumlah <span class="text-danger">*</span></label><div class="input-group"><input type="number" min="0" class="form-control" name="jumlah_frekuensi" required></div></div><div class="col-md-12 mb-3"><label class="form-label">Satuan<span class="text-danger">*</span></label><input type="text" class="form-control" name="satuan" value="${sat}" readonly></div><div class="col-md-12 mb-3"><label class="detail-fields">Tipe Pendanaan<span class="text-danger">*</span></label><div class="input-group"><select class="form-select" name="tipe_pendanaan"><option value="Bayar di awal" selected>Dibayar di Awal</option><option value="Reimburse">Reimburse</option></select></div></div><div class="col-md-12 mb-3"><label class="form-label">Keterangan Fasilitas <span class="text-danger">*</span><span class="text-secondary small">(Contoh : Perkiraan Harga)</span></label><input type="text" name="keterangan" class="form-control" required></div></div>`);
  });
});
</script>

{{-- Notification scripts --}}
<script>
$(document).ready(function() {
  @if(auth('pegawai')->check())
    var iduser = {{ auth('pegawai')->user()->id }};
  @else
    var iduser = 0;
  @endif
  function notifUser() {
    $.ajax({ url: "/notifUser/" + iduser, type: "GET", dataType: "json",
      success: function(res) {
        let totalNotif = res.notif;
        $("#notif").text(res.notifDataUnread.length > 100 ? '100+' : totalNotif);
        $('#notif-list').empty();
        if (res.notifDataUnread.length > 0) {
          $('#mark-all-read-item').show();
          res.notifDataUnread.forEach(function(notif) {
            $('#notif-list').append(`<li class="dropdown-item p-3 mb-2 border-bottom d-flex align-items-start" data-route="${notif.route}" data-id="${notif.id}" style="background-color:${notif.is_read?'#fff':'#e7f3fe'};font-weight:${notif.is_read?'normal':'bold'};"><div class="me-3"><img src="{{ asset('/assets/images/icon akunkeun.png') }}" width="50" class="rounded-circle"></div><div class="flex-grow-1"><div class="fw-bold text-dark">${notif.header||'Notifikasi Baru'}</div><div class="text-muted" style="max-width:350px;white-space:normal;">${notif.message||''}</div><div style="font-size:12px;color:#6c757d;">${new Date(notif.created_at).toLocaleDateString()}</div></div></li>`);
          });
        }
        if (res.notifDataRead.length > 0) {
          $('#notif-list').append('<div style="font-size:14px;color:#6c757d;margin-bottom:5px;">Notifikasi telah dibaca</div>');
          res.notifDataRead.forEach(function(notif) {
            $('#notif-list').append(`<li class="dropdown-item p-3 mb-2 border-bottom d-flex align-items-start read" data-route="${notif.route}" data-id="${notif.id}"><div class="me-3"><img src="{{ asset('/assets/images/icon akunkeun.png') }}" width="50" class="rounded-circle"></div><div class="flex-grow-1"><div class="fw-bold text-dark">${notif.header||'Notifikasi Lama'}</div><div class="text-muted" style="max-width:350px;white-space:normal;">${notif.message||''}</div><div style="font-size:12px;color:#6c757d;">${new Date(notif.created_at).toLocaleDateString()}</div></div></li>`);
          });
        }
        if (res.notifDataUnread.length === 0 && res.notifDataRead.length === 0) {
          if ($('#notif-list-zero li').length === 0) {
            $('#notif-list-zero').append(`<li class="dropdown-item p-3 mb-2 text-center" style="color:#6c757d;">Tidak Ada Notifikasi</li>`);
          }
        }
        $('#notif-list').on('click', '.dropdown-item', function() {
          const route = $(this).data('route'), notifId = $(this).data('id');
          $.ajax({ url: `/notifUser/read/${notifId}`, type:'POST', data:{_token:'{{ csrf_token() }}'}, success: function(){ window.location.href = `${window.location.origin}/${route}`; }});
        });
      }
    });
  }
  notifUser();
  setInterval(notifUser, 10000);
  $('#mark-all-read').click(function(e) {
    e.preventDefault();
    if (confirm('Apakah Anda yakin ingin menandai semua notifikasi sebagai dibaca?')) {
      $.ajax({ url:"/mark-all-notif-user/"+iduser, method:'POST', data:{_token:'{{ csrf_token() }}'}, success:function(){ location.reload(); }});
    }
  });
});
</script>

{{-- Pemeliharaan notification --}}
<script>
$(document).ready(function() {
  @if(auth('pegawai')->check())
    var iduserP = {{ auth('pegawai')->user()->id }};
  @else
    var iduserP = 0;
  @endif
  function notifUserP() {
    $.ajax({ url:"/notifPemeliharaanUser/"+iduserP, type:"GET", dataType:"json",
      success: function(res) {
        res.notifDataUnread.forEach(function(notif) {
          $('#notif-list').append(`<li class="dropdown-item p-3 mb-2 border-bottom d-flex align-items-start" data-route="${notif.route}" data-id="${notif.id}" style="background-color:#e7f3fe;font-weight:bold;"><div class="me-3"><img src="{{ asset('/assets/images/icon akunkeun.png') }}" width="50" class="rounded-circle"></div><div class="flex-grow-1"><div class="fw-bold text-dark">${notif.header||'Notifikasi Baru'}</div><div class="text-muted" style="max-width:350px;">${notif.message||''}</div><div style="font-size:12px;color:#6c757d;">${new Date(notif.created_at).toLocaleDateString()}</div></div></li>`);
        });
        $('#notif-list').on('click', '.dropdown-item', function() {
          const route=$(this).data('route'), notifId=$(this).data('id');
          $.ajax({ url:`/notifPemeliharaanUser/read/${notifId}`, type:'POST', data:{_token:'{{ csrf_token() }}'}, success:function(){ window.location.href=`${window.location.origin}/${route}`; }});
        });
      }
    });
  }
  notifUserP();
  setInterval(notifUserP, 10000);
});
</script>

<script>
function showAlert(event) {
  event.preventDefault();
  document.getElementById('currentVersion').innerText = {{ \App\Models\Versi::find(session('versi'))->versi ?? 'Tidak Ada Tahun' }};
  document.getElementById('requiredVersion').innerText = '{{ $activeVersi->versi }}';
  var myModal = new bootstrap.Modal(document.getElementById('versionAlertModal'));
  myModal.show();
}
</script>
<script>
ClassicEditor.create(document.querySelector('#textarea2')).catch(error => { console.error(error); });
</script>
<script>
$(window).on('load', function() { $('#template').modal('show'); });
$(document).on('click', '#unduhTemplate', function() { $('#template').modal('show'); });
</script>
</body>
</html>
