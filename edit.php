<?php
session_start(); // Memulai sesi PHP untuk menggunakan variabel session

include "koneksi.php"; // Menyertakan file koneksi.php yang berisi kode untuk menghubungkan ke database

// Mengecek apakah form telah disubmit
if (isset($_POST['submit'])) {
    // Validasi dan sanitasi input
    $id = intval($_GET['id']); // Mengambil ID barang dari URL dan mengkonversi ke integer
    $nama = mysqli_real_escape_string($db, $_POST['nama_barang']); // Menyaring input nama_barang
    $harga = floatval($_POST['harga_barang']); // Mengambil harga barang dari input form
    $stok = intval($_POST['stok_barang']); // Mengambil stok barang dari input form

    // Menyusun query SQL untuk memperbarui data barang dalam tabel 'barang'
    $sql = "UPDATE barang SET nama_barang = ?, harga_barang = ?, stok_barang = ? WHERE id_barang = ?";
    
    // Menyiapkan statement
    if ($stmt = mysqli_prepare($db, $sql)) {
        // Mengikat parameter
        mysqli_stmt_bind_param($stmt, "sdii", $nama, $harga, $stok, $id);

        // Menjalankan statement
        if (mysqli_stmt_execute($stmt)) {
            // Jika berhasil, set pesan sukses dalam session dan arahkan ke halaman lihat.php
            $_SESSION['pesan'] = "Berhasil mengedit barang";
            header("Location: lihat.php"); // Arahkan pengguna ke halaman lihat.php
            exit(); // Hentikan eksekusi script setelah redirect
        } else {
            // Jika gagal, tampilkan pesan error
            die("Gagal mengedit barang: " . mysqli_stmt_error($stmt));
        }

        // Menutup statement
        mysqli_stmt_close($stmt);
    } else {
        die("Gagal menyiapkan statement: " . mysqli_error($db));
    }

    // Menutup koneksi database
    mysqli_close($db);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Barang</title>
</head>
<body>
    <h2>Form Edit Barang</h2>
    <!-- Form untuk mengedit barang -->
    <form action="edit.php?id=<?php echo htmlspecialchars($_GET['id']); ?>" method="post">
        <?php
        // Mengecek apakah ID barang ada di URL
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']); // Mengambil ID barang dari URL dan mengkonversi ke integer
            $sql = "SELECT * FROM barang WHERE id_barang = ?";
            
            // Menyiapkan statement
            if ($stmt = mysqli_prepare($db, $sql)) {
                // Mengikat parameter
                mysqli_stmt_bind_param($stmt, "i", $id);

                // Menjalankan statement
                mysqli_stmt_execute($stmt);

                // Mengambil hasil
                $result = mysqli_stmt_get_result($stmt);
                if ($result->num_rows > 0) {
                    $data = $result->fetch_assoc();
        ?>
                    <!-- Input form dengan nilai awal diisi dengan data barang yang ada -->
                    <label for="nama_barang">Nama Barang:</label><br>
                    <input type="text" id="nama_barang" name="nama_barang" value="<?php echo htmlspecialchars($data['nama_barang']); ?>" required><br><br>
                    <label for="harga_barang">Harga Barang:</label><br>
                    <input type="number" id="harga_barang" name="harga_barang" value="<?php echo htmlspecialchars($data['harga_barang']); ?>" required><br><br>
                    <label for="stok_barang">Stok Barang:</label><br>
                    <input type="number" id="stok_barang" name="stok_barang" value="<?php echo htmlspecialchars($data['stok_barang']); ?>" required><br><br>
        <?php
                }
                // Menutup statement
                mysqli_stmt_close($stmt);
            } else {
                die("Gagal menyiapkan statement: " . mysqli_error($db));
            }
        }
        ?>
        <!-- Tombol submit untuk mengirim data ke server -->
        <input type="submit" value="Edit Barang" name="submit">
        <!-- Link untuk kembali ke halaman lihat.php -->
        <a href="lihat.php">Kembali</a>
    </form>
</body>
</html>
