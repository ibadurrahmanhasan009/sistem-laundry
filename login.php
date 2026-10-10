<?php
session_start();
include 'config.php';

// Jika sudah login, tendang ke dashboard
if (isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // Ubah password inputan jadi MD5
    $password_md5 = md5($password);

    // Query cek username dan password MD5
    $result = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username' AND password = '$password_md5'");
    
    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        
        // Set Session
        $_SESSION['login'] = true;
        $_SESSION['id_user'] = $row['id_user'];
        $_SESSION['nama'] = $row['nama_lengkap'];
        $_SESSION['role'] = $row['role'];
        
        header("Location: index.php");
        exit;
    }
    $error = true;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Bad_Laundry</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f4f7f6; height: 100vh; display: flex; align-items: center; }
        .card { border-radius: 15px; border: none; }
        .btn-primary { border-radius: 8px; padding: 10px; }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card shadow-lg">
               <div class="card-body p-5">
    <h3 class="text-center fw-bold mb-3">Bad_Laundry</h3>
    
    
    <div class="text-center mb-4">
        <img src="img/logo.png" alt="Logo" style="max-height: 120px; width: auto;">
    </div>
    
    <?php if(isset($error)) : ?>
        <div class="alert alert-danger text-center py-2" role="alert">
            Username/Password Salah!
        </div>
    <?php endif; ?>

    <form action="" method="POST">
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" placeholder="Masukan Username" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" placeholder="Masukan Password" required>
        </div>
        <div class="d-grid mt-4">
            <button type="submit" name="login" class="btn btn-primary fw-bold">Masuk Sekarang</button>
        </div>
    </form>
</div>
    </div>
</div>

</body>
</html>