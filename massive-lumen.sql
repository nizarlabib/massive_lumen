-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 25 Jun 2023 pada 05.40
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
-- Database: `massive-lumen`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `acara`
--

CREATE TABLE `acara` (
  `id_acara` bigint(20) UNSIGNED NOT NULL,
  `nama_acara` varchar(255) NOT NULL,
  `deskripsi_acara` text NOT NULL,
  `alamat_acara` varchar(255) NOT NULL,
  `gambar_acara` varchar(255) DEFAULT NULL,
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `acara`
--

INSERT INTO `acara` (`id_acara`, `nama_acara`, `deskripsi_acara`, `alamat_acara`, `gambar_acara`, `id_user`, `created_at`, `updated_at`) VALUES
(1, 'festival', 'seru', 'malang city', NULL, 3, '2023-06-25 03:36:51', '2023-06-25 03:36:51');

-- --------------------------------------------------------

--
-- Struktur dari tabel `galeri_acara`
--

CREATE TABLE `galeri_acara` (
  `id_galeri_acara` bigint(20) UNSIGNED NOT NULL,
  `galeri_acara` varchar(255) DEFAULT NULL,
  `id_acara` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `galeri_acara`
--

INSERT INTO `galeri_acara` (`id_galeri_acara`, `galeri_acara`, `id_acara`, `created_at`, `updated_at`) VALUES
(1, NULL, 1, '2023-06-25 03:37:18', '2023-06-25 03:37:18');

-- --------------------------------------------------------

--
-- Struktur dari tabel `galeri_wisata`
--

CREATE TABLE `galeri_wisata` (
  `id_galeri_wisata` bigint(20) UNSIGNED NOT NULL,
  `galeri_wisata` varchar(255) DEFAULT NULL,
  `id_wisata` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `galeri_wisata`
--

INSERT INTO `galeri_wisata` (`id_galeri_wisata`, `galeri_wisata`, `id_wisata`, `created_at`, `updated_at`) VALUES
(1, NULL, 1, '2023-06-25 03:38:31', '2023-06-25 03:38:31');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori_wisata`
--

CREATE TABLE `kategori_wisata` (
  `id_kategori_wisata` bigint(20) UNSIGNED NOT NULL,
  `nama_kategori_wisata` varchar(255) NOT NULL,
  `deskripsi_kategori_wisata` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `kategori_wisata`
--

INSERT INTO `kategori_wisata` (`id_kategori_wisata`, `nama_kategori_wisata`, `deskripsi_kategori_wisata`, `created_at`, `updated_at`) VALUES
(1, 'pantai', 'lampung memiliki banyak pantai indah', '2023-06-25 03:30:40', '2023-06-25 03:31:35');

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
(1, '2023_06_14_030021_create_role_user_table', 1),
(2, '2023_06_14_030102_create_user_table', 1),
(3, '2023_06_14_030115_create_wisata_table', 1),
(4, '2023_06_14_030126_create_kategori_wisata_table', 1),
(5, '2023_06_14_030142_create_transaksi_table', 1),
(6, '2023_06_14_143524_create_transaksi_wisata_table', 1),
(7, '2023_06_16_030337_create_acara_table', 1),
(8, '2023_06_17_011450_add_column_token_to_user', 1),
(9, '2023_06_22_135740_create_galeri_wisata_table', 1),
(10, '2023_06_22_135800_create_galeri_acara_table', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `role_user`
--

CREATE TABLE `role_user` (
  `id_role_user` bigint(20) UNSIGNED NOT NULL,
  `nama_role` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `role_user`
--

INSERT INTO `role_user` (`id_role_user`, `nama_role`, `created_at`, `updated_at`) VALUES
(1, 'admin', '2023-06-25 03:29:13', '2023-06-25 03:29:13'),
(2, 'pengelola', '2023-06-25 03:29:22', '2023-06-25 03:29:22'),
(3, 'wisatawan', '2023-06-25 03:29:33', '2023-06-25 03:29:33');

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` bigint(20) UNSIGNED NOT NULL,
  `total_transaksi` varchar(255) NOT NULL,
  `status_transaksi` varchar(255) NOT NULL,
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `total_transaksi`, `status_transaksi`, `id_user`, `created_at`, `updated_at`) VALUES
(1, '100000', 'pending', 5, '2023-06-25 03:39:22', '2023-06-25 03:39:22');

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi_wisata`
--

CREATE TABLE `transaksi_wisata` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `id_transaksi` bigint(20) UNSIGNED NOT NULL,
  `id_wisata` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `transaksi_wisata`
--

INSERT INTO `transaksi_wisata` (`id`, `created_at`, `updated_at`, `id_transaksi`, `id_wisata`) VALUES
(1, '2023-06-25 03:39:22', '2023-06-25 03:39:22', 1, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `nama_user` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `no_telepon` varchar(255) NOT NULL,
  `foto_profil` varchar(255) DEFAULT NULL,
  `id_role_user` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id_user`, `nama_user`, `email`, `password`, `no_telepon`, `foto_profil`, `id_role_user`, `created_at`, `updated_at`, `token`) VALUES
(1, 'admin', 'admin@gmail.com', '$2y$10$v0saO4D5K85abjqcqEA/CefaeTS/1st9/JNHqRESC9jRlRukvKqH2', '089999999999', 'E:\\Nizar labib\\KULIAH\\massive-project\\massive-lumen/public/images/1687663676.svg', 1, '2023-06-25 03:27:56', '2023-06-25 03:28:29', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJsdW1lbi1qd3QiLCJzdWIiOiJhZG1pbkBnbWFpbC5jb20iLCJpYXQiOjE2ODc2NjM3MDksImV4cCI6MTY4NzY2NzMwOX0.F6CDUgckX3qJP39pr58Kdcc1H0fKiGReWV9bVahb9Qk'),
(3, 'pengelola', 'pengelola@gmail.com', '$2y$10$S05OGVQrhlbRfCMFpJG5k.imNY78fJ3HD1zqbMB6RcKwV9E1skc8a', '089999999999', 'E:\\Nizar labib\\KULIAH\\massive-project\\massive-lumen/public/images/1687664035.svg', 2, '2023-06-25 03:33:55', '2023-06-25 03:36:14', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJsdW1lbi1qd3QiLCJzdWIiOiJwZW5nZWxvbGFAZ21haWwuY29tIiwiaWF0IjoxNjg3NjY0MTc0LCJleHAiOjE2ODc2Njc3NzR9.CmJwUmKXdo5oivnAmK4e4ncDtm4ELx7de6S8fhjPIuU'),
(5, 'wisatawan', 'wisatawan@gmail.com', '$2y$10$rYqJqWB.LwWSTBtdC8Xe0e.yjHRqqWfN2jsckl2m6KD.OM0uFDK2a', '089999999999', 'E:\\Nizar labib\\KULIAH\\massive-project\\massive-lumen/public/images/1687664087.svg', 3, '2023-06-25 03:34:47', '2023-06-25 03:39:01', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJsdW1lbi1qd3QiLCJzdWIiOiJ3aXNhdGF3YW5AZ21haWwuY29tIiwiaWF0IjoxNjg3NjY0MzQxLCJleHAiOjE2ODc2Njc5NDF9.k6L3PXzOjOetNb8ne1zCn1H3hqdYbkOtZaJrnZR9lrE');

-- --------------------------------------------------------

--
-- Struktur dari tabel `wisata`
--

CREATE TABLE `wisata` (
  `id_wisata` bigint(20) UNSIGNED NOT NULL,
  `nama_wisata` varchar(255) NOT NULL,
  `deskripsi_wisata` text NOT NULL,
  `gambar_wisata` varchar(255) DEFAULT NULL,
  `alamat_wisata` varchar(255) NOT NULL,
  `jam_buka_wisata` varchar(255) NOT NULL,
  `harga_tiket_wisata` varchar(255) NOT NULL,
  `id_kategori_wisata` bigint(20) UNSIGNED NOT NULL,
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `wisata`
--

INSERT INTO `wisata` (`id_wisata`, `nama_wisata`, `deskripsi_wisata`, `gambar_wisata`, `alamat_wisata`, `jam_buka_wisata`, `harga_tiket_wisata`, `id_kategori_wisata`, `id_user`, `created_at`, `updated_at`) VALUES
(1, 'gunung panderman', 'panderman punya cerita', NULL, 'kacuk city', '07.00', '50.000', 1, 3, '2023-06-25 03:36:35', '2023-06-25 03:36:35');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `acara`
--
ALTER TABLE `acara`
  ADD PRIMARY KEY (`id_acara`);

--
-- Indeks untuk tabel `galeri_acara`
--
ALTER TABLE `galeri_acara`
  ADD PRIMARY KEY (`id_galeri_acara`);

--
-- Indeks untuk tabel `galeri_wisata`
--
ALTER TABLE `galeri_wisata`
  ADD PRIMARY KEY (`id_galeri_wisata`);

--
-- Indeks untuk tabel `kategori_wisata`
--
ALTER TABLE `kategori_wisata`
  ADD PRIMARY KEY (`id_kategori_wisata`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `role_user`
--
ALTER TABLE `role_user`
  ADD PRIMARY KEY (`id_role_user`);

--
-- Indeks untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`);

--
-- Indeks untuk tabel `transaksi_wisata`
--
ALTER TABLE `transaksi_wisata`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `user_token_unique` (`token`);

--
-- Indeks untuk tabel `wisata`
--
ALTER TABLE `wisata`
  ADD PRIMARY KEY (`id_wisata`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `acara`
--
ALTER TABLE `acara`
  MODIFY `id_acara` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `galeri_acara`
--
ALTER TABLE `galeri_acara`
  MODIFY `id_galeri_acara` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `galeri_wisata`
--
ALTER TABLE `galeri_wisata`
  MODIFY `id_galeri_wisata` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `kategori_wisata`
--
ALTER TABLE `kategori_wisata`
  MODIFY `id_kategori_wisata` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `role_user`
--
ALTER TABLE `role_user`
  MODIFY `id_role_user` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `transaksi_wisata`
--
ALTER TABLE `transaksi_wisata`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id_user` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `wisata`
--
ALTER TABLE `wisata`
  MODIFY `id_wisata` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
