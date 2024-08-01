<?php
session_start(); // Memulai sesi PHP untuk menggunakan variabel session
//marlina setya ningrum sudah di cek pMoko
include "koneksi.php"; // Menyertakan file koneksi.php yang berisi kode untuk menghubungkan ke database

// Mengecek apakah parameter 'id' ada di URL
if (isset($_GET['id'])) {
    // Validasi dan sanitasi ID
    $id = intval($_GET['id']); // Mengambil ID barang dari parameter URL dan mengkonversi ke integer

    // Menyusun query SQL untuk menghapus data barang dari tabel 'barang' berdasarkan ID
    $sql = "DELETE FROM barang WHERE id_barang = ?";

    // Menyiapkan statement
    if ($stmt = mysqli_prepare($db, $sql)) {
        // Mengikat parameter
        mysqli_stmt_bind_param($stmt, "i", $id);

        // Menjalankan statement
        if (mysqli_stmt_execute($stmt)) {
            // Jika berhasil, set pesan sukses dalam session dan arahkan ke halaman lihat.php
            $_SESSION['pesan'] = "Berhasil menghapus barang";
            header("Location: lihat.php"); // Arahkan pengguna ke halaman lihat.php
            exit(); // Hentikan eksekusi script setelah redirect
        } else {
            // Jika gagal, tampilkan pesan error
            die("Gagal menghapus barang: " . mysqli_stmt_error($stmt));
        }

        // Menutup statement
        mysqli_stmt_close($stmt);
    } else {
        die("Gagal menyiapkan statement: " . mysqli_error($db));
    }

    // Menutup koneksi database
    mysqli_close($db);
} else {
    // Jika parameter 'id' tidak ada di URL, arahkan pengguna ke halaman lihat.php
    header("Location: lihat.php");
    exit();
}
?>
