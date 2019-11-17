<?php
session_start();

if (isset($_GET['signup']) && $_GET['signup'] == "u_name")
{
  echo ("<script>alert('Username is not available')</script>");
}
else if (isset($_GET['signup']) && $_GET['signup'] == "email")
{
  echo ("<script>alert('The email entered has been used before')</script>;");
}
else if (isset($_GET['signup']) && $_GET['signup'] == "username")
{
  echo ("<script>alert('Username not available. Choose another one')</script>;");
}
else if (isset($_GET['code']) && $_GET['code'] == -1)
{
  echo ("<script>alert('Error: Code is invalid')</script>;");
}
else if (isset($_GET['signup']) && $_GET['signup'] == "empty")
{
  echo ("<script>alert('Required fields are empty')</script>;");
}
else if (isset($_GET['verify']) && $_GET['verify'] == 0)
{
  echo ("<script>alert('A verification link has been sent to your email')</script>");
}
else if (isset($_GET['signup']) && $_GET['signup'] == "invalid")
{
  echo "<script>alert('Invalid username entered');</script>";
}
else if (isset($_GET['forgot']) && $_GET['forgot'] == 1)
{
  echo ("<script>alert('A reset link has been sent to your email');</script>");
}
else if (isset($_GET['pas']) && $_GET['pas'] == "weak")
{
  echo ("<script>alert('Password too short. Password must be 8 or more characters, have atleast one lowercase and one uppercase letter');</script>");
}
else if (isset($_GET['con']))
{
  echo ("<script>alert('Connection to the server failed');</script>");
}
else if (isset($_GET['user']) && $_GET['user'] == "log")
{
    if (!isset($_SESSION['username']))
    {
        echo ("<script>alert('Please signin/signup first');</script>");
    }
    else
    {
        session_destroy();
        echo ("<script>alert('Logged out successfully');</script>");
    }
}
else if (isset($_SESSION['email']) && $_SESSION['email'] != "")
{
  require_once('config/database.php');

  $user = $_SESSION['email'];

  try
  {
    $conn = new PDO($db_dsn, $db_username, $db_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare('SELECT * FROM users WHERE email = :email');
    $stmt->execute(array(':email' => $user));

    $result = $stmt->fetchAll();
    if (count($result))
    {
      foreach($result as $row)
      if ($row['email'] == $user)
      {
        header("Location: camera.php");
        exit();
      }
      else
      {
        session_destroy();
        header("Location: index.php");
        exit();
      }
    }
    else
    {
      session_destroy();
      header("Location: index.php");
      exit();
    }
  }
  catch(PDOExceptio $e)
  {
    echo "<script>alert('Error trying to connect to server. Check your internet connection')</script>";
    session_destroy();
  }
}

?>

<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
  <title>Signup</title>
      <link rel="stylesheet" href="css/style.css">
</head>

<body>
  <div class="topnav" id="myTopnav">
    <a href="index.php">Home</a>
    <a href="gallery.php">Gallery</a>
    <a class="log" href="index.php?user=log">Signout</a>
    <a href="javascript:void(0);" style="font-size:15px;" class="icon" onclick="doThings()">&#9776;</a>
    </div>

    <script>
    function doThings()
    {
      var res = document.getElementById("myTopnav");
      if (res.className === "topnav") 
      {
        res.className += " responsive";
        } 
        else 
        {
          res.className = "topnav";
          }
          }
</script>
<div class="pen-title">
    <h1>CAMAGRU</h1><span> <i class='fa fa-code'></i> </span>
</div>

<div class="module form-module">
  <div class="toggle"><i class="fa fa-times fa-pencil"></i>
  </div>
  <div class="form">
    <h2>SIGNUP</h2>
    <form action="config/signup.php" method="POST">
      <input type="text" name="user_name" placeholder="USERNAME"/>
      <input type="password" name="passwd" placeholder="PASSWORD"/>
      <input type="email" name="email" placeholder="EMAIL ADDRESS"/>
      <button type="submit" name="submit">Signup</button><br/>
      <button formaction="login.php">Signin</button>
    </form>
  </div>
</div>

<div class="footer">
          <p>mmotaung &copy camagru 2019</p>
</div>
</body>
</html>