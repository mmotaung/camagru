<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/style.css">
    <title>Gallery</title>
</head>
<body>
      <!--Header out here-->
  <div class="topnav" id="myTopnav">
    <a href="index.php">Home</a>
    <a href="gallery.php">Gallery</a>
    <a class="log" href="login.php?user=log">Signout</a>
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
    <h1>camagru</h1>
</div>
<div class="footer">
          <p>mmotaung &copy camagru 2019</p>
</div>
    </body>
    </html>

<?php

require_once ("config/database.php");
session_start();

if (isset($_GET['no_comment']))
{
  echo "<script>alert('Comment empty. Write something before submitting')</script>";
}
else if (isset($_GET['hacker_vibes']))
{
  echo "<script>alert('You are not authorised to enter this page. Sorry :)')</script>";
}
else if (isset($_GET['comment_sent']))
{
  echo "<script>alert('Comment sent :)')</script>";
}
else if (isset($_GET['server_error']))
{
  $msg = $_GET['msg'];
  echo "<script>alert('Server has gone away. :( ".$msg."')</script>";
}
else if (isset($_GET['not_allowed']))
{
  echo "<script>alert('You can only like a picture once!. :)')</script>";
}

try
{
  $conn = new PDO($db_dsn, $db_username, $db_password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $stmt = $conn->prepare("SELECT * FROM pictures");
  $stmt->execute();

  $results = $stmt->fetchAll();
  $results_per_page = 5;
  $number_of_results = count($results);

  $number_of_pages = ceil($number_of_results / $results_per_page);

  if (!isset($_GET['page']))
  {
    $page = 1;
  }
  else
  {
    $page = $_GET['page'];
  }
  
  $this_page_first_result = ($page - 1) * $results_per_page;

  $stmt = $conn->prepare("SELECT * FROM pictures ORDER BY user_id DESC LIMIT ". $this_page_first_result. ',' .$results_per_page);
  $stmt->execute();

  while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
  {
    $pic = $row['name'];
    $user = $row['user'];

    try 
    {
      $conn2 = new PDO($db_dsn, $db_username, $db_password);
      $conn2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $stmt2 = $conn2->prepare("SELECT * FROM likes WHERE picname = :picname");
      $stmt2->execute(array(':picname' => $pic));

      $likes = $stmt2->fetchAll();
      $likes = count($likes);
    }
    catch (PDOException $e)
    {
      header("Location: gallery.php?server_error");
      exit();
    }

    if (isset($_SESSION['username']))
    {
      $user1 = $_SESSION['username'];
      echo "<form method='post' class='form4'>
      <p>By ".$user."</p>
      <img height='300' width='400' src='".$pic."'>
      <button formaction='config/gallery2.php?picname=".$pic."&liker=".$user1."' type='submit' name='like' value='1'>Like(".$likes.")</button>
      <input type='text' name='comment'>
      <button formaction='config/gallery2.php?picname=".$pic."&user=".$user1."' type='submit' name='submit'>Comment</button>
      </form><br/>";
    }
    else
    {
      echo "<form method='post' class='form4'>
      <img height='300' width='400' src='".$pic."'>
      </form><br/>";
    }
  }

  for ($page = 1; $page <= $number_of_pages; $page++)
  {
    echo '<form class="form4">
      <a class="page_number" href="gallery.php?page=' .$page. '">' . $page . '</a>
      </form>'."  ";
  }
  echo "<br/><br/><br/><br/>";
}
catch(PDOException $e)
{
  header("Location: gallery.php?server_error");
  exit();
}
?>