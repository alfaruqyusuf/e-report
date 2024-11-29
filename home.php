<?php
session_start();
include("php/config.php");
if (!isset($_SESSION['token'])) {
  header("Location: login.php");
  exit();
} else {
  if (!validateToken($_SESSION['token'])) {
    session_destroy();
    header("Location: login.php");
    exit();
  }


  if (isset($_SESSION['update_success'])) {
    $success_message = "Profile berhasil diperbarui!";
    unset($_SESSION['update_success']);
  }
}

// untuk mendapatkan user data //
$token = $_SESSION['token'];
$stmt = $con->prepare("SELECT users.id, username, email, age, profile_picture, role FROM tokens JOIN users ON users.id = tokens.user_id WHERE tokens.user_id = users.id AND tokens.token = ?");
$stmt->bind_param('s', $token);
if ($stmt->execute()) {
  $user = $stmt->get_result()->fetch_assoc();
}


// untuk mengatasi kompalinan baru dari user //
if (isset($_POST['submit_pengaduan'])) {
  $judul = mysqli_real_escape_string($con, $_POST['judul']);
  $isi = mysqli_real_escape_string($con, $_POST['isi_pengaduan']);
  $user_id = $user['id'];

  $insert_query = "INSERT INTO pengaduan (user_id, judul, isi_pengaduan, status) 
                     VALUES (?, ?, ?, 'Pending')";

  if ($stmt = mysqli_prepare($con, $insert_query)) {
    mysqli_stmt_bind_param($stmt, "iss", $user_id, $judul, $isi);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
      $success_message = "Pengaduan berhasil dikirim!";
    } else {
      $error_message = "Gagal mengirim pengaduan. Silakan coba lagi.";
    }
    mysqli_stmt_close($stmt);
  }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>HOME</title>
  <link rel="stylesheet" href="gaya.css">
</head>

<body>
  <nav class="nav">
    <div class="user-info">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
        <path d="M9.74462 21.7446C5.30798 20.7219 2 16.7473 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 16.7473 18.692 20.7219 14.2554 21.7446L12 24L9.74462 21.7446ZM7.01173 18.2567C7.92447 18.986 9.00433 19.5215 10.1939 19.7957L10.7531 19.9246L12 21.1716L13.2469 19.9246L13.8061 19.7957C15.0745 19.5033 16.2183 18.9139 17.1676 18.1091C15.8965 16.8078 14.1225 16 12.1597 16C10.1238 16 8.29083 16.8692 7.01173 18.2567ZM5.61562 16.8214C7.25644 15.0841 9.58146 14 12.1597 14C14.644 14 16.8931 15.0065 18.5216 16.634C19.4563 15.3185 20 13.7141 20 12C20 7.58172 16.4183 4 12 4C7.58172 4 4 7.58172 4 12C4 13.7964 4.59708 15.4722 5.61562 16.8214ZM12 13C9.79086 13 8 11.2091 8 9C8 6.79086 9.79086 5 12 5C14.2091 5 16 6.79086 16 9C16 11.2091 14.2091 13 12 13ZM12 11C13.1046 11 14 10.1046 14 9C14 7.89543 13.1046 7 12 7C10.8954 7 10 7.89543 10 9C10 10.1046 10.8954 11 12 11Z"></path>
      </svg>
      <span>Welcome, <?php echo htmlspecialchars($user['username']); ?></span>
    </div>
    <div class="nav-links">
      <a href="profile.php" class="btn">
        Change Profile
      </a>
      <form action="php/logout.php" method="post" style="display: inline;">
        <button type="submit" class="btn">
          Logout
        </button>
      </form>
    </div>
  </nav>

  <div class="main-container">
    <?php if (isset($success_message)): ?>
      <div class="alert alert-success">
        <?php echo $success_message; ?>
      </div>
    <?php endif; ?>

    <?php if (isset($error_message)): ?>
      <div class="alert alert-error">
        <?php echo $error_message; ?>
      </div>
    <?php endif; ?>

    <div class="complaint-form">
      <h2> Buat Pengaduan Baru</h2>
      <form method="POST" action="">
        <div class="form-group">
          <label for="judul">Judul Pengaduan:</label>
          <input type="text" id="judul" name="judul" class="form-control" required>
        </div>
        <div class="form-group">
          <label for="isi_pengaduan">Isi Pengaduan:</label>
          <textarea id="isi_pengaduan" name="isi_pengaduan" class="form-control" rows="4" required></textarea>
        </div>
        <button type="submit" name="submit_pengaduan" class="btn btn-primary">
          Kirim Pengaduan
        </button>
      </form>
    </div>
  </div>
</body>

</html>