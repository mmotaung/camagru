<?php

include_once ('config/database.php');

if (isset($_GET['verify']) && $_GET['verify'] == 1 && isset($_GET['email']) && isset($_GET['code']) && isset($_GET['com']))
{
$user = $_GET['email'];
$code = $_GET['code'];

try
{
    //veryfying the user in the database
    $conn = new PDO($db_dsn, $db_username, $db_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(array(':email' => $user));

    //getting the row
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (count($result))
    {
        foreach($result as $row)
        {
            if ($row['con_hash'] == $code)
            {
                try
                {
                    $conn = new PDO($db_dsn, $db_username, $db_password);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $stmt = $conn->prepare('UPDATE users SET active = :active WHERE email = :email');
                    $stmt->execute(array(':active' => '1', ':email' => $user));

                    //Updating the con_hash
                    $new_code = hash('whirlpool', rand(0,100000));
                    $stmt = $conn->prepare('UPDATE users SET con_hash = :new_code WHERE email = :email');
                    $stmt->execute(array(':new_code' => $new_code, ':email' => $user));
                    echo ("<script>alert('Account is now active!')</script>");
                }
                catch(PDOException $e)
                {
                    header("Location: index.php?con=error");
                    exit();
                }
            }
            else
            {
                header("Location: index.php?code=-1");
                exit();
            }
        }
    }
    else
    {
        header("Location: index.php?code=-1");
        exit();
    }
}
catch(PDOException $e)
{
    header("Location: index.php?con=error");
    exit();
}
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="pen-title">
        <h1>Camagru</h1>
    </div>
    
    <div class="module form-module">
        <div class="toggle">
        </div>
        <div class="form">
            <h2>You can now log in to your account :)</h2>
            <form action="" method="POST">
                <button formaction="login.php">Login</button>
            </form>
        </div>
    </div> 
    <div class="footer">
          <p>mmotaung &copy camagru 2019</p>
</div> 
</body>
</html>