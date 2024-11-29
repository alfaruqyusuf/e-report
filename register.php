
<?php
session_start();
include("./config.php");
if (isset($_SESSION['token'])) {
  header("Location:home.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="gaya.css">
  <title>REGISTER</title>
</head>

<body>
  <section class="bdy">
    <div class="container">
      <div class="box form-box">



        <?php


        if (isset($_POST['submit'])) {
          $username = $_POST['username'];
          $email = $_POST['email'];
          $age = $_POST['age'];
          $hased_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

          //verifying the unique email//
          $verify_query = mysqli_query($con, "SELECT email FROM users WHERE email = '$email'");

          if (mysqli_num_rows($verify_query) != 0) {
            echo "<div class='error'>
            <p>Email ini sudah dipakai oleh seseorang, coba gunakan Email yang lain please!</p>
            </div> <br>";
            echo "<a href='javascript:self.history.back()'><button Class='btn'> GO BACK</button>";
          } else {
            mysqli_query($con, "INSERT INTO users(username, email,age,password) VALUES('$username','$email','$age','$hased_password')");
            echo "<div class='message'>
            <p>REGISTRASI kamu sudah berhasil dan sukses</p>
            </div> <br>";
            echo "<a href='login.php'><button  Class='btn'> LOGIN SEKARANG </button>";
          }
        } else {



        ?>
          <header>Sign Up</header>
          <form action="" method="post">
            <div class="field input">
              <label for="username">Username:</label>
              <input
                type="text"
                name="username"
                id="username"

                required />
            </div>

            <div class="field input">
              <label for="Email">Email:</label>
              <input
                type="text"
                name="email"
                id="email"

                required />
            </div>

            <div class="field input">
              <label for="Age">Age:</label>
              <input
                type="number"
                name="age"
                id="age"

                required />
            </div>

            <div class="field input">
              <label for="Password">Password</label>
              <input
                type="password"
                name="password"
                id="password"

                required />
            </div>

            <div class="field">
              <input
                type="submit"
                class="btn"
                name="submit"
                value="Register"
                required />
            </div>

            <div class="links">
              Sudah Punya Akun? <a href="login.php">Sign In</a>
            </div>
          </form>
      </div>
    <?php } ?>
    </div>
  </section>
</body>

</html>
