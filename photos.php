<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
  <title>Photos</title>
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
    <h1>Pictures</h1><span> <i class='fa fa-code'></i> </span>
</div>

<div class="container2">
    <?php
    session_start();

    if (isset($_SESSION['username']))
    {
        $user = $_SESSION['username'];
        $dir = "config/upload/".$user."/*.*";
        $files_uo = glob($dir);
        $files = array_reverse($files_uo);

        for ($i = 0; $i < count($files); $i++)
        {   
            $num = $files[$i];
            $exploded = end(explode("/", $num));
            $new_dir = "config/upload/".$exploded;
            echo "<form class='form4' method='POST'>
            <img height='300' width='400' id='display' src='".$num."' alt='image file' />
            <button formaction='config/delete.php?picname=".$new_dir."&user_dir=".$num."&filename=".$exploded."' type='submit' name='delete'>Delete</button>
            </form>";
        }
    }
    else
    {
        header("Location: login.php?user=log");
        exit();
    }
    ?>
</div>

<div class="footer">
          <p>mmotaung &copy camagru 2019</p>
</div>