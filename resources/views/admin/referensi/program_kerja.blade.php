<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Logo -->
    <link rel="icon" href="images/icon akunkeun.png" type="image/x-icon">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css" />

    <!-- My CSS -->
    <link rel="stylesheet" href="css/style.css" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Data Tables-->
    <link rel="stylesheet" href="css/dataTables.bootstrap5.min.css" />

    <title>Referensi - Program Kerja</title>
  </head>
  <body>
    <!-- Awal Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top bg-white" style="color: black">
      <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas"  data-bs-target="#sidebar" aria-controls="offcanvasExample">
          <span class="navbar-toggler-icon" data-bs-target="#sidebar"></span>
        </button>
        <a class="navbar-brand py-0" href="index.html">
          <img src="images/Tut-Wuri-Handayani.png.crdownload" alt="" width="50">
          <h5 class="d-inline-block align-text-top fw-bold pt-1">AKUNKEUN</h5>
        </a>        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topNavBar" aria-controls="topNavBar" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="topNavBar">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item mb-2 pt-2">
              <a class="nav-link py-0 small" href="#">
                <i class="fa-solid fa-bell"></i> Pemberitahuan
              </a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle small" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa-solid fa-user"></i>  User
              </a>
              <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="" aria-current-active="true">Profile</a></li>
                <li><hr class="dropdown-divider"></li>
                <form action="" method="post">
                  <li><button type="submit" class="dropdown-item">Keluar</button></li>
                </form>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="">Pengaturan</a></li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <!-- Akhir Navbar -->

    <!-- Awal Sidebar -->
    <div class="container-fluid">
      <div class="offcanvas offcanvas-start sidebar-nav pt-4" id="sidebar" style="background: #082A99">
        <div class="offcanvas-body p-0">
          <nav class="navbar-dark">
            <ul class="navbar-nav">
              <li>
                <a href="index.html" class="nav-link px-3 sidebar-link">
                  <span class="me-2"><i class="fa-solid fa-gauge"></i></span>
                  <span>Dashboard</span>
                </a>
              </li>
              <li>
                <a class="nav-link px-3 sidebar-link" data-bs-toggle="collapse" href="#referensi">
                  <span class="me-2"><i class="fa-solid fa-table-columns"></i></span>
                  <span>Referensi</span>
                  <span class="ms-auto">
                    <span class="right-icon">
                      <i class="fa-solid fa-chevron-down"></i>
                    </span>
                  </span>
                </a>
                <div class="collapse" id="referensi">
                  <ul class="navbar-nav ps-3">
                    <li> 
                      <a href="iku.html" class="nav-link px-3 text-white">
                        <span class="me-2 "><i class="fa-solid fa-file"></i></i></span>
                        <span>IKU</span>
                      </a>
                    </li>
                    <li>
                      <a href="sbm.html" class="nav-link px-3 text-white">
                        <span class="me-2"><i class="fa-solid fa-file"></i></i></span>
                        <span>SBM</span>
                      </a>
                    </li>
                    <li>
                      <a href="rkakl_satker.html" class="nav-link px-3 text-white">
                        <span class="me-2"><i class="fa-solid fa-file"></i></i></span>
                        <span>RKAKL</span>
                      </a>
                    </li>
                    <li>
                      <a href="program_kerja.html" class="nav-link px-3 text-white active">
                        <span class="me-2"><i class="fa-solid fa-file"></i></i></span>
                        <span>Program Kerja</span>
                      </a>
                    </li>
                    <li>
                  </ul>
                </div>
              </li>
              <li> 
                <a class="nav-link px-3 sidebar-link" data-bs-toggle="collapse" href="#perjadin_langsung">
                  <span class="me-2"><i class="fa-solid fa-train-subway"></i></span>
                  <span>Perjadin Langsung</span>
                  <span class="ms-auto">
                    <span class="right-icon">
                      <i class="fa-solid fa-chevron-down"></i>
                    </span>
                  </span>
                </a>
                <div class="collapse" id="perjadin_langsung">
                  <ul class="navbar-nav ps-3">
                    <li>
                      <a href="perjadin_bmn_kendaraan.html" class="nav-link px-3 text-white">
                        <span class="me-2"><i class="fa-solid fa-car-side"></i></span>
                        <span>BMN Kendaraan</span>
                      </a>
                    </li>
                    <li>
                      <a href="perjadin_keuangan.html" class="nav-link px-3 text-white">
                        <span class="me-2"
                          ><i class="fa-solid fa-sack-dollar"></i></span>
                        <span>Keuangan</span>
                      </a>
                    </li>
                    <li>
                      <a href="perjadin_bendahara.html" class="nav-link px-3 text-white">
                        <span class="me-2"
                          ><i class="fa-solid fa-money-bill-wave"></i></span>
                        <span>Bendahara</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
              <li>
                <a class="nav-link px-3 sidebar-link" data-bs-toggle="collapse" href="#perjadin_kegiatan">
                  <span class="me-2"><i class="fa-regular fa-calendar-days"></i></span>
                  <span>Perjadin Kegiatan</span>
                  <span class="ms-auto">
                    <span class="right-icon">
                      <i class="fa-solid fa-chevron-down"></i>
                    </span>
                  </span>
                </a>
                <div class="collapse" id="perjadin_kegiatan">
                  <ul class="navbar-nav ps-3">
                    <li>
                      <a href="kegiatan_bmn_kendaraan.html" class="nav-link px-3 text-white">
                        <span class="me-2"
                          ><i class="fa-solid fa-car-side"></i></span>
                        <span>BMN Kendaraan</span>
                      </a>
                    </li>
                    <li>
                      <a href="kegiatan_bmn_asset.html" class="nav-link px-3 text-white">
                        <span class="me-2"
                          ><i class="fa-solid fa-building-columns"></i></span>
                        <span>BMN Aset</span>
                      </a>
                    </li>
                    <li>
                      <a href="kegiatan_keuangan.html" class="nav-link px-3 text-white">
                        <span class="me-2"
                          ><i class="fa-solid fa-sack-dollar"></i></span>
                        <span>Keuangan</span>
                      </a>
                    </li>
                    <li>
                      <a href="kegiatan_bendahara.html" class="nav-link px-3 text-white">
                        <span class="me-2"
                          ><i class="fa-solid fa-money-bill-wave"></i></span>
                        <span>Bendahara</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
              <li>
                <a class="nav-link px-3 sidebar-link" data-bs-toggle="collapse" href="#bmn">
                  <span class="me-2"><i class="fa-solid fa-box-archive"></i></span>
                  <span>BMN</span>
                  <span class="ms-auto">
                    <span class="right-icon">
                      <i class="fa-solid fa-chevron-down"></i>
                    </span>
                  </span>
                </a>
                <div class="collapse" id="bmn">
                  <ul class="navbar-nav ps-3">
                    <li>
                      <a href="data_penyedia.html" class="nav-link px-3 text-white">
                        <span class="me-2"
                          ><i class="fa-solid fa-database"></i></span>
                        <span>Data Penyedia</span>
                      </a>
                    </li>
                    <li>
                      <a href="data_kendaraan.html" class="nav-link px-3 text-white">
                        <span class="me-2"
                          ><i class="fa-solid fa-database"></i></span>
                        <span>Data Kendaraan</span>
                      </a>
                    </li>
                    <li>
                      <a href="data_ruangan.html" class="nav-link px-3 text-white">
                        <span class="me-2"
                          ><i class="fa-solid fa-database"></i></span>
                        <span>Data Ruangan</span>
                      </a>
                    </li>
                    <li>
                      <a href="data_aset.html" class="nav-link px-3 text-white">
                        <span class="me-2"
                          ><i class="fa-solid fa-database"></i></span>
                        <span>Data Aset</span>
                      </a>
                    </li>
                    <li>
                      <a href="peminjaman_aset.html" class="nav-link px-3 text-white">
                        <span class="me-2"
                          ><i class="fa-solid fa-arrows-spin"></i></span>
                        <span>Peminjaman Aset</span>
                      </a>
                    </li>
                    <li>
                      <a href="perbaikan_kendaraan.html" class="nav-link px-3 text-white">
                        <span class="me-2"
                          ><i class="fa-solid fa-screwdriver-wrench"></i></span>
                        <span>Perbaikan Kendaraan</span>
                      </a>
                    </li>
                    <li>
                      <a href="perbaikan_aset.html" class="nav-link px-3 text-white">
                        <span class="me-2"
                          ><i class="fa-solid fa-screwdriver-wrench"></i></span>
                        <span>Perbaikan Aset</span>
                      </a>
                    </li>
                    <li>
                      <a href="perbaikan_ruangan.html" class="nav-link px-3 text-white">
                        <span class="me-2"
                          ><i class="fa-solid fa-screwdriver-wrench"></i></span>
                        <span>Perbaikan Ruangan</span>
                      </a>
                    </li>
                    <li>
                      <a href="bmn_keuangan.html" class="nav-link px-3 text-white">
                        <span class="me-2"
                          ><i class="fa-solid fa-sack-dollar"></i></span>
                        <span>Keuangan</span>
                      </a>
                    </li>
                    <li>
                      <a href="bmn_bendahara.html" class="nav-link px-3 text-white">
                        <span class="me-2"
                          ><i class="fa-solid fa-money-bill-wave"></i></span>
                        <span>Bendahara</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
              <li>
                <a class="nav-link px-3 sidebar-link" data-bs-toggle="collapse" href="#kelola_user">
                  <span class="me-2"><i class="fa-solid fa-users-gear"></i></span>
                  <span>Kelola User</span>
                  <span class="ms-auto">
                    <span class="right-icon">
                      <i class="fa-solid fa-chevron-down"></i>
                    </span>
                  </span>
                </a>
                <div class="collapse" id="kelola_user">
                  <ul class="navbar-nav ps-3">
                    <li>
                      <a href="pegawai.html" class="nav-link px-3 text-white">
                        <span class="me-2"><i class="fa-solid fa-database"></i></span> 
                        <span>Data Pegawai</span>
                      </a>
                    </li>
                    <li>
                      <a href="nonpegawai.html" class="nav-link px-3 text-white">
                        <span class="me-2"><i class="fa-solid fa-database"></i></span>
                        <span>Data Non Pegawai</span>
                      </a>
                    </li>
                    <li>
                      <a href="administrator.html" class="nav-link px-3 text-white">
                        <span class="me-2"><i class="fa-solid fa-user-lock"></i></span>
                        <span>Administrator</span>
                      </a>
                    </li>
                    <li>
                      <a href="user.html" class="nav-link px-3 text-white">
                        <span class="me-2"><i class="fa-solid fa-user"></i></span>
                        <span>User</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
              <li>
                <a class="nav-link px-3 sidebar-link" href="laporan.html">
                  <span class="me-2"><i class="fa-solid fa-file-circle-exclamation"></i></span>
                  <span>Laporan</span>
                </a>
              </li>
              <li>
                <a class="nav-link px-3 sidebar-link" href="pengaturan.html">
                  <span class="me-2"><i class="fa-solid fa-gear"></i></span>
                  <span>Pengaturan</span>
                </a>
              </li>
            </ul>
          </nav>
        </div>
      </div>
    </div>
    <!-- Akhir Sidebar -->

    <!-- Awal Dashboard RKAKL Program Kerja -->
    <main class="mt-5 pt-3" style="background: #D9D9D9;">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <h4>Referensi / <span class="fw-bold">Program Kerja</span></h4>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12 mb-3">
            <div class="card">
              <div class="card-body">
                <div class="table-responsive">
                    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambah_programkerja">
                        <i class="fa fa-plus"></i> Tambah
                    </button>
                  <table id="example" class="table table-bordered data-table" style="width: 100%">
                    <thead>
                      <tr class="text-center small">
                        <th class="th-sm">No</th>
                        <th>Nama Program</th>
                        <th class="th-lg">Kode RKAKL</th>
                        <th class="th-lg">Biaya</th>
                        <th class="th-lg-percent">Aksi</th>
                      </tr>
                    </thead>
                        <td class=''></td>
                        <td class='text-center'></td>
                        <td class='text-center'></td>
                        <td class='text-center'></td>
                        <td class='text-center d-flex justify-content-evenly'>
                            <span>
                                <a href="" class="text-decoration-none btn btn-primary btn-sm">
                                    <i class="fa-regular fa-file-lines"></i>
                                </a>
                            </span>
                            <span>
                                <a href="" class="text-decoration-none btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#edit_programkerja">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                            </span>
                            <span>
                                <a href="" class="text-decoration-none btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#delete_programkerja">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </span>
                        </td>
                    </tr>
                    </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
    <!-- Akhir Dashboard RKAKL Program Kerja -->

    <!-- Awal Footer -->
    <section id="footer" class="bg-white">
      <div class="container-fluid">
        <div class="row text-center justify-content-center">
          <div class="col-md-6 offset-md-2 small fw-bold py-3">Copyright &#169; 2023. All Right Reserved.</div>
        </div>
      </div>
    </section>
    <!-- Akhir Footer -->
    
    <!-- Aawal Tambah Program Kerja -->
    <div class="modal fade" id="tambah_programkerja" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <label for="InputNamaProgram" class="form-label">Nama Program</label>
                    </div>
                    <div class="col-md-8">
                        <div class="input-group mb-3 submit-select">
                            <select class="form-select text-muted" id="inputGroupSelect01">
                                <option selected>Pilih Nama Program</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label for="InputKodeRKAKL" class="form-label">Kode RKAKL</label>
                    </div>
                    <div class="col-md-8">
                        <div class="input-group mb-3 submit-select">
                            <select class="form-select text-muted" id="inputGroupSelect01">
                                <option selected>Pilih Kode RKAKL</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="InputBiaya" class="form-label">Biaya</label>
                    </div>
                    <div class="col-md-8">
                        <input type="text" class="form-control" id="InputBiaya">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary">Simpan</button>
            </div>
            </div>
        </div>
    </div>
    <!-- Akhir Tambah Program Kerja -->

    <!-- Aawal Edit Program Kerja -->
    <div class="modal fade" id="edit_programkerja" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
          <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Edit Data</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <div class="row">
                  <div class="col-md-4">
                      <label for="InputNamaProgram" class="form-label">Nama Program</label>
                  </div>
                  <div class="col-md-8">
                      <div class="input-group mb-3 submit-select">
                          <select class="form-select text-muted" id="inputGroupSelect01">
                              <option selected>Pilih Nama Program</option>
                          </select>
                      </div>
                  </div>
              </div>
              <div class="row">
                  <div class="col-md-4">
                      <label for="InputKodeRKAKL" class="form-label">Kode RKAKL</label>
                  </div>
                  <div class="col-md-8">
                      <div class="input-group mb-3 submit-select">
                          <select class="form-select text-muted" id="inputGroupSelect01">
                              <option selected>Pilih Kode RKAKL</option>
                          </select>
                      </div>
                  </div>
              </div>
              <div class="row mb-3">
                  <div class="col-md-4">
                      <label for="InputBiaya" class="form-label">Biaya</label>
                  </div>
                  <div class="col-md-8">
                      <input type="text" class="form-control" id="InputBiaya">
                  </div>
              </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
              <button type="button" class="btn btn-primary">Simpan</button>
          </div>
          </div>
      </div>
    </div>
    <!-- Akhir Edit Program Kerja -->

    <!-- Awal Delete Program Kerja -->
    <div id='delete_programkerja' class='modal fade' role='dialog'>
      <div class='modal-dialog'>

        <!-- Modal content-->
        <div class='modal-content'>
          <div class='modal-body text-center'>
            <h5>Apakah Anda yakin?</h5>
          </div>
          <div class="modal-footer justify-content-center">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button href="" type="button" class="btn btn-danger">Hapus</button>
          </div>   
        </div>
      </div>
    </div>
    <!-- Akhir Delete Program Kerja -->

    <script src="./js/bootstrap.bundle.min.js"></script>
    <script src="./js/jquery-3.5.1.js"></script>
    <script src="./js/jquery.dataTables.min.js"></script>
    <script src="./js/dataTables.bootstrap5.min.js"></script>
    <script src="./js/script.js"></script>
  </body>
</html>
