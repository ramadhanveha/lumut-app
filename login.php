<?php

if (!isset($_POST['username']) || !isset($_POST['password'])) {
    echo '<meta http-equiv="refresh" content="0;url=http://localhost/lumut-app/login.html">';
    exit;
}


elseif (empty($_POST['username']) || empty($_POST['password'])) {
    echo '<meta http-equiv="refresh" content="0;url=http://localhost/lumut-app/login.html">';
    exit;
} else {
   
    $user = addslashes($_POST['username']);
    $pass = md5($_POST['password']);

   
    $dbHost = "localhost";
    $dbUser = "root"; 
    $dbPass = ""; 
    $dbDatabase = "lumut-app"; 

    
    try {
        $pdo = new PDO("mysql:host=$dbHost;dbname=$dbDatabase", $dbUser, $dbPass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("koneksi gagal nih, cek apakah variabel sudah benar apa belum: " . $e->getMessage());
    }

  
    $stmt = $pdo->prepare("SELECT * FROM account WHERE username = :username AND password = :password");
    $stmt->bindParam(':username', $user);
    $stmt->bindParam(':password', $pass);

   
    $stmt->execute();

    
    $rowCheck = $stmt->rowCount();
  
   
    if ($rowCheck > 0) {
        
        session_start();
        $_SESSION['username'] = $user;

        $data = $stmt->fetch(PDO::FETCH_ASSOC); 
        $_SESSION['role'] = $data['role'];
        
        echo 'login berhasil..!!';

        
        echo '<meta http-equiv="refresh" content="3;url=index.php">';
        exit;
    } else {
       
        echo 'Invalid username or password, coba lagi deh.. ';
    }
}
?>