
<?php
session_start();
include("php/config.php");
if (!isset($_SESSION['token'])) {
  header("Location:login.php");
  exit();
} else {
  if (!validateToken($_SESSION['token'])) {
    header("Location:login.php");
    exit();
  }
}

if (isset($_POST['submit'])) {
  $username = $_POST['username'];
  $email = $_POST['email'];
  $age = $_POST['age'];

  $email = $_SESSION['email'];
  $edit_query = mysqli_query($con, "UPDATE users SET username = '$username', email = '$email', age = '$age' WHERE email = '$email'");

  if ($edit_query) {
    $_SESSION['update_success'] = true; 
    header("Location: home.php");
    exit();
  } else {
    $error_message = "Gagal mengupdate profil. Silakan coba lagi.";
  }
} else {
  $email = $_SESSION['email'];
  $query = mysqli_query($con, "SELECT * FROM users WHERE email = '$email'");

  while ($result = mysqli_fetch_assoc($query)) {
    $res_Uname = $result['username'];
    $res_Email = $result['email'];
    $res_Age = $result['age'];
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="gaya.css">
  <title>Change Profile</title>
</head>

<body>
  <div class="nav">
    <div class="image">
      <a href="home.php"><img src="image/guung.jpg" alt=""></a>
    </div>

    <div class="right-links">
      <a href="#">Change Profile</a>
      <form action="php/logout.php" method="post" style="display: inline;">
        <button type="submit" class="btn">Logout</button>
      </form>
    </div>
  </div>
 

  <section class="bdy">
    <div class="container">
      <div class="box form-box">
        <?php if (isset($error_message)): ?>
          <div class="message error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <header>Change Profile</header>
        <form action="" method="post">
          <div class="field input">
            <label for="username">Username:</label>
            <input
              type="text"
              name="username"
              value="<?php echo isset($res_Uname) ? htmlspecialchars($res_Uname) : ''; ?>"
              id="username"
              required
            />
          </div>

          <div class="field input">
            <label for="email">Email:</label>
            <input
              type="text"
              name="email"
              value="<?php echo isset($res_Email) ? htmlspecialchars($res_Email) : ''; ?>"
              id="email"
              required
            />
          </div>

          <div class="field input">
            <label for="age">Age:</label>
            <input
              type="number"
              name="age"
              value="<?php echo isset($res_Age) ? htmlspecialchars($res_Age) : ''; ?>"
              id="age"
              required
            />
          </div>

          <div class="field">
            <input
              type="submit"
              class="btn"
              name="submit"
              value="Update"
              required
            />
          </div>
        </form>
      </div>
    </div>
  </section>
</body>

</html> 
