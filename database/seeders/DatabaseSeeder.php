<?php

namespace Database\Seeders;

use App\Models\Ref_sbm;
use App\Models\Ref_rkakl_satker;
use App\Models\Ref_rkakl_program;
use App\Models\Ref_rkakl_kegiatan;
use App\Models\Ref_rkakl_output;
use App\Models\Ref_rkakl_suboutput;
use App\Models\Ref_rkakl_komponen;
use App\Models\Ref_rkakl_sub_komponen;
use App\Models\Akun;
use App\Models\Akun_x_rkakl;
use App\Models\Asset;
use App\Models\User;
use App\Models\Fungsi;
use App\Models\Jabatan;
use App\Models\Pegawai;
use App\Models\Non_pegawai;
use App\Models\Kendaraan;
use App\Models\Administrator;
use App\Models\Versi;
use App\Models\Program_kerja;


// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);


        Versi::create([
            'versi' => '2022',
            'status' => 'non-aktif'
        ]);

        Versi::create([
            'versi' => '2023',
            'status' => 'aktif'
        ]);

        // User::create([
        //     'name' => 'Kemal Ramadhan',
        //     'username' => 'kemal',
        //     'email' => 'km.kemal03@gmail.com',
        //     'password' => bcrypt('123456'),
        // ]);

        Administrator::create([
            'email' => 'km.kemal04@gmail.com',
            'username' => 'Kemal Ramadhan',
            'password' => bcrypt('123456'),
            'role' => 'Master',
        ]);
        Administrator::create([
            'email' => 'km.kemal01@gmail.com',
            'username' => 'Kemal Ramadhan',
            'password' => bcrypt('123456'),
            'role' => 'Bendahara',
        ]);
        Administrator::create([
            'email' => 'km.kemal02@gmail.com',
            'username' => 'Kemal Ramadhan',
            'password' => bcrypt('123456'),
            'role' => 'Keuangan',
        ]);
        Administrator::create([
            'email' => 'km.kemal05@gmail.com',
            'username' => 'Kemal Ramadhan',
            'password' => bcrypt('123456'),
            'role' => 'BMN',
        ]);

        Pegawai::create([
            'NIP_NIK' => '3204320712000008',
            'password' => bcrypt('123456'),
            'nama_lengkap' => 'Kemal Ramadhan',
            'jenis_kelamin' => 'Laki - laki',
            'status' => 'Magang',
            'golongan' => 'viii/a',
            'pangkat' => 'Penata Muda',
            'no_telp' => '08986004677',
            'email' => 'km.kemal03@gmail.com',
            'no_rekening' => '12345678911',
            'is_aktif' => '1',
            'jabatan_id' => '1',
        ]);

        Pegawai::create([
            'NIP_NIK' => '3204320712000009',
            'password' => bcrypt('123456'),
            'nama_lengkap' => 'Gama Kusumah',
            'jenis_kelamin' => 'Laki - laki',
            'status' => 'Magang',
            'golongan' => 'viii/a',
            'pangkat' => 'Penata Muda',
            'no_telp' => '08986004677',
            'email' => 'gama@gmail.com',
            'no_rekening' => '12345678911',
            'is_aktif' => '1',
            'jabatan_id' => '1',
        ]);

        Pegawai::create([
            'NIP_NIK' => '196609141990032001',
            'password' => bcrypt('123456'),
            'nama_lengkap' => 'Cucu Juhaenah',
            'jenis_kelamin' => 'Perempuan',
            'status' => 'PNS',
            'golongan' => 'iii/b',
            'pangkat' => 'Penata Muda Tingkat I',
            'no_telp' => '081320575396',
            'email' => 'juhaenahcucu@gmail.com',
            'no_rekening' => '123343212233',
            'is_aktif' => '1',
            'jabatan_id' => '1',
        ]);

        Pegawai::create([
            'NIP_NIK' => '198202192010121004',
            'password' => bcrypt('123456'),
            'nama_lengkap' => 'Idik Nursidik, S.T.',
            'jenis_kelamin' => 'Laki - Laki',
            'status' => 'PNS',
            'golongan' => 'iii/b',
            'pangkat' => 'Penata Muda Tingkat I',
            'no_telp' => '082115464615',
            'email' => 'idiknursidik@gmail.com',
            'no_rekening' => '213443213355',
            'is_aktif' => '1',
            'jabatan_id' => '2',
        ]);

        Pegawai::create([
            'NIP_NIK' => '198503182009121001',
            'password' => bcrypt('123456'),
            'nama_lengkap' => 'Hedi Naufal, S.Si., M.A.P.',
            'jenis_kelamin' => 'Laki - Laki',
            'status' => 'PNS',
            'golongan' => 'iii/c',
            'pangkat' => 'Penata',
            'no_telp' => '081320435767',
            'email' => 'hedinaufal@gmail.com',
            'no_rekening' => '312345123234',
            'is_aktif' => '1',
            'jabatan_id' => '3',
        ]);

        Pegawai::create([
            'NIP_NIK' => '198807162010122005',
            'password' => bcrypt('123456'),
            'nama_lengkap' => 'Hevy Pratiwi, S.I.Kom.',
            'jenis_kelamin' => 'Perempuan',
            'status' => 'PNS',
            'golongan' => 'iii/c',
            'pangkat' => 'Penata',
            'no_telp' => '081321970306',
            'email' => 'Hevypratiwi@gmail.com',
            'no_rekening' => '451233567432',
            'is_aktif' => '1',
            'jabatan_id' => '4',
        ]);

        Pegawai::create([
            'NIP_NIK' => '196602271986021001',
            'password' => bcrypt('123456'),
            'nama_lengkap' => 'Dedi Setiadi M.',
            'jenis_kelamin' => 'Laki - Laki',
            'status' => 'PNS',
            'golongan' => 'iii/b',
            'pangkat' => 'Penata Muda Tingkat I',
            'no_telp' => '085943488568',
            'email' => 'dedigembit@gmail.com',
            'no_rekening' => '567895234432',
            'is_aktif' => '1',
            'jabatan_id' => '5',
        ]);

        Pegawai::create([
            'NIP_NIK' => '199511262019032018',
            'password' => bcrypt('123456'),
            'nama_lengkap' => 'Ghina Khairiyah Syam, S.Pd.',
            'jenis_kelamin' => 'Perempuan',
            'status' => 'PNS',
            'golongan' => 'iii/a',
            'pangkat' => 'Penata Muda',
            'no_telp' => '085322636677',
            'email' => 'ghinakhairiyahsyam@gmail.com',
            'no_rekening' => '657897456302',
            'is_aktif' => '1',
            'jabatan_id' => '6',
        ]);

        Pegawai::create([
            'NIP_NIK' => '196705151990021003',
            'password' => bcrypt('123456'),
            'nama_lengkap' => 'Nandang Supriatna',
            'jenis_kelamin' => 'Laki - Laki',
            'status' => 'PNS',
            'golongan' => 'iii/b',
            'pangkat' => 'Penata Muda Tingkat I',
            'no_telp' => '081395562732',
            'email' => 'nadangsupriatna67@gmail.com',
            'no_rekening' => '765789943456',
            'is_aktif' => '1',
            'jabatan_id' => '7',
        ]);

        Pegawai::create([
            'NIP_NIK' => '198102252010122001',
            'password' => bcrypt('123456'),
            'nama_lengkap' => 'Yeni Rospiani, S.S',
            'jenis_kelamin' => 'Perempuan',
            'status' => 'PNS',
            'golongan' => 'iii/c',
            'pangkat' => 'Penata',
            'no_telp' => '085220538908',
            'email' => 'yenikopertis@gmail.com',
            'no_rekening' => '889976512345',
            'is_aktif' => '1',
            'jabatan_id' => '8',
        ]);

        Pegawai::create([
            'NIP_NIK' => '198112162010122001',
            'password' => bcrypt('123456'),
            'nama_lengkap' => 'Ewisna Yulius, S.S.',
            'jenis_kelamin' => 'Perempuan',
            'status' => 'PNS',
            'golongan' => 'iii/c',
            'pangkat' => 'Penata',
            'no_telp' => '081220085481',
            'email' => 'nhayuli@gmail.com',
            'no_rekening' => '965784345678',
            'is_aktif' => '1',
            'jabatan_id' => '9',
        ]);

        Pegawai::create([
            'NIP_NIK' => '3174091710740004',
            'password' => bcrypt('123456'),
            'nama_lengkap' => 'Riady Subanar, S.T.',
            'jenis_kelamin' => 'Laki - Laki',
            'status' => 'Outsourcing',
            'golongan' => '-',
            'pangkat' => '-',
            'no_telp' => '087777168771',
            'email' => 'riady12345@gmail.com',
            'no_rekening' => '109876543345',
            'is_aktif' => '1',
            'jabatan_id' => '10',
        ]);

        Fungsi::create([
            'nama_fungsi' => 'Perencanaan, Penganggaran dan BMN',
        ]);

        Fungsi::create([
            'nama_fungsi' => 'Satuan Pengawas Internal',
        ]);

        Fungsi::create([
            'nama_fungsi' => 'Kepegawaian dan Tatalaksana',
        ]);

        Fungsi::create([
            'nama_fungsi' => 'Humas dan Hukum',
        ]);

        Fungsi::create([
            'nama_fungsi' => 'Pendidik dan Tenaga Kependidikan',
        ]);

        Fungsi::create([
            'nama_fungsi' => 'Akademik',
        ]);

        Fungsi::create([
            'nama_fungsi' => 'Kemahasiswaan',
        ]);

        Fungsi::create([
            'nama_fungsi' => 'Kelembagaan',
        ]);

        Fungsi::create([
            'nama_fungsi' => 'Sistem Informasi dan Kerja Sama',
        ]);

        Fungsi::create([
            'nama_fungsi' => 'Support',
        ]);



        Jabatan::create([
            'nama_jabatan' => 'Pengelola Keuangan',
            'fungsi_id' => '1',
        ]);

        Jabatan::create([
            'nama_jabatan' => 'Pengawasan',
            'fungsi_id' => '2',
        ]);

        Jabatan::create([
            'nama_jabatan' => 'Analis Organisasi dan Tata Laksana',
            'fungsi_id' => '3',
        ]);

        Jabatan::create([
            'nama_jabatan' => 'Pengelola Data',
            'fungsi_id' => '4',
        ]);

        Jabatan::create([
            'nama_jabatan' => 'Pengadministrasi Pendidik dan Tenaga Kependidikan',
            'fungsi_id' => '5',
        ]);

        Jabatan::create([
            'nama_jabatan' => 'Analisis Data Akademik',
            'fungsi_id' => '6',
        ]);

        Jabatan::create([
            'nama_jabatan' => 'Pengadministrasi Umum',
            'fungsi_id' => '7',
        ]);

        Jabatan::create([
            'nama_jabatan' => 'Analisi Pengembangan Saranan dan Prasarana',
            'fungsi_id' => '8',
        ]);

        Jabatan::create([
            'nama_jabatan' => 'Pengelola Sistem Informasi',
            'fungsi_id' => '9',
        ]);

        Jabatan::create([
            'nama_jabatan' => 'Pengemudi',
            'fungsi_id' => '10',
        ]);


        Non_pegawai::create([
            'NIP_NIK' => '196805051989031015',
            'nama_lengkap' => 'Ismail bin Mail',
            'golongan' => 'iii/a',
            'pangkat' => 'Penata Muda',
            'alamat' => 'Kp. durian runtuh',
            'email' => 'mailbinmail@gmail.com',
            'no_telp' => '08986004677',
        ]);

        Non_pegawai::create([
            'NIP_NIK' => '19304222011011010',
            'nama_lengkap' => 'Aruffin bin Abdul Salam',
            'golongan' => 'iii/c',
            'pangkat' => 'Penata',
            'alamat' => 'Jl.Nakulo No.2 RT 08 RW 02 Kel.Klegen Kec.Kartoharjo Kota Madiun',
            'email' => 'upin12345@gmail.com',
            'no_telp' => '085655227735',
        ]);

        Non_pegawai::create([
            'NIP_NIK' => '19721020261993031002',
            'nama_lengkap' => 'Arifin bin Abdul Salam',
            'golongan' => 'iii/d',
            'pangkat' => 'Penata Tingkat I',
            'alamat' => 'Perum Widodo Kencana Indah I E/5 Kel. Pandean Kec. Taman Kota Medan',
            'email' => 'ipin12345@gmail.com',
            'no_telp' => '081234620782',
        ]);

        Non_pegawai::create([
            'NIP_NIK' => '321316550888889',
            'nama_lengkap' => 'Jarjit Singh',
            'golongan' => 'iii/d',
            'pangkat' => 'Penata Tingkat I',
            'alamat' => 'Jl. Batu Kuda 19 Bandung',
            'email' => 'jarjithahaha@gmail.com',
            'no_telp' => '083877660921',
        ]);

        Non_pegawai::create([
            'NIP_NIK' => '321316550201027',
            'nama_lengkap' => 'Mohammad Al Hafeezy',
            'golongan' => 'iii/c',
            'pangkat' => 'Penata',
            'alamat' => 'Jl. Rindang No.4 Bandung',
            'email' => 'fizi88@gmail.com',
            'no_telp' => '08986004677',
        ]);

        Non_pegawai::create([
            'NIP_NIK' => '320432071229011',
            'nama_lengkap' => 'Ehsan bin Azzarudin',
            'golongan' => 'iv/',
            'pangkat' => 'Pembina',
            'alamat' => 'Jl.Utama Jakarta',
            'email' => 'ehsan181@gmail.com',
            'no_telp' => '08986004677',
        ]);

        Non_pegawai::create([
            'NIP_NIK' => '198110292010012002',
            'nama_lengkap' => 'Xiao Mei Mei',
            'golongan' => 'iv/e',
            'pangkat' => 'Pembina Utama',
            'alamat' => 'Jl.Danau Toba Blok F/10, Bend.Hilir, Tanah Abang, Jakarta Pusat',
            'email' => 'meimeicantik@gmail.com',
            'no_telp' => '089860010000',
        ]);

        Non_pegawai::create([
            'NIP_NIK' => '198802122011012009',
            'nama_lengkap' => 'Susanti',
            'golongan' => 'iv/b',
            'pangkat' => 'Pembina Tingkat I',
            'alamat' => 'Jln. Petemon IV No.32-A, Kel.Petemon, Kec. Sawahan, Kota Surabaya, Jawa Timur',
            'email' => 'susanti96@gmail.com',
            'no_telp' => '08776007677',
        ]);

        Non_pegawai::create([
            'NIP_NIK' => '198109291992022002',
            'nama_lengkap' => 'Rania Mumtaz',
            'golongan' => 'iii/d',
            'pangkat' => 'Penata Tingkat I',
            'alamat' => 'Jl. Jodhipati No 12, Desa Banyubiru, Kec. Banyubiru Kabupaten Semarangan, Jawa Tengah',
            'email' => 'raniamumtaz17@gmail.com',
            'no_telp' => '08777874618',
        ]);

        Non_pegawai::create([
            'NIP_NIK' => '19660118200121001',
            'nama_lengkap' => 'Arum Sofiana',
            'golongan' => 'iii/a',
            'pangkat' => 'Penata Muda',
            'alamat' => 'Babakan Ciparay, Babakan Ciparay Kota Bandung',
            'email' => 'arumsofiana@gmail.com',
            'no_telp' => '08981006691',
        ]);

        Asset::create([
            'kode_barang' => '3010304003',
            'nama_barang' => 'Stationary Generating Set',
            'NUP' => '1',
            'nama_merek' => 'FOTON ISUZU',
            'tgl_beli' => now(),
            'jenis_perawatan' => 'Berkala',
            'status_kondisi' => 'Baik',
            'status_peminjaman' => 'Tidak Dipakai',
        ]);

        Asset::create([
            'kode_barang' => '3010304999',
            'nama_barang' => 'Electric Generating Set Lainnya',
            'NUP' => '1',
            'nama_merek' => 'General Silent Generator',
            'tgl_beli' => now(),
            'jenis_perawatan' => 'Berkala',
            'status_kondisi' => 'Baik',
            'status_peminjaman' => 'Dipakai'
        ]);

Asset::create([
            'kode_barang' => '3050104001',
            'nama_barang' => 'Lemari Besi/Metal',
            'NUP' => '1',
            'nama_merek' => 'Merk Barata Type B 205',
            'tgl_beli' => now(),
            'jenis_perawatan' => 'Non-berkala',
            'status_kondisi' => 'Kurang Baik',
            'status_peminjaman' => 'Tidak Dipakai'
        ]);

Asset::create([
            'kode_barang' => '3050104001',
            'nama_barang' => 'Lemari Besi/Metal',
            'NUP' => '2',
            'nama_merek' => 'Merk Barata Type B 205',
            'tgl_beli' => now(),
            'jenis_perawatan' => 'Non-berkala',
            'status_kondisi' => 'Rusak',
            'status_peminjaman' => 'Dipakai',
        ]);

Asset::create([
            'kode_barang' => '3050206002',
            'nama_barang' => 'Televisi',
            'NUP' => '1',
            'nama_merek' => 'Panasonic Type THL24C20',
            'tgl_beli' => now(),
            'jenis_perawatan' => 'Berkala',
            'status_kondisi' => 'Baik',
            'status_peminjaman' => 'Tidak Dipakai',
        ]);

Asset::create([
            'kode_barang' => '3050204004',
            'nama_barang' => 'A.C. Split',
            'NUP' => '1',
            'nama_merek' => 'LG TYPE S 18 LCF',
            'tgl_beli' => now(),
            'jenis_perawatan' => 'Berkala',
            'status_kondisi' => 'Baik',
            'status_peminjaman' => 'Dipakai'
        ]);

Asset::create([
            'kode_barang' => '3100102003',
            'nama_barang' => 'Note Book',
            'NUP' => '25',
            'nama_merek' => 'Asus Pro',
            'tgl_beli' => now(),
            'jenis_perawatan' => 'Berkala',
            'status_kondisi' => 'Baik',
            'status_peminjaman' => 'Tidak Dipakai'
        ]);

Asset::create([
            'kode_barang' => '3100102002',
            'nama_barang' => 'Lap Top',
            'NUP' => '1',
            'nama_merek' => 'ASUS Pro P2420LJ-WO0135P',
            'tgl_beli' => now(),
            'jenis_perawatan' => 'Berkala',
            'status_kondisi' => 'Baik',
            'status_peminjaman' => 'Tidak Dipakai'
        ]);

Asset::create([
            'kode_barang' => '3100203004',
            'nama_barang' => 'Scanner',
            'NUP' => '1',
            'nama_merek' => 'Fujitsu ix 1500',
            'tgl_beli' => now(),
            'jenis_perawatan' => 'Berkala',
            'status_kondisi' => 'Baik',
            'status_peminjaman' => 'Tidak Dipakai'
        ]);

Asset::create([
            'kode_barang' => '3060102128',
            'nama_barang' => 'Camera Digital',
            'NUP' => '4',
            'nama_merek' => 'CANON EOS 5D Mark III+Lensa',
            'tgl_beli' => now(),
            'jenis_perawatan' => 'Berkala',
            'status_kondisi' => 'Baik',
            'status_peminjaman' => 'Tidak Dipakai'
        ]);

         // kendaraan
         Kendaraan::create([
            'merek' => 'Avanza',
            'no_polisi' => ' D 1 LLD',
            'no_mesin' => '123321',
            'no_stnk' => '7652876',
            'no_bpkb' => 'Legal',
            'legalitas_5th' => '2027',
            'tipe' => 'Mini Bus',
            'status' => 'Baik',
        ]);

        Kendaraan::create([
            'merek' => 'Inova',
            'no_polisi' => ' D 2 LLD',
            'no_mesin' => '123321',
            'no_stnk' => '7652876',
            'no_bpkb' => 'Legal',
            'legalitas_5th' => '2027',
            'tipe' => 'Mini Bus',
            'status' => 'Baik',
        ]);
        Kendaraan::create([
            'merek' => 'Elp Suzuki',
            'no_polisi' => ' D 4 LLD',
            'no_mesin' => '123321',
            'no_stnk' => '7652876',
            'no_bpkb' => 'Legal',
            'legalitas_5th' => '2027',
            'tipe' => 'Mini Bus',
            'status' => 'Baik',
        ]);

         // satker
         Ref_rkakl_satker::create([
            'kode_satker' => '723012',
            'satker' => 'LEMBAGA LAYANAN PENDIDIKAN TINGGI WILAYAH IV BANDUNG'
        ]);

        // program
        Ref_rkakl_program::create([
            'ref_rkakl_satker_id' => '1',
            'kode_program' => '01.WA',
            'program' => 'Sekertariat Jendral'
        ]);

        Ref_rkakl_program::create([
            'ref_rkakl_satker_id' => '1',
            'kode_program' => '01.DK',
            'program' => 'Program Pendidikan Tinggi'
        ]);

        // kegiatan
        Ref_rkakl_kegiatan::create([
            'ref_rkakl_program_id' => '1',
            'kode_kegiatan' => '4472',
            'nama_kegiatan' => 'Pembinaan Kelembagaan Pendidikan Tinggi'
        ]);

        Ref_rkakl_kegiatan::create([
            'ref_rkakl_program_id' => '2',
            'kode_kegiatan' => '6392',
            'nama_kegiatan' => 'Pengelolaan Lembaga Layanan Penddikan Tinggi'
        ]);

        // output
        Ref_rkakl_output::create([
            'ref_rkakl_kegiatan_id' => '1',
            'kode_output' => 'BDB',
            'nama_output' => 'Fasilitasi dan Pembinaan Lembaga [00]'
        ]);

        Ref_rkakl_output::create([
            'ref_rkakl_kegiatan_id' => '1',
            'kode_output' => 'BEJ',
            'nama_output' => 'Bantuan Pendidikan Tinggi [00]'
        ]);

        Ref_rkakl_output::create([
            'ref_rkakl_kegiatan_id' => '2',
            'kode_output' => 'EBA',
            'nama_output' => 'Layanan Dukungan Manajemen Internal [00]'
        ]);

        Ref_rkakl_output::create([
            'ref_rkakl_kegiatan_id' => '2',
            'kode_output' => 'EBB',
            'nama_output' => 'Layanan Sarana dan Prasarana Internal [00]'
        ]);

        // sub output
        Ref_rkakl_suboutput::create([
            'ref_rkakl_output_id' => '1',
            'kode_sub_output' => '001',
            'nama_sub_output' => 'Lembaga Pendidikan Tinggi Akademik dan Vokasi yang mendapatkan layanan pembinaan peningkatan mutu'
        ]);

        Ref_rkakl_suboutput::create([
            'ref_rkakl_output_id' => '1',
            'kode_sub_output' => '002',
            'nama_sub_output' => 'Lembaga Pendidikan Tinggi Akademik dan Vokasi yang mendapat layanan rekomendasi'
        ]);

        Ref_rkakl_suboutput::create([
            'ref_rkakl_output_id' => '2',
            'kode_sub_output' => '001',
            'nama_sub_output' => 'Dosen Non PNS yang Menerima Tunjangan Profesi'
        ]);

        Ref_rkakl_suboutput::create([
            'ref_rkakl_output_id' => '3',
            'kode_sub_output' => '962',
            'nama_sub_output' => 'Layanan Umum'
        ]);

        Ref_rkakl_suboutput::create([
            'ref_rkakl_output_id' => '3',
            'kode_sub_output' => '994',
            'nama_sub_output' => 'Layanan Perkantoran'
        ]);

        Ref_rkakl_suboutput::create([
            'ref_rkakl_output_id' => '4',
            'kode_sub_output' => '971',
            'nama_sub_output' => 'Layanan Prasarana Internal'
        ]);

        // komponen
        Ref_rkakl_komponen::create([
            'ref_rkakl_suboutput_id' => '1',
            'kode_komponen' => '051',
            'nama_komponen' => 'Pembinaan dan Evaluasi Lapangan Pengendalian Perguruan Tinggi',
        ]);

        Ref_rkakl_komponen::create([
            'ref_rkakl_suboutput_id' => '1',
            'kode_komponen' => '052',
            'nama_komponen' => 'Workshop/Sosialisasi/Bimbingan Teknis Peningkatan Mutu Perguruan Tinggi',
        ]);

        Ref_rkakl_komponen::create([
            'ref_rkakl_suboutput_id' => '1',
            'kode_komponen' => '054',
            'nama_komponen' => 'Fasilitasi Layanan LLDikti',
        ]);

        Ref_rkakl_komponen::create([
            'ref_rkakl_suboutput_id' => '2',
            'kode_komponen' => '053',
            'nama_komponen' => 'Visitasi dan evaluasi Lapangan',
        ]);

        Ref_rkakl_komponen::create([
            'ref_rkakl_suboutput_id' => '3',
            'kode_komponen' => '004',
            'nama_komponen' => 'Dukungan Operasional Penyelenggaraan Pendidikan',
        ]);

        Ref_rkakl_komponen::create([
            'ref_rkakl_suboutput_id' => '4',
            'kode_komponen' => '051',
            'nama_komponen' => 'Umum dan Rumah Tangga Satker',
        ]);

        Ref_rkakl_komponen::create([
            'ref_rkakl_suboutput_id' => '5',
            'kode_komponen' => '001',
            'nama_komponen' => 'Gaji dan Tunjangan',
        ]);

        Ref_rkakl_komponen::create([
            'ref_rkakl_suboutput_id' => '5',
            'kode_komponen' => '002',
            'nama_komponen' => 'Operasional dan Pemeliharaan Kantor',
        ]);

        Ref_rkakl_komponen::create([
            'ref_rkakl_suboutput_id' => '6',
            'kode_komponen' => '998',
            'nama_komponen' => 'Rehab/Renovasi Gedung dan Bangunan',
        ]);

        // sub komponen
        Ref_rkakl_sub_komponen::create([
            'ref_rkakl_komponen_id' => '1',
            'kode_sub_kegiatan' => 'A',
            'nama_sub_kegiatan' => 'Pemantauan, Pendampingan, Pembinaan, dan Evaluasi Perguruan Tinggi'
        ]);

        Ref_rkakl_sub_komponen::create([
            'ref_rkakl_komponen_id' => '2',
            'kode_sub_kegiatan' => 'A',
            'nama_sub_kegiatan' => 'Layanan Peningkatan Mutu PTS (Kelembagaan, Dosen, Tendik, dan Mahasiswa)'
        ]);

        Ref_rkakl_sub_komponen::create([
            'ref_rkakl_komponen_id' => '3',
            'kode_sub_kegiatan' => 'A',
            'nama_sub_kegiatan' => 'Penilaian Jabatan Akademik Dosen'
        ]);

        Ref_rkakl_sub_komponen::create([
            'ref_rkakl_komponen_id' => '3',
            'kode_sub_kegiatan' => 'B',
            'nama_sub_kegiatan' => 'Penguatan Tata Kelola LLDIKTI IV (Evaluasi RBI/ZI, Manajemen Risiko dan Standar Pelayanan Publik)'
        ]);

        Ref_rkakl_sub_komponen::create([
            'ref_rkakl_komponen_id' => '3',
            'kode_sub_kegiatan' => 'C',
            'nama_sub_kegiatan' => 'Koordinasi Pelaksanaan Anggaran'
        ]);

        Ref_rkakl_sub_komponen::create([
            'ref_rkakl_komponen_id' => '4',
            'kode_sub_kegiatan' => 'A',
            'nama_sub_kegiatan' => 'Evaluasi Lapangan Usulan Pendirian PT Perubahan PT dan Pembukaan Prodi pada PT'
        ]);

        Ref_rkakl_sub_komponen::create([
            'ref_rkakl_komponen_id' => '5',
            'kode_sub_kegiatan' => 'A',
            'nama_sub_kegiatan' => 'Tunjangan Sertifikasi Dosen Non PNS'
        ]);

        Ref_rkakl_sub_komponen::create([
            'ref_rkakl_komponen_id' => '6',
            'kode_sub_kegiatan' => 'A',
            'nama_sub_kegiatan' => 'Pengelolaan Jurnal'
        ]);

        Ref_rkakl_sub_komponen::create([
            'ref_rkakl_komponen_id' => '6',
            'kode_sub_kegiatan' => 'B',
            'nama_sub_kegiatan' => 'Pengelolaan Buletin'
        ]);

        Ref_rkakl_sub_komponen::create([
            'ref_rkakl_komponen_id' => '6',
            'kode_sub_kegiatan' => 'C',
            'nama_sub_kegiatan' => 'Pengelolaan Website'
        ]);

        Ref_rkakl_sub_komponen::create([
            'ref_rkakl_komponen_id' => '6',
            'kode_sub_kegiatan' => 'D',
            'nama_sub_kegiatan' => 'Koordinasi dan Evaluasi Pelaksanaan Anggaran'
        ]);

        Ref_rkakl_sub_komponen::create([
            'ref_rkakl_komponen_id' => '7',
            'kode_sub_kegiatan' => 'A',
            'nama_sub_kegiatan' => 'Pembayaran Gaji dan Tunjangan'
        ]);

        Ref_rkakl_sub_komponen::create([
            'ref_rkakl_komponen_id' => '7',
            'kode_sub_kegiatan' => 'B',
            'nama_sub_kegiatan' => 'Pembayaran Tunjangan Sertifikasi Dosen PNS'
        ]);

        Ref_rkakl_sub_komponen::create([
            'ref_rkakl_komponen_id' => '8',
            'kode_sub_kegiatan' => 'A',
            'nama_sub_kegiatan' => 'Honorarium PPNPN  Pengadaan Seragam'
        ]);

        Ref_rkakl_sub_komponen::create([
            'ref_rkakl_komponen_id' => '8',
            'kode_sub_kegiatan' => 'B',
            'nama_sub_kegiatan' => 'Langganan Daya dan Jasa'
        ]);

        Ref_rkakl_sub_komponen::create([
            'ref_rkakl_komponen_id' => '8',
            'kode_sub_kegiatan' => 'C',
            'nama_sub_kegiatan' => 'Penyelenggaraan Operasional dan Pemeliharaan Perkantoran'
        ]);

        Ref_rkakl_sub_komponen::create([
            'ref_rkakl_komponen_id' => '8',
            'kode_sub_kegiatan' => 'D',
            'nama_sub_kegiatan' => 'Perawatan dan Pemeliharaan'
        ]);

        Ref_rkakl_sub_komponen::create([
            'ref_rkakl_komponen_id' => '9',
            'kode_sub_kegiatan' => 'A',
            'nama_sub_kegiatan' => 'Rehabilitasi/Renovasi Gedung dan Bangunan'
        ]);

        Ref_rkakl_sub_komponen::create([
            'ref_rkakl_komponen_id' => '9',
            'kode_sub_kegiatan' => 'B',
            'nama_sub_kegiatan' => 'Pengadaan Laptop CPNS  Paket Broadcast'
        ]);

        // akun
        Akun::create([
            'kode_akun' => '522151',
            'uraian' => 'Belanja Jasa Profesi',
            'nominal' => 48500000
        ]);

        Akun::create([
            'kode_akun' => '524111',
            'uraian' => 'Belanja Perjalanan Dinas Biasa',
            'nominal' => 465260000
        ]);

        Akun::create([
            'kode_akun' => '521211',
            'uraian' => 'Belanja Bahan',
            'nominal' => 321836000
        ]);

        Akun::create([
            'kode_akun' => '521213',
            'uraian' => 'Belanja Honor Output Kegiatan',
            'nominal' => 144300000
        ]);

        Akun::create([
            'kode_akun' => '522151',
            'uraian' => 'Belanja Jasa Profesi',
            'nominal' => 189300000
        ]);

        Akun::create([
            'kode_akun' => '524111',
            'uraian' => 'Belanja Perjalanan Dinas Biasa',
            'nominal' => 245530000
        ]);

        Akun::create([
            'kode_akun' => '524114',
            'uraian' => 'Belanja Perjalanan Dinas Paket Meeting Dalam Kota',
            'nominal' => 570000000
        ]);

        Akun::create([
            'kode_akun' => '524119',
            'uraian' => 'Belanja Perjalanan Dinas Paket Meeting Luar Kota',
            'nominal' => 662454000
        ]);

        Akun::create([
            'kode_akun' => '521211',
            'uraian' => 'Belanja Bahan',
            'nominal' => 900000
        ]);

        Akun::create([
            'kode_akun' => '521213',
            'uraian' => 'Belanja Honor Output Kegiatan',
            'nominal' => 98400000
        ]);

        Akun::create([
            'kode_akun' => '521211',
            'uraian' => 'Belanja Bahan',
            'nominal' => 11400000
        ]);

        Akun::create([
            'kode_akun' => '521213',
            'uraian' => 'Belanja Honor Output Kegiatan',
            'nominal' => 52700000
        ]);

        Akun::create([
            'kode_akun' => '522151',
            'uraian' => 'Belanja Jasa Profesi',
            'nominal' => 73700000
        ]);

        Akun::create([
            'kode_akun' => '524119',
            'uraian' => 'Belanja Perjalanan Dinas Paket Meeting Luar Kota',
            'nominal' => 142500000
        ]);

        Akun::create([
            'kode_akun' => '524111',
            'uraian' => 'Belanja Perjalanan Dinas Biasa',
            'nominal' => 113950000
        ]);

        Akun::create([
            'kode_akun' => '522151',
            'uraian' => 'Belanja Jasa Profesi',
            'nominal' => 90000000
        ]);

        Akun::create([
            'kode_akun' => '524111',
            'uraian' => 'Belanja Perjalanan Dinas Biasa',
            'nominal' => 197400000
        ]);

        Akun::create([
            'kode_akun' => '511521',
            'uraian' => 'Belanja Tunjangan Tenaga Pendidik Non PNS',
            'nominal' => 197400000
        ]);

        Akun::create([
            'kode_akun' => '521211',
            'uraian' => 'Belanja Bahan',
            'nominal' => 69942000
        ]);

        Akun::create([
            'kode_akun' => '521213',
            'uraian' => 'Belanja Honor Output Kegiatan',
            'nominal' => 50940000
        ]);

        Akun::create([
            'kode_akun' => '521211',
            'uraian' => 'Belanja Bahan',
            'nominal' => 45000000
        ]);

        Akun::create([
            'kode_akun' => '521213',
            'uraian' => 'Belanja Honor Output Kegiatan',
            'nominal' => 56820000
        ]);

        Akun::create([
            'kode_akun' => '521213',
            'uraian' => 'Belanja Honor Output Kegiatan',
            'nominal' => 43100000
        ]);

        Akun::create([
            'kode_akun' => '524111',
            'uraian' => 'Belanja Perjalanan Dinas Biasa',
            'nominal' => 472140000
        ]);

        Akun::create([
            'kode_akun' => '511111',
            'uraian' => 'Belanja Gaji Pokok PNS',
            'nominal' => 465600380
        ]);

        Akun::create([
            'kode_akun' => '511119',
            'uraian' => 'Belanja Pembulatan Gaji PNS',
            'nominal' => 511000
        ]);

        Akun::create([
            'kode_akun' => '511121',
            'uraian' => 'Belanja Tunj. Suami/Istri PNS',
            'nominal' => 3362226000
        ]);

        Akun::create([
            'kode_akun' => '511122',
            'uraian' => 'Belanja Tunj. Anak PNS',
            'nominal' => 562184000
        ]);

        Akun::create([
            'kode_akun' => '511123',
            'uraian' => 'Belanja Tunj. Struktural PNS',
            'nominal' => 67140000
        ]);

        Akun::create([
            'kode_akun' => '511124',
            'uraian' => 'Belanja Tunj. Fungsional PNS',
            'nominal' => 7729550000
        ]);

        Akun::create([
            'kode_akun' => '511125',
            'uraian' => 'Belanja Tunj. PPh PNS',
            'nominal' => 536282000
        ]);

        Akun::create([
            'kode_akun' => '511126',
            'uraian' => 'Belanja Tunj. Beras PNS',
            'nominal' => 4566408000
        ]);

        Akun::create([
            'kode_akun' => '511129',
            'uraian' => 'Belanja Uang Makan PNS',
            'nominal' => 7099488000
        ]);

        Akun::create([
            'kode_akun' => '511151',
            'uraian' => 'Belanja Tunjangan Umum PNS',
            'nominal' => 149100000
        ]);

        Akun::create([
            'kode_akun' => '512211',
            'uraian' => 'Belanja Uang Lembur',
            'nominal' => 82575000
        ]);

        // akun x rkakl
        Akun_x_rkakl::create([
            'akun_id' => '1',
            'ref_sub_komponen_id' => '1'
        ]);

        Akun_x_rkakl::create([
            'akun_id' => '2',
            'ref_sub_komponen_id' => '1'
        ]);

        Akun_x_rkakl::create([
            'akun_id' => '3',
            'ref_sub_komponen_id' => '2'
        ]);

        Akun_x_rkakl::create([
            'akun_id' => '4',
            'ref_sub_komponen_id' => '2'
        ]);

        Akun_x_rkakl::create([
            'akun_id' => '5',
            'ref_sub_komponen_id' => '2'
        ]);

        Akun_x_rkakl::create([
            'akun_id' => '6',
            'ref_sub_komponen_id' => '2'
        ]);

        Akun_x_rkakl::create([
            'akun_id' => '7',
            'ref_sub_komponen_id' => '2'
        ]);

        Akun_x_rkakl::create([
            'akun_id' => '8',
            'ref_sub_komponen_id' => '2'
        ]);

        Akun_x_rkakl::create([
            'akun_id' => '9',
            'ref_sub_komponen_id' => '3'
        ]);

        Akun_x_rkakl::create([
            'akun_id' => '10',
            'ref_sub_komponen_id' => '3'
        ]);

        Akun_x_rkakl::create([
            'akun_id' => '11',
            'ref_sub_komponen_id' => '4'
        ]);

        Akun_x_rkakl::create([
            'akun_id' => '12',
            'ref_sub_komponen_id' => '4'
        ]);

        Akun_x_rkakl::create([
            'akun_id' => '13',
            'ref_sub_komponen_id' => '4'
        ]);

        Akun_x_rkakl::create([
            'akun_id' => '14',
            'ref_sub_komponen_id' => '4'
        ]);

        Akun_x_rkakl::create([
            'akun_id' => '15',
            'ref_sub_komponen_id' => '5'
        ]);

        Akun_x_rkakl::create([
            'akun_id' => '16',
            'ref_sub_komponen_id' => '6'
        ]);

        Akun_x_rkakl::create([
            'akun_id' => '17',
            'ref_sub_komponen_id' => '6'
        ]);

        Akun_x_rkakl::create([
            'akun_id' => '18',
            'ref_sub_komponen_id' => '7'
        ]);

        Akun_x_rkakl::create([
            'akun_id' => '19',
            'ref_sub_komponen_id' => '8'
        ]);

        Akun_x_rkakl::create([
            'akun_id' => '20',
            'ref_sub_komponen_id' => '8'
        ]);

        Akun_x_rkakl::create([
            'akun_id' => '21',
            'ref_sub_komponen_id' => '9'
        ]);

        Akun_x_rkakl::create([
            'akun_id' => '22',
            'ref_sub_komponen_id' => '9'
        ]);

        Akun_x_rkakl::create([
            'akun_id' => '23',
            'ref_sub_komponen_id' => '10'
        ]);

        Akun_x_rkakl::create([
            'akun_id' => '24',
            'ref_sub_komponen_id' => '11'
        ]);

        Akun_x_rkakl::create([
            'akun_id' => '25',
            'ref_sub_komponen_id' => '12'
        ]);

        Akun_x_rkakl::create([
            'akun_id' => '26',
            'ref_sub_komponen_id' => '12'
        ]);

        Akun_x_rkakl::create([
            'akun_id' => '27',
            'ref_sub_komponen_id' => '12'
        ]);

        Akun_x_rkakl::create([
            'akun_id' => '28',
            'ref_sub_komponen_id' => '12'
        ]);

        Akun_x_rkakl::create([
            'akun_id' => '29',
            'ref_sub_komponen_id' => '12'
        ]);

        Akun_x_rkakl::create([
            'akun_id' => '30',
            'ref_sub_komponen_id' => '12'
        ]);

        Akun_x_rkakl::create([
            'akun_id' => '31',
            'ref_sub_komponen_id' => '12'
        ]);

        Akun_x_rkakl::create([
            'akun_id' => '32',
            'ref_sub_komponen_id' => '12'
        ]);

        // sbm
        Ref_sbm::create([
            'kode_sbm' => '11.1.a',
            'uraian' => 'Honorarium Narasumber Menteri/Pejabat Setingkat Menteri/Pejabat Negara Lainnya/ yang disetarakan',
            'satuan' => 'OJ',
            'biaya' => 1700000
        ]);

        Ref_sbm::create([
            'kode_sbm' => '11.1.b',
            'uraian' => 'Honorarium Narasumber Pejabat Eselon I/yang disetarakan',
            'satuan' => 'OJ',
            'biaya' => 1400000
        ]);

        Ref_sbm::create([
            'kode_sbm' => '11.2',
            'uraian' => 'Honorarium Moderator',
            'satuan' => 'Orang/ Kali ',
            'biaya' => 700000
        ]);

        Ref_sbm::create([
            'kode_sbm' => '11.3',
            'uraian' => 'Honorarium Pembawa Acara',
            'satuan' => 'OK',
            'biaya' => 400000
        ]);

        Ref_sbm::create([
            'kode_sbm' => '29',
            'uraian' => 'HONORARIUM SATPAM, PENGEMUDI, PETUGAS KEBERSIHAN, DAN PRAMUBAKTI JAWA BARAT',
            'satuan' => 'OB',
            'biaya' => 3777000
        ]);

    }
}