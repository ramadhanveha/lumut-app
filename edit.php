<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lumut-app";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Koneksi gagal: " . $e->getMessage();
}

// Operasi Update
if(isset($_POST['update']) && $_SESSION['role']!=='author'){
    $id = $_GET['id'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    var_dump($id);

    try {
        $stmt = $conn->prepare("UPDATE post SET title=:title, content=:content WHERE idpost=:idpost");
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':idpost', $id);
        $stmt->execute();
        echo "Data berhasil diperbarui.";
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Ambil data post yang akan diedit
if(isset($_GET['idpost'])){
    $id = $_GET['idpost'];

    try {
        $stmt = $conn->prepare("SELECT * FROM post WHERE idpost=:idpost");
        $stmt->bindParam(':idpost', $id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

$conn = null;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Post</title>
</head>
<body>
<h2>Edit Post</h2>
    <form method="post">
        <input type="hidden" name="id" value="<?php echo isset($row['idpost']) ? $row['idpost'] : ''; ?>">
        <label for="title">Judul:</label><br>
        <input type="text" id="title" name="title" value="<?php echo isset($row['title']) ? $row['title'] : ''; ?>" required><br>
        <label for="content">Isi:</label><br>
        <textarea id="content" name="content" required><?php echo isset($row['content']) ? $row['content'] : ''; ?></textarea><br>
        <input type="submit" name="update" value="Update">
    </form>
</body>
</html>
