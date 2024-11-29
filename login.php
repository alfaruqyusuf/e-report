
<?php
session_start();
include("./config.php");
if (isset($_SESSION['token'])) {
  header("Location: home.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login</title>
  <link rel="stylesheet" href="gaya.css" />

<body>
  <section class="bdy">
    <div class="container">
      <div class="box form-box">

        <?php

        if (isset($_POST['submit'])) {
          $email = mysqli_real_escape_string($con, $_POST['email']);
          $password = mysqli_real_escape_string($con, $_POST['password']);

          $result = mysqli_query($con, "SELECT * FROM users WHERE email = '$email'");
          $row = mysqli_fetch_assoc($result);
          // LOOK FOR THE USER BASED ON EMAIL 
          if ($row) {
            if (($email !== $row['email'])) {
              echo "<div class='message'>
            <p>Email tidak terdaftar</p>
            </div> <br>";
              echo "<a href='login.php'><button Class='btn'> GO BACK</button>";
            } else {
              if (password_verify($password, $row['password'])) {
                $token = generateToken($row['id']);
                if ($token) {
                  $_SESSION['token'] = $token;
                  // $_SESSION['email'] = $row['email'];
                  header("Location: home.php");
                }
              } else {
                echo "<div class='message'>
                    <p>Password kamu salah</p>
                    </div> <br>";
                echo "<a href='login.php'><button Class='btn'> GO BACK</button>";
              }
            }
          }


          if (is_array($row) && !empty($row)) {
            $_SESSION['email'] = $row['email'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['password'] = $row['password'];
          } else {
            echo "<div class='error'>
            <p>Nama kamu salah atau  kata sandi-nya salah</p>
            </div> <br>";
            echo "<a href='login.php'><button Class='btn'> GO BACK</button>";
          }
        } else {

        ?>

          <header>Login</header>
          <form action="" method="post">


            <div class="field input">
              <label for="Email">Email:</label>
              <input type="text" name="email" id="email" required />
            </div>

            <div class="field input">
              <label for="Password">Password:</label>
              <input type="password" name="password" id="password" required />
            </div>

            <div class="field">
              <input
                type="submit"
                class="btn"
                name="submit"
                value="Login"
                required />
            </div>

            <div class="links">
              Tidak Mempunya akun? <a href="register.php">Sign Up Now</a>
            </div>
          </form>
      </div>
    <?php } ?>
    </div>
  </section>
</body>

</html>
