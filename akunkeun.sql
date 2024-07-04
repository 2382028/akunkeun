-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 07 Jun 2023 pada 10.59
-- Versi server: 10.4.27-MariaDB
-- Versi PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `akunkeun`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `administrators`
--

CREATE TABLE `administrators` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `administrators`
--

INSERT INTO `administrators` (`id`, `email`, `username`, `password`, `role`, `created_at`, `updated_at`) VALUES
(1, 'km.kemal04@gmail.com', 'Kemal Ramadhan', '$2y$10$RLB6CFFP73xEnKoe3ILGnOp/nDPwwIr49n8EiS/6tQ/plQ2pb4Hwm', 'Master', '2023-06-07 01:56:07', '2023-06-07 01:56:07'),
(2, 'km.kemal01@gmail.com', 'Kemal Ramadhan', '$2y$10$Xk8A9/UX8IybmBqj/u1vKuRmSexJXgsZIdYXGlH76uj5tPLLPqgV.', 'Bendahara', '2023-06-07 01:56:08', '2023-06-07 01:56:08'),
(3, 'km.kemal02@gmail.com', 'Kemal Ramadhan', '$2y$10$/Q639jqTCMA9/yaSXg0RfeziUNhSQDQknfhkgwlufVy/bFGGF8f8.', 'Keuangan', '2023-06-07 01:56:08', '2023-06-07 01:56:08'),
(4, 'km.kemal05@gmail.com', 'Kemal Ramadhan', '$2y$10$jpl72xNxEnyURYNjagVExeWKhlx2GbzimuKG8nvwaLMrWVSlkRxYy', 'BMN', '2023-06-07 01:56:08', '2023-06-07 01:56:08');

-- --------------------------------------------------------

--
-- Struktur dari tabel `akuns`
--

CREATE TABLE `akuns` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode_akun` varchar(255) NOT NULL,
  `uraian` varchar(255) NOT NULL,
  `nominal` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `akuns`
--

INSERT INTO `akuns` (`id`, `kode_akun`, `uraian`, `nominal`, `created_at`, `updated_at`) VALUES
(1, '522151', 'Belanja Jasa Profesi', 48500000, '2023-06-07 01:56:21', '2023-06-07 01:56:21'),
(2, '524111', 'Belanja Perjalanan Dinas Biasa', 465260000, '2023-06-07 01:56:21', '2023-06-07 01:56:21'),
(3, '521211', 'Belanja Bahan', 321836000, '2023-06-07 01:56:21', '2023-06-07 01:56:21'),
(4, '521213', 'Belanja Honor Output Kegiatan', 144300000, '2023-06-07 01:56:21', '2023-06-07 01:56:21'),
(5, '522151', 'Belanja Jasa Profesi', 189300000, '2023-06-07 01:56:22', '2023-06-07 01:56:22'),
(6, '524111', 'Belanja Perjalanan Dinas Biasa', 245530000, '2023-06-07 01:56:22', '2023-06-07 01:56:22'),
(7, '524114', 'Belanja Perjalanan Dinas Paket Meeting Dalam Kota', 570000000, '2023-06-07 01:56:22', '2023-06-07 01:56:22'),
(8, '524119', 'Belanja Perjalanan Dinas Paket Meeting Luar Kota', 662454000, '2023-06-07 01:56:22', '2023-06-07 01:56:22'),
(9, '521211', 'Belanja Bahan', 900000, '2023-06-07 01:56:22', '2023-06-07 01:56:22'),
(10, '521213', 'Belanja Honor Output Kegiatan', 98400000, '2023-06-07 01:56:22', '2023-06-07 01:56:22'),
(11, '521211', 'Belanja Bahan', 11400000, '2023-06-07 01:56:23', '2023-06-07 01:56:23'),
(12, '521213', 'Belanja Honor Output Kegiatan', 52700000, '2023-06-07 01:56:23', '2023-06-07 01:56:23'),
(13, '522151', 'Belanja Jasa Profesi', 73700000, '2023-06-07 01:56:23', '2023-06-07 01:56:23'),
(14, '524119', 'Belanja Perjalanan Dinas Paket Meeting Luar Kota', 142500000, '2023-06-07 01:56:23', '2023-06-07 01:56:23'),
(15, '524111', 'Belanja Perjalanan Dinas Biasa', 113950000, '2023-06-07 01:56:23', '2023-06-07 01:56:23'),
(16, '522151', 'Belanja Jasa Profesi', 90000000, '2023-06-07 01:56:23', '2023-06-07 01:56:23'),
(17, '524111', 'Belanja Perjalanan Dinas Biasa', 197400000, '2023-06-07 01:56:23', '2023-06-07 01:56:23'),
(18, '511521', 'Belanja Tunjangan Tenaga Pendidik Non PNS', 197400000, '2023-06-07 01:56:23', '2023-06-07 01:56:23'),
(19, '521211', 'Belanja Bahan', 69942000, '2023-06-07 01:56:24', '2023-06-07 01:56:24'),
(20, '521213', 'Belanja Honor Output Kegiatan', 50940000, '2023-06-07 01:56:24', '2023-06-07 01:56:24'),
(21, '521211', 'Belanja Bahan', 45000000, '2023-06-07 01:56:24', '2023-06-07 01:56:24'),
(22, '521213', 'Belanja Honor Output Kegiatan', 56820000, '2023-06-07 01:56:24', '2023-06-07 01:56:24'),
(23, '521213', 'Belanja Honor Output Kegiatan', 43100000, '2023-06-07 01:56:24', '2023-06-07 01:56:24'),
(24, '524111', 'Belanja Perjalanan Dinas Biasa', 472140000, '2023-06-07 01:56:24', '2023-06-07 01:56:24'),
(25, '511111', 'Belanja Gaji Pokok PNS', 465600380, '2023-06-07 01:56:24', '2023-06-07 01:56:24'),
(26, '511119', 'Belanja Pembulatan Gaji PNS', 511000, '2023-06-07 01:56:25', '2023-06-07 01:56:25'),
(27, '511121', 'Belanja Tunj. Suami/Istri PNS', 3362226000, '2023-06-07 01:56:25', '2023-06-07 01:56:25'),
(28, '511122', 'Belanja Tunj. Anak PNS', 562184000, '2023-06-07 01:56:25', '2023-06-07 01:56:25'),
(29, '511123', 'Belanja Tunj. Struktural PNS', 67140000, '2023-06-07 01:56:25', '2023-06-07 01:56:25'),
(30, '511124', 'Belanja Tunj. Fungsional PNS', 7729550000, '2023-06-07 01:56:25', '2023-06-07 01:56:25'),
(31, '511125', 'Belanja Tunj. PPh PNS', 536282000, '2023-06-07 01:56:25', '2023-06-07 01:56:25'),
(32, '511126', 'Belanja Tunj. Beras PNS', 4566408000, '2023-06-07 01:56:25', '2023-06-07 01:56:25'),
(33, '511129', 'Belanja Uang Makan PNS', 7099488000, '2023-06-07 01:56:25', '2023-06-07 01:56:25'),
(34, '511151', 'Belanja Tunjangan Umum PNS', 149100000, '2023-06-07 01:56:25', '2023-06-07 01:56:25'),
(35, '512211', 'Belanja Uang Lembur', 82575000, '2023-06-07 01:56:25', '2023-06-07 01:56:25');

-- --------------------------------------------------------

--
-- Struktur dari tabel `akun_x_rkakls`
--

CREATE TABLE `akun_x_rkakls` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `akun_id` bigint(20) UNSIGNED NOT NULL,
  `ref_sub_komponen_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `akun_x_rkakls`
--

INSERT INTO `akun_x_rkakls` (`id`, `akun_id`, `ref_sub_komponen_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2023-06-07 01:56:25', '2023-06-07 01:56:25'),
(2, 2, 1, '2023-06-07 01:56:26', '2023-06-07 01:56:26'),
(3, 3, 2, '2023-06-07 01:56:26', '2023-06-07 01:56:26'),
(4, 4, 2, '2023-06-07 01:56:26', '2023-06-07 01:56:26'),
(5, 5, 2, '2023-06-07 01:56:26', '2023-06-07 01:56:26'),
(6, 6, 2, '2023-06-07 01:56:26', '2023-06-07 01:56:26'),
(7, 7, 2, '2023-06-07 01:56:26', '2023-06-07 01:56:26'),
(8, 8, 2, '2023-06-07 01:56:26', '2023-06-07 01:56:26'),
(9, 9, 3, '2023-06-07 01:56:26', '2023-06-07 01:56:26'),
(10, 10, 3, '2023-06-07 01:56:26', '2023-06-07 01:56:26'),
(11, 11, 4, '2023-06-07 01:56:26', '2023-06-07 01:56:26'),
(12, 12, 4, '2023-06-07 01:56:26', '2023-06-07 01:56:26'),
(13, 13, 4, '2023-06-07 01:56:26', '2023-06-07 01:56:26'),
(14, 14, 4, '2023-06-07 01:56:26', '2023-06-07 01:56:26'),
(15, 15, 5, '2023-06-07 01:56:26', '2023-06-07 01:56:26'),
(16, 16, 6, '2023-06-07 01:56:27', '2023-06-07 01:56:27'),
(17, 17, 6, '2023-06-07 01:56:27', '2023-06-07 01:56:27'),
(18, 18, 7, '2023-06-07 01:56:27', '2023-06-07 01:56:27'),
(19, 19, 8, '2023-06-07 01:56:27', '2023-06-07 01:56:27'),
(20, 20, 8, '2023-06-07 01:56:27', '2023-06-07 01:56:27'),
(21, 21, 9, '2023-06-07 01:56:27', '2023-06-07 01:56:27'),
(22, 22, 9, '2023-06-07 01:56:27', '2023-06-07 01:56:27'),
(23, 23, 10, '2023-06-07 01:56:27', '2023-06-07 01:56:27'),
(24, 24, 11, '2023-06-07 01:56:27', '2023-06-07 01:56:27'),
(25, 25, 12, '2023-06-07 01:56:27', '2023-06-07 01:56:27'),
(26, 26, 12, '2023-06-07 01:56:27', '2023-06-07 01:56:27'),
(27, 27, 12, '2023-06-07 01:56:27', '2023-06-07 01:56:27'),
(28, 28, 12, '2023-06-07 01:56:27', '2023-06-07 01:56:27'),
(29, 29, 12, '2023-06-07 01:56:28', '2023-06-07 01:56:28'),
(30, 30, 12, '2023-06-07 01:56:28', '2023-06-07 01:56:28'),
(31, 31, 12, '2023-06-07 01:56:28', '2023-06-07 01:56:28'),
(32, 32, 12, '2023-06-07 01:56:28', '2023-06-07 01:56:28');

-- --------------------------------------------------------

--
-- Struktur dari tabel `assets`
--

CREATE TABLE `assets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode_barang` varchar(255) DEFAULT NULL,
  `nama_barang` varchar(255) DEFAULT NULL,
  `NUP` varchar(255) DEFAULT NULL,
  `nama_merek` varchar(255) DEFAULT NULL,
  `tgl_beli` date DEFAULT NULL,
  `jenis_perawatan` varchar(255) DEFAULT NULL,
  `status_kondisi` varchar(255) DEFAULT NULL,
  `status_peminjaman` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `assets`
--

INSERT INTO `assets` (`id`, `kode_barang`, `nama_barang`, `NUP`, `nama_merek`, `tgl_beli`, `jenis_perawatan`, `status_kondisi`, `status_peminjaman`, `created_at`, `updated_at`) VALUES
(1, '3010304003', 'Stationary Generating Set', '1', 'FOTON ISUZU', '2023-06-07', 'Berkala', 'Baik', 'Dipakai', '2023-06-07 01:56:13', '2023-06-07 01:56:13'),
(2, '3010304999', 'Electric Generating Set Lainnya', '1', 'General Silent Generator', '2023-06-07', 'Berkala', 'Baik', 'Dipakai', '2023-06-07 01:56:13', '2023-06-07 01:56:13'),
(3, '3050104001', 'Lemari Besi/Metal', '1', 'Merk Barata Type B 205', '2023-06-07', 'Non-berkala', 'Kurang Baik', 'Tidak Dipakai', '2023-06-07 01:56:13', '2023-06-07 01:56:13'),
(4, '3050104001', 'Lemari Besi/Metal', '2', 'Merk Barata Type B 205', '2023-06-07', 'Non-berkala', 'Rusak', 'Tidak Dipakai', '2023-06-07 01:56:13', '2023-06-07 01:56:13'),
(5, '3050206002', 'Televisi', '1', 'Panasonic Type THL24C20', '2023-06-07', 'Berkala', 'Baik', 'Tidak Dipakai', '2023-06-07 01:56:14', '2023-06-07 01:56:14'),
(6, '3050204004', 'A.C. Split', '1', 'LG TYPE S 18 LCF', '2023-06-07', 'Berkala', 'Baik', 'Dipakai', '2023-06-07 01:56:14', '2023-06-07 01:56:14'),
(7, '3100102003', 'Note Book', '25', 'Asus Pro', '2023-06-07', 'Berkala', 'Baik', 'Dipakai', '2023-06-07 01:56:14', '2023-06-07 01:56:14'),
(8, '3100102002', 'Lap Top', '1', 'ASUS Pro P2420LJ-WO0135P', '2023-06-07', 'Berkala', 'Baik', 'Tidak Dipakai', '2023-06-07 01:56:14', '2023-06-07 01:56:14'),
(9, '3100203004', 'Scanner', '1', 'Fujitsu ix 1500', '2023-06-07', 'Berkala', 'Baik', 'Dipakai', '2023-06-07 01:56:14', '2023-06-07 01:56:14'),
(10, '3060102128', 'Camera Digital', '4', 'CANON EOS 5D Mark III+Lensa', '2023-06-07', 'Berkala', 'Baik', 'Tidak Dipakai', '2023-06-07 01:56:14', '2023-06-07 01:56:14');

-- --------------------------------------------------------

--
-- Struktur dari tabel `data_penanggungjawabs`
--

CREATE TABLE `data_penanggungjawabs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tgl_mulai_digunakan` datetime NOT NULL,
  `tgl_selesai` datetime DEFAULT NULL,
  `asset_id` bigint(20) UNSIGNED NOT NULL,
  `pegawai_id` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `data_penyedias`
--

CREATE TABLE `data_penyedias` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `NPWP` varchar(255) DEFAULT NULL,
  `nama_CV` varchar(255) NOT NULL,
  `penanggung_jawab` varchar(255) NOT NULL,
  `jabatan` varchar(255) NOT NULL,
  `no_telp` varchar(255) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `kategori` varchar(255) NOT NULL,
  `tahun` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `data_perjadinkegiatans`
--

CREATE TABLE `data_perjadinkegiatans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_iku` varchar(255) DEFAULT NULL,
  `uraian` varchar(255) DEFAULT NULL,
  `program_kerja` varchar(255) DEFAULT NULL,
  `nama_kegiatan` varchar(255) NOT NULL,
  `jenis_kegiatan` varchar(255) DEFAULT NULL,
  `jumlah_peserta` varchar(255) DEFAULT NULL,
  `tgl_mulai` datetime DEFAULT NULL,
  `tgl_selesai` datetime DEFAULT NULL,
  `provinsi` varchar(255) DEFAULT NULL,
  `kab_kota` varchar(255) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL,
  `program_kerja_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_acceptBMN` varchar(255) DEFAULT NULL,
  `is_acceptKeu` varchar(255) DEFAULT NULL,
  `is_acceptBend` varchar(255) DEFAULT NULL,
  `admin_BMN` bigint(20) UNSIGNED DEFAULT NULL,
  `admin_Keu` bigint(20) UNSIGNED DEFAULT NULL,
  `admin_Bend` bigint(20) UNSIGNED DEFAULT NULL,
  `versi_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `data_perjadinlangsungs`
--

CREATE TABLE `data_perjadinlangsungs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `status_pegawai` varchar(255) NOT NULL,
  `info_perjadinlangsung` bigint(20) UNSIGNED NOT NULL,
  `pegawai_id` bigint(20) UNSIGNED DEFAULT NULL,
  `non_pegawai_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status_persetujuan` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `dokumens`
--

CREATE TABLE `dokumens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `info_perjadinlangsung_id` bigint(20) UNSIGNED NOT NULL,
  `surat_undangan` varchar(255) DEFAULT NULL,
  `surat_tugas` varchar(255) DEFAULT NULL,
  `SPPD` varchar(255) DEFAULT NULL,
  `lap_perjadin` varchar(255) DEFAULT NULL,
  `lap_BBM` varchar(255) DEFAULT NULL,
  `lap_tol` varchar(255) DEFAULT NULL,
  `status_persetujuan` varchar(255) NOT NULL,
  `ket` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `dokumen_permohonans`
--

CREATE TABLE `dokumen_permohonans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_id` bigint(20) UNSIGNED NOT NULL,
  `nama_dokumen` varchar(255) NOT NULL,
  `file` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `fasilitas`
--

CREATE TABLE `fasilitas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `data_perjadinkegiatan_id` bigint(20) UNSIGNED NOT NULL,
  `nama_fasilitas` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `fungsis`
--

CREATE TABLE `fungsis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_fungsi` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `fungsis`
--

INSERT INTO `fungsis` (`id`, `nama_fungsi`, `created_at`, `updated_at`) VALUES
(1, 'Perencanaan, Penganggaran dan BMN', '2023-06-07 01:56:10', '2023-06-07 01:56:10'),
(2, 'Satuan Pengawas Internal', '2023-06-07 01:56:10', '2023-06-07 01:56:10'),
(3, 'Kepegawaian dan Tatalaksana', '2023-06-07 01:56:11', '2023-06-07 01:56:11'),
(4, 'Humas dan Hukum', '2023-06-07 01:56:11', '2023-06-07 01:56:11'),
(5, 'Pendidik dan Tenaga Kependidikan', '2023-06-07 01:56:11', '2023-06-07 01:56:11'),
(6, 'Akademik', '2023-06-07 01:56:11', '2023-06-07 01:56:11'),
(7, 'Kemahasiswaan', '2023-06-07 01:56:11', '2023-06-07 01:56:11'),
(8, 'Kelembagaan', '2023-06-07 01:56:11', '2023-06-07 01:56:11'),
(9, 'Sistem Informasi dan Kerja Sama', '2023-06-07 01:56:11', '2023-06-07 01:56:11'),
(10, 'Support', '2023-06-07 01:56:11', '2023-06-07 01:56:11');

-- --------------------------------------------------------

--
-- Struktur dari tabel `info_perjadinlangsungs`
--

CREATE TABLE `info_perjadinlangsungs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_kegiatan` varchar(255) NOT NULL,
  `tgl_mulai` datetime NOT NULL,
  `tgl_selesai` datetime NOT NULL,
  `tgl_keberangkatan` datetime NOT NULL,
  `tgl_kepulangan` datetime DEFAULT NULL,
  `provinsi` varchar(255) NOT NULL,
  `kabupaten_kota` varchar(255) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `status_pengajuan` varchar(255) DEFAULT NULL,
  `is_acceptBMN` varchar(255) DEFAULT NULL,
  `is_acceptKeu` varchar(255) DEFAULT NULL,
  `is_acceptBend` varchar(255) DEFAULT NULL,
  `jenis_kegiatan` varchar(255) DEFAULT NULL,
  `admin_BMN` bigint(20) UNSIGNED DEFAULT NULL,
  `admin_Keu` bigint(20) UNSIGNED DEFAULT NULL,
  `admin_Bend` bigint(20) UNSIGNED DEFAULT NULL,
  `versi_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jabatans`
--

CREATE TABLE `jabatans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_jabatan` varchar(255) NOT NULL,
  `fungsi_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `jabatans`
--

INSERT INTO `jabatans` (`id`, `nama_jabatan`, `fungsi_id`, `created_at`, `updated_at`) VALUES
(1, 'Pengelola Keuangan', 1, '2023-06-07 01:56:11', '2023-06-07 01:56:11'),
(2, 'Pengawasan', 2, '2023-06-07 01:56:11', '2023-06-07 01:56:11'),
(3, 'Analis Organisasi dan Tata Laksana', 3, '2023-06-07 01:56:11', '2023-06-07 01:56:11'),
(4, 'Pengelola Data', 4, '2023-06-07 01:56:12', '2023-06-07 01:56:12'),
(5, 'Pengadministrasi Pendidik dan Tenaga Kependidikan', 5, '2023-06-07 01:56:12', '2023-06-07 01:56:12'),
(6, 'Analisis Data Akademik', 6, '2023-06-07 01:56:12', '2023-06-07 01:56:12'),
(7, 'Pengadministrasi Umum', 7, '2023-06-07 01:56:12', '2023-06-07 01:56:12'),
(8, 'Analisi Pengembangan Saranan dan Prasarana', 8, '2023-06-07 01:56:12', '2023-06-07 01:56:12'),
(9, 'Pengelola Sistem Informasi', 9, '2023-06-07 01:56:12', '2023-06-07 01:56:12'),
(10, 'Pengemudi', 10, '2023-06-07 01:56:12', '2023-06-07 01:56:12');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kebutuhans`
--

CREATE TABLE `kebutuhans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `jumlah_frekuensi` int(11) NOT NULL,
  `satuan` int(11) DEFAULT NULL,
  `detail_satuan` varchar(255) DEFAULT NULL,
  `ket` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kendaraans`
--

CREATE TABLE `kendaraans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `merek` varchar(255) DEFAULT NULL,
  `no_polisi` varchar(255) DEFAULT NULL,
  `no_mesin` varchar(255) DEFAULT NULL,
  `no_stnk` varchar(255) DEFAULT NULL,
  `no_bpkb` varchar(255) DEFAULT NULL,
  `legalitas` varchar(255) DEFAULT NULL,
  `legalitas_5th` varchar(255) DEFAULT NULL,
  `tipe` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `kendaraans`
--

INSERT INTO `kendaraans` (`id`, `merek`, `no_polisi`, `no_mesin`, `no_stnk`, `no_bpkb`, `legalitas`, `legalitas_5th`, `tipe`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Avanza', ' D 1 LLD', '123321', '7652876', 'Legal', NULL, '2027', 'Mini Bus', 'Baik', '2023-06-07 01:56:14', '2023-06-07 01:56:14'),
(2, 'Inova', ' D 2 LLD', '123321', '7652876', 'Legal', NULL, '2027', 'Mini Bus', 'Baik', '2023-06-07 01:56:14', '2023-06-07 01:56:14'),
(3, 'Elp Suzuki', ' D 4 LLD', '123321', '7652876', 'Legal', NULL, '2027', 'Mini Bus', 'Baik', '2023-06-07 01:56:14', '2023-06-07 01:56:14');

-- --------------------------------------------------------

--
-- Struktur dari tabel `keuangan_perjadinkegiatans`
--

CREATE TABLE `keuangan_perjadinkegiatans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `harga` int(11) DEFAULT NULL,
  `persen_pajak` int(11) DEFAULT NULL,
  `jumlah_harga` int(11) DEFAULT NULL,
  `data_perjadinkegiatan` bigint(20) UNSIGNED DEFAULT NULL,
  `perangkat_acara` bigint(20) UNSIGNED DEFAULT NULL,
  `operasional` bigint(20) UNSIGNED DEFAULT NULL,
  `ref_sbm` bigint(20) UNSIGNED DEFAULT NULL,
  `akun_x_rkakl` bigint(20) UNSIGNED DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `keuangan_perjadinlangsungs`
--

CREATE TABLE `keuangan_perjadinlangsungs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `harga` int(11) DEFAULT NULL,
  `persen_pajak` int(11) DEFAULT NULL,
  `jumlah_harga` int(11) DEFAULT NULL,
  `uang_rep` int(11) DEFAULT NULL,
  `info_perjadinlangsung` bigint(20) UNSIGNED DEFAULT NULL,
  `data_perjadinlangsungs` bigint(20) UNSIGNED DEFAULT NULL,
  `kebutuhan_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ref_sbm` bigint(20) UNSIGNED DEFAULT NULL,
  `akun_x_rkakl` bigint(20) UNSIGNED DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `komponen_diperlukans`
--

CREATE TABLE `komponen_diperlukans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `frekuensi` int(11) NOT NULL,
  `permohonan_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `laporan_perjadinkegiatans`
--

CREATE TABLE `laporan_perjadinkegiatans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_dokumen` varchar(255) NOT NULL,
  `file` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `data_perjadin_kegiatan` bigint(20) UNSIGNED NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_administrators_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2023_03_29_064724_create_pegawais_table', 1),
(6, '2023_03_29_070545_create_fungsis_table', 1),
(7, '2023_03_29_070649_create_jabatans_table', 1),
(8, '2023_03_29_071733_create_non_pegawais_table', 1),
(9, '2023_03_29_073040_create_ref_sbms_table', 1),
(10, '2023_03_29_073415_create_ref_rkakl_satkers_table', 1),
(11, '2023_03_29_073519_create_ref_rkakl_kegiatans_table', 1),
(12, '2023_03_29_073748_create_ref_rkakl_outputs_table', 1),
(13, '2023_03_29_073944_create_ref_rkakl_suboutputs_table', 1),
(14, '2023_03_29_074104_create_ref_rkakl_komponens_table', 1),
(15, '2023_03_29_074206_create_ref_rkakl_sub_komponens_table', 1),
(16, '2023_03_29_074313_create_akuns_table', 1),
(17, '2023_03_29_074451_create_akun_x_rkakls_table', 1),
(18, '2023_03_29_075056_create_program_kerjas_table', 1),
(19, '2023_03_29_075304_create_kendaraans_table', 1),
(20, '2023_03_29_075502_create_assets_table', 1),
(21, '2023_03_29_075908_create_ruangans_table', 1),
(22, '2023_03_29_080254_create_info_perjadinlangsungs_table', 1),
(23, '2023_03_30_012013_create_dokumens_table', 1),
(24, '2023_03_30_012301_create_fasilitass_table', 1),
(25, '2023_03_30_012336_create_komponen_diperlukans_table', 1),
(26, '2023_03_30_012446_create_operasionals_table', 1),
(27, '2023_03_30_013611_create_permohonans_table', 1),
(28, '2023_03_30_014305_create_data_penanggungjawabs_table', 1),
(29, '2023_03_30_025032_create_kebutuhans_table', 1),
(30, '2023_03_30_042717_create_laporan_perjadinkegiatans_table', 1),
(31, '2023_03_30_062533_create_data_penyedias_table', 1),
(32, '2023_03_30_063437_create_peminjaman_kendaraan_dinas_table', 1),
(33, '2023_03_30_063552_create_data_perjadinkegiatans_table', 1),
(34, '2023_03_30_064012_create_keuangan_perjadinkegiatans_table', 1),
(35, '2023_03_30_064252_create_mobilitas_perjadinkegiatans_table', 1),
(36, '2023_03_30_064458_create_peminjaman_sarpras_table', 1),
(37, '2023_03_30_064935_create_data_perjadinlangsungs_table', 1),
(38, '2023_03_30_065556_create_keuangan_perjadinlangsungs_table', 1),
(39, '2023_03_30_070929_create_perangkat_acaras_table', 1),
(40, '2023_05_08_072038_create_versis_table', 1),
(41, '2023_05_10_013002_create_administrators_table', 1),
(42, '2023_05_19_025251_create_ref_rkakl_programs_table', 1),
(43, '2023_05_29_035752_create_dokumen_permohonans_table', 1),
(44, '2023_05_29_061811_create_services_table', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `mobilitas_perjadinkegiatans`
--

CREATE TABLE `mobilitas_perjadinkegiatans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `mobilitas` varchar(255) NOT NULL,
  `tujuan_penggunaan` varchar(255) NOT NULL,
  `tgl_mulai` datetime NOT NULL,
  `tgl_selesai` datetime NOT NULL,
  `provinsi` varchar(255) NOT NULL,
  `kab_kota` varchar(255) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `data_perjadinkegiatan` bigint(20) UNSIGNED NOT NULL,
  `versi_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `non_pegawais`
--

CREATE TABLE `non_pegawais` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `NIP_NIK` varchar(255) NOT NULL,
  `nama_lengkap` varchar(255) NOT NULL,
  `golongan` varchar(255) NOT NULL,
  `pangkat` varchar(255) NOT NULL,
  `status` varchar(255) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `no_telp` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `non_pegawais`
--

INSERT INTO `non_pegawais` (`id`, `NIP_NIK`, `nama_lengkap`, `golongan`, `pangkat`, `status`, `alamat`, `email`, `no_telp`, `created_at`, `updated_at`) VALUES
(1, '196805051989031015', 'Ismail bin Mail', 'iii/a', 'Penata Muda', NULL, 'Kp. durian runtuh', 'mailbinmail@gmail.com', '08986004677', '2023-06-07 01:56:12', '2023-06-07 01:56:12'),
(2, '19304222011011010', 'Aruffin bin Abdul Salam', 'iii/c', 'Penata', NULL, 'Jl.Nakulo No.2 RT 08 RW 02 Kel.Klegen Kec.Kartoharjo Kota Madiun', 'upin12345@gmail.com', '085655227735', '2023-06-07 01:56:12', '2023-06-07 01:56:12'),
(3, '19721020261993031002', 'Arifin bin Abdul Salam', 'iii/d', 'Penata Tingkat I', NULL, 'Perum Widodo Kencana Indah I E/5 Kel. Pandean Kec. Taman Kota Medan', 'ipin12345@gmail.com', '081234620782', '2023-06-07 01:56:12', '2023-06-07 01:56:12'),
(4, '321316550888889', 'Jarjit Singh', 'iii/d', 'Penata Tingkat I', NULL, 'Jl. Batu Kuda 19 Bandung', 'jarjithahaha@gmail.com', '083877660921', '2023-06-07 01:56:12', '2023-06-07 01:56:12'),
(5, '321316550201027', 'Mohammad Al Hafeezy', 'iii/c', 'Penata', NULL, 'Jl. Rindang No.4 Bandung', 'fizi88@gmail.com', '08986004677', '2023-06-07 01:56:13', '2023-06-07 01:56:13'),
(6, '320432071229011', 'Ehsan bin Azzarudin', 'iv/', 'Pembina', NULL, 'Jl.Utama Jakarta', 'ehsan181@gmail.com', '08986004677', '2023-06-07 01:56:13', '2023-06-07 01:56:13'),
(7, '198110292010012002', 'Xiao Mei Mei', 'iv/e', 'Pembina Utama', NULL, 'Jl.Danau Toba Blok F/10, Bend.Hilir, Tanah Abang, Jakarta Pusat', 'meimeicantik@gmail.com', '089860010000', '2023-06-07 01:56:13', '2023-06-07 01:56:13'),
(8, '198802122011012009', 'Susanti', 'iv/b', 'Pembina Tingkat I', NULL, 'Jln. Petemon IV No.32-A, Kel.Petemon, Kec. Sawahan, Kota Surabaya, Jawa Timur', 'susanti96@gmail.com', '08776007677', '2023-06-07 01:56:13', '2023-06-07 01:56:13'),
(9, '198109291992022002', 'Rania Mumtaz', 'iii/d', 'Penata Tingkat I', NULL, 'Jl. Jodhipati No 12, Desa Banyubiru, Kec. Banyubiru Kabupaten Semarangan, Jawa Tengah', 'raniamumtaz17@gmail.com', '08777874618', '2023-06-07 01:56:13', '2023-06-07 01:56:13'),
(10, '19660118200121001', 'Arum Sofiana', 'iii/a', 'Penata Muda', NULL, 'Babakan Ciparay, Babakan Ciparay Kota Bandung', 'arumsofiana@gmail.com', '08981006691', '2023-06-07 01:56:13', '2023-06-07 01:56:13');

-- --------------------------------------------------------

--
-- Struktur dari tabel `operasionals`
--

CREATE TABLE `operasionals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `jumlah_frekuensi` int(11) NOT NULL,
  `satuan` int(11) DEFAULT NULL,
  `detail_satuan` varchar(255) DEFAULT NULL,
  `ket` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `fasilitas_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pegawais`
--

CREATE TABLE `pegawais` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `NIP_NIK` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(255) NOT NULL,
  `jenis_kelamin` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `golongan` varchar(255) NOT NULL,
  `pangkat` varchar(255) NOT NULL,
  `no_telp` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `foto` varchar(255) NOT NULL,
  `no_rekening` varchar(255) NOT NULL,
  `is_aktif` tinyint(1) NOT NULL,
  `jabatan_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `pegawais`
--

INSERT INTO `pegawais` (`id`, `NIP_NIK`, `password`, `nama_lengkap`, `jenis_kelamin`, `status`, `golongan`, `pangkat`, `no_telp`, `email`, `foto`, `no_rekening`, `is_aktif`, `jabatan_id`, `created_at`, `updated_at`) VALUES
(1, '3204320712000008', '$2y$10$NalaVauA1yQ7mVfSPLdC3urNASjR7ANsNULPNfqWQTRsVFzEsP2Me', 'Kemal Ramadhan', 'Laki - laki', 'Magang', 'viii/a', 'Penata Muda', '08986004677', 'km.kemal03@gmail.com', 'default.png', '12345678911', 1, 1, '2023-06-07 01:56:08', '2023-06-07 01:56:08'),
(2, '3204320712000009', '$2y$10$WBPbCbkDdNZ16j9H9tgNoO00Jvu4xc.LIX0Nx5I6V4CkwdAM6UqtW', 'Gama Kusumah', 'Laki - laki', 'Magang', 'viii/a', 'Penata Muda', '08986004677', 'gama@gmail.com', 'default.png', '12345678911', 1, 1, '2023-06-07 01:56:08', '2023-06-07 01:56:08'),
(3, '196609141990032001', '$2y$10$NWkSwoYFPOvQeoC7h8A5VuKoeVxqQyIGa.zjSRiWdZzcyd30yqymO', 'Cucu Juhaenah', 'Perempuan', 'PNS', 'iii/b', 'Penata Muda Tingkat I', '081320575396', 'juhaenahcucu@gmail.com', 'cucu.png', '123343212233', 1, 1, '2023-06-07 01:56:09', '2023-06-07 01:56:09'),
(4, '198202192010121004', '$2y$10$FoJ9i.U4uwE02EwUHCTAbu989HKxu0yZC4/sRuxv1tOoZ0wLIcLx6', 'Idik Nursidik, S.T.', 'Laki - Laki', 'PNS', 'iii/b', 'Penata Muda Tingkat I', '082115464615', 'idiknursidik@gmail.com', 'idik.png', '213443213355', 1, 2, '2023-06-07 01:56:09', '2023-06-07 01:56:09'),
(5, '198503182009121001', '$2y$10$UcC3lgW48367BWzFlxbNKekZ5dtp1UgvH/z3c3siSgEHkUCC9PouG', 'Hedi Naufal, S.Si., M.A.P.', 'Laki - Laki', 'PNS', 'iii/c', 'Penata', '081320435767', 'hedinaufal@gmail.com', 'hedi.png', '312345123234', 1, 3, '2023-06-07 01:56:09', '2023-06-07 01:56:09'),
(6, '198807162010122005', '$2y$10$mrbEWiGQV.Lj5xSyy9tdwOuBAWg6TjvUciMaPFXWxhqHYaEHciA0q', 'Hevy Pratiwi, S.I.Kom.', 'Perempuan', 'PNS', 'iii/c', 'Penata', '081321970306', 'Hevypratiwi@gmail.com', 'hevy.png', '451233567432', 1, 4, '2023-06-07 01:56:09', '2023-06-07 01:56:09'),
(7, '196602271986021001', '$2y$10$9J8gDXLzM8FMbdQOeJ9UxO9fYRd5zE8jtAFGVgsMz9hpd/enxA1gi', 'Dedi Setiadi M.', 'Laki - Laki', 'PNS', 'iii/b', 'Penata Muda Tingkat I', '085943488568', 'dedigembit@gmail.com', 'dedi.png', '567895234432', 1, 5, '2023-06-07 01:56:09', '2023-06-07 01:56:09'),
(8, '199511262019032018', '$2y$10$05iB27sMj3ifkw01RRD3pOlqp9VPVPzmqtbJfPQDFT3fvMhHwMiuy', 'Ghina Khairiyah Syam, S.Pd.', 'Perempuan', 'PNS', 'iii/a', 'Penata Muda', '085322636677', 'ghinakhairiyahsyam@gmail.com', 'ghina.png', '657897456302', 1, 6, '2023-06-07 01:56:09', '2023-06-07 01:56:09'),
(9, '196705151990021003', '$2y$10$fu8Lt1PLvcNrC.oUQvXiqOl/oAB606CxOmC2CWa8fslVCj.KOnsCy', 'Nandang Supriatna', 'Laki - Laki', 'PNS', 'iii/b', 'Penata Muda Tingkat I', '081395562732', 'nadangsupriatna67@gmail.com', 'nandang.png', '765789943456', 1, 7, '2023-06-07 01:56:10', '2023-06-07 01:56:10'),
(10, '198102252010122001', '$2y$10$sC5j.mb7FIIl4OS1XJP5vODZlMM9X6WeU6VmeqUO4VOYOjZ0V5BE6', 'Yeni Rospiani, S.S', 'Perempuan', 'PNS', 'iii/c', 'Penata', '085220538908', 'yenikopertis@gmail.com', 'yeni.png', '889976512345', 1, 8, '2023-06-07 01:56:10', '2023-06-07 01:56:10'),
(11, '198112162010122001', '$2y$10$9rpESjgNP93U0b.X.G09WuCuKvUcQjE/75R8PyoqeZB4JP8T6c19S', 'Ewisna Yulius, S.S.', 'Perempuan', 'PNS', 'iii/c', 'Penata', '081220085481', 'nhayuli@gmail.com', 'ewisna.png', '965784345678', 1, 9, '2023-06-07 01:56:10', '2023-06-07 01:56:10'),
(12, '3174091710740004', '$2y$10$cXlhqgpaJsv4ZqZjDxZe3uBTW7NU5sl3FkqFJ2zZglaBNOUmnxzDm', 'Riady Subanar, S.T.', 'Laki - Laki', 'Outsourcing', '-', '-', '087777168771', 'riady12345@gmail.com', 'riyady.png', '109876543345', 1, 10, '2023-06-07 01:56:10', '2023-06-07 01:56:10');

-- --------------------------------------------------------

--
-- Struktur dari tabel `peminjaman_kendaraan_dinas`
--

CREATE TABLE `peminjaman_kendaraan_dinas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `info_perjadinlangsung` bigint(20) UNSIGNED DEFAULT NULL,
  `kendaraan` bigint(20) UNSIGNED DEFAULT NULL,
  `mobilitas_perjadinkegiatan` bigint(20) UNSIGNED DEFAULT NULL,
  `pegawai_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `peminjaman_sarpras`
--

CREATE TABLE `peminjaman_sarpras` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `jumlah_asset` int(11) DEFAULT NULL,
  `tgl_peminjaman` datetime NOT NULL,
  `tgl_pengembalian` datetime DEFAULT NULL,
  `data_perjadinkegiatan` bigint(20) UNSIGNED DEFAULT NULL,
  `pegawai_id` bigint(20) UNSIGNED DEFAULT NULL,
  `asset` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(255) NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `versi_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `perangkat_acaras`
--

CREATE TABLE `perangkat_acaras` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pegawai_id` bigint(20) UNSIGNED DEFAULT NULL,
  `non_pegawai_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sebagai` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `detail_satuan` varchar(255) DEFAULT NULL,
  `satuan` int(11) DEFAULT NULL,
  `fasilitas_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `permohonans`
--

CREATE TABLE `permohonans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode_permohonan` varchar(255) DEFAULT NULL,
  `no_BMN` varchar(255) DEFAULT NULL,
  `tgl_permohonan` datetime NOT NULL,
  `tgl_pemeriksaan` datetime DEFAULT NULL,
  `tgl_pengerjaan` datetime DEFAULT NULL,
  `tgl_selesai` datetime DEFAULT NULL,
  `alasan_ket` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `MAK` varchar(255) DEFAULT NULL,
  `dokumen_nota` varchar(255) DEFAULT NULL,
  `admin` bigint(20) UNSIGNED DEFAULT NULL,
  `data_penanggungjawab_id` bigint(20) UNSIGNED DEFAULT NULL,
  `akun_x_rkakl_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ref_sbm_id` bigint(20) UNSIGNED DEFAULT NULL,
  `nominal` bigint(20) DEFAULT NULL,
  `pph` bigint(20) DEFAULT NULL,
  `total` bigint(20) DEFAULT NULL,
  `ruangan_id` bigint(20) UNSIGNED DEFAULT NULL,
  `kendaraan_id` bigint(20) UNSIGNED DEFAULT NULL,
  `asset_id` bigint(20) UNSIGNED DEFAULT NULL,
  `data_penyedia_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_acceptBMN` varchar(255) DEFAULT NULL,
  `is_acceptKeu` varchar(255) DEFAULT NULL,
  `is_acceptBend` varchar(255) DEFAULT NULL,
  `admin_BMN` bigint(20) UNSIGNED DEFAULT NULL,
  `admin_Keu` bigint(20) UNSIGNED DEFAULT NULL,
  `admin_Bend` bigint(20) UNSIGNED DEFAULT NULL,
  `service_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status_pembayaran` varchar(255) DEFAULT NULL,
  `versi_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `program_kerjas`
--

CREATE TABLE `program_kerjas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ref_rkakl_sub_komponen` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `ref_rkakl_kegiatans`
--

CREATE TABLE `ref_rkakl_kegiatans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ref_rkakl_program_id` bigint(20) UNSIGNED NOT NULL,
  `kode_kegiatan` varchar(255) NOT NULL,
  `nama_kegiatan` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `ref_rkakl_kegiatans`
--

INSERT INTO `ref_rkakl_kegiatans` (`id`, `ref_rkakl_program_id`, `kode_kegiatan`, `nama_kegiatan`, `created_at`, `updated_at`) VALUES
(1, 1, '4472', 'Pembinaan Kelembagaan Pendidikan Tinggi', '2023-06-07 01:56:15', '2023-06-07 01:56:15'),
(2, 2, '6392', 'Pengelolaan Lembaga Layanan Penddikan Tinggi', '2023-06-07 01:56:16', '2023-06-07 01:56:16');

-- --------------------------------------------------------

--
-- Struktur dari tabel `ref_rkakl_komponens`
--

CREATE TABLE `ref_rkakl_komponens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ref_rkakl_suboutput_id` bigint(20) UNSIGNED NOT NULL,
  `kode_komponen` varchar(255) NOT NULL,
  `nama_komponen` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `ref_rkakl_komponens`
--

INSERT INTO `ref_rkakl_komponens` (`id`, `ref_rkakl_suboutput_id`, `kode_komponen`, `nama_komponen`, `created_at`, `updated_at`) VALUES
(1, 1, '051', 'Pembinaan dan Evaluasi Lapangan Pengendalian Perguruan Tinggi', '2023-06-07 01:56:19', '2023-06-07 01:56:19'),
(2, 1, '052', 'Workshop/Sosialisasi/Bimbingan Teknis Peningkatan Mutu Perguruan Tinggi', '2023-06-07 01:56:19', '2023-06-07 01:56:19'),
(3, 1, '054', 'Fasilitasi Layanan LLDikti', '2023-06-07 01:56:19', '2023-06-07 01:56:19'),
(4, 2, '053', 'Visitasi dan evaluasi Lapangan', '2023-06-07 01:56:19', '2023-06-07 01:56:19'),
(5, 3, '004', 'Dukungan Operasional Penyelenggaraan Pendidikan', '2023-06-07 01:56:19', '2023-06-07 01:56:19'),
(6, 4, '051', 'Umum dan Rumah Tangga Satker', '2023-06-07 01:56:19', '2023-06-07 01:56:19'),
(7, 5, '001', 'Gaji dan Tunjangan', '2023-06-07 01:56:19', '2023-06-07 01:56:19'),
(8, 5, '002', 'Operasional dan Pemeliharaan Kantor', '2023-06-07 01:56:19', '2023-06-07 01:56:19'),
(9, 6, '998', 'Rehab/Renovasi Gedung dan Bangunan', '2023-06-07 01:56:19', '2023-06-07 01:56:19');

-- --------------------------------------------------------

--
-- Struktur dari tabel `ref_rkakl_outputs`
--

CREATE TABLE `ref_rkakl_outputs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ref_rkakl_kegiatan_id` bigint(20) UNSIGNED NOT NULL,
  `kode_output` varchar(255) NOT NULL,
  `nama_output` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `ref_rkakl_outputs`
--

INSERT INTO `ref_rkakl_outputs` (`id`, `ref_rkakl_kegiatan_id`, `kode_output`, `nama_output`, `created_at`, `updated_at`) VALUES
(1, 1, 'BDB', 'Fasilitasi dan Pembinaan Lembaga [00]', '2023-06-07 01:56:16', '2023-06-07 01:56:16'),
(2, 1, 'BEJ', 'Bantuan Pendidikan Tinggi [00]', '2023-06-07 01:56:16', '2023-06-07 01:56:16'),
(3, 2, 'EBA', 'Layanan Dukungan Manajemen Internal [00]', '2023-06-07 01:56:17', '2023-06-07 01:56:17'),
(4, 2, 'EBB', 'Layanan Sarana dan Prasarana Internal [00]', '2023-06-07 01:56:17', '2023-06-07 01:56:17');

-- --------------------------------------------------------

--
-- Struktur dari tabel `ref_rkakl_programs`
--

CREATE TABLE `ref_rkakl_programs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ref_rkakl_satker_id` bigint(20) UNSIGNED NOT NULL,
  `kode_program` varchar(255) NOT NULL,
  `program` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `ref_rkakl_programs`
--

INSERT INTO `ref_rkakl_programs` (`id`, `ref_rkakl_satker_id`, `kode_program`, `program`, `created_at`, `updated_at`) VALUES
(1, 1, '01.WA', 'Sekertariat Jendral', '2023-06-07 01:56:15', '2023-06-07 01:56:15'),
(2, 1, '01.DK', 'Program Pendidikan Tinggi', '2023-06-07 01:56:15', '2023-06-07 01:56:15');

-- --------------------------------------------------------

--
-- Struktur dari tabel `ref_rkakl_satkers`
--

CREATE TABLE `ref_rkakl_satkers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode_satker` varchar(255) NOT NULL,
  `satker` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `ref_rkakl_satkers`
--

INSERT INTO `ref_rkakl_satkers` (`id`, `kode_satker`, `satker`, `created_at`, `updated_at`) VALUES
(1, '723012', 'LEMBAGA LAYANAN PENDIDIKAN TINGGI WILAYAH IV BANDUNG', '2023-06-07 01:56:15', '2023-06-07 01:56:15');

-- --------------------------------------------------------

--
-- Struktur dari tabel `ref_rkakl_suboutputs`
--

CREATE TABLE `ref_rkakl_suboutputs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ref_rkakl_output_id` bigint(20) UNSIGNED NOT NULL,
  `kode_sub_output` varchar(255) NOT NULL,
  `nama_sub_output` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `ref_rkakl_suboutputs`
--

INSERT INTO `ref_rkakl_suboutputs` (`id`, `ref_rkakl_output_id`, `kode_sub_output`, `nama_sub_output`, `created_at`, `updated_at`) VALUES
(1, 1, '001', 'Lembaga Pendidikan Tinggi Akademik dan Vokasi yang mendapatkan layanan pembinaan peningkatan mutu', '2023-06-07 01:56:17', '2023-06-07 01:56:17'),
(2, 1, '002', 'Lembaga Pendidikan Tinggi Akademik dan Vokasi yang mendapat layanan rekomendasi', '2023-06-07 01:56:17', '2023-06-07 01:56:17'),
(3, 2, '001', 'Dosen Non PNS yang Menerima Tunjangan Profesi', '2023-06-07 01:56:18', '2023-06-07 01:56:18'),
(4, 3, '962', 'Layanan Umum', '2023-06-07 01:56:18', '2023-06-07 01:56:18'),
(5, 3, '994', 'Layanan Perkantoran', '2023-06-07 01:56:18', '2023-06-07 01:56:18'),
(6, 4, '971', 'Layanan Prasarana Internal', '2023-06-07 01:56:18', '2023-06-07 01:56:18');

-- --------------------------------------------------------

--
-- Struktur dari tabel `ref_rkakl_sub_komponens`
--

CREATE TABLE `ref_rkakl_sub_komponens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ref_rkakl_komponen_id` bigint(20) UNSIGNED NOT NULL,
  `kode_sub_kegiatan` varchar(255) NOT NULL,
  `nama_sub_kegiatan` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `ref_rkakl_sub_komponens`
--

INSERT INTO `ref_rkakl_sub_komponens` (`id`, `ref_rkakl_komponen_id`, `kode_sub_kegiatan`, `nama_sub_kegiatan`, `created_at`, `updated_at`) VALUES
(1, 1, 'A', 'Pemantauan, Pendampingan, Pembinaan, dan Evaluasi Perguruan Tinggi', '2023-06-07 01:56:19', '2023-06-07 01:56:19'),
(2, 2, 'A', 'Layanan Peningkatan Mutu PTS (Kelembagaan, Dosen, Tendik, dan Mahasiswa)', '2023-06-07 01:56:19', '2023-06-07 01:56:19'),
(3, 3, 'A', 'Penilaian Jabatan Akademik Dosen', '2023-06-07 01:56:19', '2023-06-07 01:56:19'),
(4, 3, 'B', 'Penguatan Tata Kelola LLDIKTI IV (Evaluasi RBI/ZI, Manajemen Risiko dan Standar Pelayanan Publik)', '2023-06-07 01:56:19', '2023-06-07 01:56:19'),
(5, 3, 'C', 'Koordinasi Pelaksanaan Anggaran', '2023-06-07 01:56:20', '2023-06-07 01:56:20'),
(6, 4, 'A', 'Evaluasi Lapangan Usulan Pendirian PT Perubahan PT dan Pembukaan Prodi pada PT', '2023-06-07 01:56:20', '2023-06-07 01:56:20'),
(7, 5, 'A', 'Tunjangan Sertifikasi Dosen Non PNS', '2023-06-07 01:56:20', '2023-06-07 01:56:20'),
(8, 6, 'A', 'Pengelolaan Jurnal', '2023-06-07 01:56:20', '2023-06-07 01:56:20'),
(9, 6, 'B', 'Pengelolaan Buletin', '2023-06-07 01:56:20', '2023-06-07 01:56:20'),
(10, 6, 'C', 'Pengelolaan Website', '2023-06-07 01:56:20', '2023-06-07 01:56:20'),
(11, 6, 'D', 'Koordinasi dan Evaluasi Pelaksanaan Anggaran', '2023-06-07 01:56:20', '2023-06-07 01:56:20'),
(12, 7, 'A', 'Pembayaran Gaji dan Tunjangan', '2023-06-07 01:56:20', '2023-06-07 01:56:20'),
(13, 7, 'B', 'Pembayaran Tunjangan Sertifikasi Dosen PNS', '2023-06-07 01:56:20', '2023-06-07 01:56:20'),
(14, 8, 'A', 'Honorarium PPNPN  Pengadaan Seragam', '2023-06-07 01:56:20', '2023-06-07 01:56:20'),
(15, 8, 'B', 'Langganan Daya dan Jasa', '2023-06-07 01:56:20', '2023-06-07 01:56:20'),
(16, 8, 'C', 'Penyelenggaraan Operasional dan Pemeliharaan Perkantoran', '2023-06-07 01:56:21', '2023-06-07 01:56:21'),
(17, 8, 'D', 'Perawatan dan Pemeliharaan', '2023-06-07 01:56:21', '2023-06-07 01:56:21'),
(18, 9, 'A', 'Rehabilitasi/Renovasi Gedung dan Bangunan', '2023-06-07 01:56:21', '2023-06-07 01:56:21'),
(19, 9, 'B', 'Pengadaan Laptop CPNS  Paket Broadcast', '2023-06-07 01:56:21', '2023-06-07 01:56:21');

-- --------------------------------------------------------

--
-- Struktur dari tabel `ref_sbms`
--

CREATE TABLE `ref_sbms` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode_sbm` varchar(255) NOT NULL,
  `uraian` varchar(255) NOT NULL,
  `satuan` varchar(255) NOT NULL,
  `biaya` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `ref_sbms`
--

INSERT INTO `ref_sbms` (`id`, `kode_sbm`, `uraian`, `satuan`, `biaya`, `created_at`, `updated_at`) VALUES
(1, '11.1.a', 'Honorarium Narasumber Menteri/Pejabat Setingkat Menteri/Pejabat Negara Lainnya/ yang disetarakan', 'OJ', 1700000, '2023-06-07 01:56:28', '2023-06-07 01:56:28'),
(2, '11.1.b', 'Honorarium Narasumber Pejabat Eselon I/yang disetarakan', 'OJ', 1400000, '2023-06-07 01:56:28', '2023-06-07 01:56:28'),
(3, '11.2', 'Honorarium Moderator', 'Orang/ Kali ', 700000, '2023-06-07 01:56:28', '2023-06-07 01:56:28'),
(4, '11.3', 'Honorarium Pembawa Acara', 'OK', 400000, '2023-06-07 01:56:28', '2023-06-07 01:56:28'),
(5, '29', 'HONORARIUM SATPAM, PENGEMUDI, PETUGAS KEBERSIHAN, DAN PRAMUBAKTI JAWA BARAT', 'OB', 3777000, '2023-06-07 01:56:28', '2023-06-07 01:56:28');

-- --------------------------------------------------------

--
-- Struktur dari tabel `ruangans`
--

CREATE TABLE `ruangans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode_ruangan` varchar(255) NOT NULL,
  `nama_ruangan` varchar(255) NOT NULL,
  `pegawai_id` bigint(20) UNSIGNED NOT NULL,
  `kondisi` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `services`
--

CREATE TABLE `services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `penyedia_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `versis`
--

CREATE TABLE `versis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `versi` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `versis`
--

INSERT INTO `versis` (`id`, `versi`, `status`, `created_at`, `updated_at`) VALUES
(1, '2022', 'non-aktif', '2023-06-07 01:56:06', '2023-06-07 01:56:06'),
(2, '2023', 'aktif', '2023-06-07 01:56:06', '2023-06-07 01:56:06');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `administrators`
--
ALTER TABLE `administrators`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `administrators_email_unique` (`email`);

--
-- Indeks untuk tabel `akuns`
--
ALTER TABLE `akuns`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `akun_x_rkakls`
--
ALTER TABLE `akun_x_rkakls`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `assets`
--
ALTER TABLE `assets`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `data_penanggungjawabs`
--
ALTER TABLE `data_penanggungjawabs`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `data_penyedias`
--
ALTER TABLE `data_penyedias`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `data_perjadinkegiatans`
--
ALTER TABLE `data_perjadinkegiatans`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `data_perjadinlangsungs`
--
ALTER TABLE `data_perjadinlangsungs`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `dokumens`
--
ALTER TABLE `dokumens`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `dokumen_permohonans`
--
ALTER TABLE `dokumen_permohonans`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `fasilitas`
--
ALTER TABLE `fasilitas`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `fungsis`
--
ALTER TABLE `fungsis`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `info_perjadinlangsungs`
--
ALTER TABLE `info_perjadinlangsungs`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `jabatans`
--
ALTER TABLE `jabatans`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kebutuhans`
--
ALTER TABLE `kebutuhans`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kendaraans`
--
ALTER TABLE `kendaraans`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `keuangan_perjadinkegiatans`
--
ALTER TABLE `keuangan_perjadinkegiatans`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `keuangan_perjadinlangsungs`
--
ALTER TABLE `keuangan_perjadinlangsungs`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `komponen_diperlukans`
--
ALTER TABLE `komponen_diperlukans`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `laporan_perjadinkegiatans`
--
ALTER TABLE `laporan_perjadinkegiatans`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `mobilitas_perjadinkegiatans`
--
ALTER TABLE `mobilitas_perjadinkegiatans`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `non_pegawais`
--
ALTER TABLE `non_pegawais`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `non_pegawais_nip_nik_unique` (`NIP_NIK`),
  ADD UNIQUE KEY `non_pegawais_email_unique` (`email`);

--
-- Indeks untuk tabel `operasionals`
--
ALTER TABLE `operasionals`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `pegawais`
--
ALTER TABLE `pegawais`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pegawais_nip_nik_unique` (`NIP_NIK`);

--
-- Indeks untuk tabel `peminjaman_kendaraan_dinas`
--
ALTER TABLE `peminjaman_kendaraan_dinas`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `peminjaman_sarpras`
--
ALTER TABLE `peminjaman_sarpras`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `perangkat_acaras`
--
ALTER TABLE `perangkat_acaras`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `permohonans`
--
ALTER TABLE `permohonans`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indeks untuk tabel `program_kerjas`
--
ALTER TABLE `program_kerjas`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `ref_rkakl_kegiatans`
--
ALTER TABLE `ref_rkakl_kegiatans`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `ref_rkakl_komponens`
--
ALTER TABLE `ref_rkakl_komponens`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `ref_rkakl_outputs`
--
ALTER TABLE `ref_rkakl_outputs`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `ref_rkakl_programs`
--
ALTER TABLE `ref_rkakl_programs`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `ref_rkakl_satkers`
--
ALTER TABLE `ref_rkakl_satkers`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `ref_rkakl_suboutputs`
--
ALTER TABLE `ref_rkakl_suboutputs`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `ref_rkakl_sub_komponens`
--
ALTER TABLE `ref_rkakl_sub_komponens`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `ref_sbms`
--
ALTER TABLE `ref_sbms`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `ruangans`
--
ALTER TABLE `ruangans`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indeks untuk tabel `versis`
--
ALTER TABLE `versis`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `administrators`
--
ALTER TABLE `administrators`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `akuns`
--
ALTER TABLE `akuns`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT untuk tabel `akun_x_rkakls`
--
ALTER TABLE `akun_x_rkakls`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT untuk tabel `assets`
--
ALTER TABLE `assets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `data_penanggungjawabs`
--
ALTER TABLE `data_penanggungjawabs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `data_penyedias`
--
ALTER TABLE `data_penyedias`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `data_perjadinkegiatans`
--
ALTER TABLE `data_perjadinkegiatans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `data_perjadinlangsungs`
--
ALTER TABLE `data_perjadinlangsungs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `dokumens`
--
ALTER TABLE `dokumens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `dokumen_permohonans`
--
ALTER TABLE `dokumen_permohonans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `fasilitas`
--
ALTER TABLE `fasilitas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `fungsis`
--
ALTER TABLE `fungsis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `info_perjadinlangsungs`
--
ALTER TABLE `info_perjadinlangsungs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jabatans`
--
ALTER TABLE `jabatans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `kebutuhans`
--
ALTER TABLE `kebutuhans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `kendaraans`
--
ALTER TABLE `kendaraans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `keuangan_perjadinkegiatans`
--
ALTER TABLE `keuangan_perjadinkegiatans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `keuangan_perjadinlangsungs`
--
ALTER TABLE `keuangan_perjadinlangsungs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `komponen_diperlukans`
--
ALTER TABLE `komponen_diperlukans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `laporan_perjadinkegiatans`
--
ALTER TABLE `laporan_perjadinkegiatans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT untuk tabel `mobilitas_perjadinkegiatans`
--
ALTER TABLE `mobilitas_perjadinkegiatans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `non_pegawais`
--
ALTER TABLE `non_pegawais`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `operasionals`
--
ALTER TABLE `operasionals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pegawais`
--
ALTER TABLE `pegawais`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `peminjaman_kendaraan_dinas`
--
ALTER TABLE `peminjaman_kendaraan_dinas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `peminjaman_sarpras`
--
ALTER TABLE `peminjaman_sarpras`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `perangkat_acaras`
--
ALTER TABLE `perangkat_acaras`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `permohonans`
--
ALTER TABLE `permohonans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `program_kerjas`
--
ALTER TABLE `program_kerjas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `ref_rkakl_kegiatans`
--
ALTER TABLE `ref_rkakl_kegiatans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `ref_rkakl_komponens`
--
ALTER TABLE `ref_rkakl_komponens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `ref_rkakl_outputs`
--
ALTER TABLE `ref_rkakl_outputs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `ref_rkakl_programs`
--
ALTER TABLE `ref_rkakl_programs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `ref_rkakl_satkers`
--
ALTER TABLE `ref_rkakl_satkers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `ref_rkakl_suboutputs`
--
ALTER TABLE `ref_rkakl_suboutputs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `ref_rkakl_sub_komponens`
--
ALTER TABLE `ref_rkakl_sub_komponens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT untuk tabel `ref_sbms`
--
ALTER TABLE `ref_sbms`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `ruangans`
--
ALTER TABLE `ruangans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `versis`
--
ALTER TABLE `versis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

