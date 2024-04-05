<?php

session_start();


if(isset($_SESSION['username'])) {

   
// Koneksi ke database menggunakan PDO
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lumut-app";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set mode error untuk menampilkan error sebagai exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Koneksi gagal: " . $e->getMessage();
}

// Operasi Create
if(isset($_POST['submit'])){
    $title = $_POST['title'];
    $content = $_POST['content'];

    try {
        $stmt = $conn->prepare("INSERT INTO post (title, content, date,username) VALUES (:title, :content, :date,:username)");
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $date=date('Y-m-d H:i:s');
        $stmt->bindParam(':date', $date,PDO::PARAM_STR);
        $stmt->bindParam(':username', $_SESSION['username']);
        $stmt->execute();
        echo "Data berhasil ditambahkan.";
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Operasi Read
try {
    $stmt = $conn->prepare("SELECT * FROM post");
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($result) {
        foreach ($result as $row) {
            echo "ID: " . $row["idpost"]. " - Title: " . $row["title"]. " - Content: " . $row["content"]. "<br>";
        }
    } else {
        echo "0 results";
    }
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Operasi Delete
if(isset($_GET['delete'])){
    $id = $_GET['delete'];

    try {
        $stmt = $conn->prepare("DELETE FROM post WHERE idpost=:idpost");
        $stmt->bindParam(':idpost', $id);
        $stmt->execute();
        echo "Data berhasil dihapus.";
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

  
} else {
   
    echo '<meta http-equiv="refresh" content="0;url=http://localhost/lumut-app/login.html">';
    exit; 
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>CRUD Blog Post</title>
</head>
<body>
    <h2>Tambah Post Baru</h2>
    <form method="post">
        <label for="title">Judul:</label><br>
        <input type="text" id="title" name="title" required><br>
        <label for="content">Isi:</label><br>
        <textarea id="content" name="content" required></textarea><br>
        <input type="submit" name="submit" value="Submit">
    </form>
    <a href="http://localhost/lumut-app/logout.php">logout</a>.

    <h2>Daftar Post</h2>
    <?php
        // Menampilkan daftar post dengan tombol hapus
        if ($result) {
            foreach ($result as $row) {
                echo "ID: " . $row["idpost"]. " - Title: " . $row["title"]. " - Content: " . $row["content"]. " - <a href='edit.php?id=" . $row["idpost"] . "'>Edit</a> - <a href='?delete=" . $row["idpost"] . "'>Hapus</a><br>";
            }
        } else {
            echo "0 results";
        }
    ?>
</body>
</html>