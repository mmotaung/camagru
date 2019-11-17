<?php

require_once('database.php');
session_start();

if (isset($_SESSION['username']))
{
    if (isset($_GET['status']))
    {
        $status = $_GET['status'];
        if ($status == "on")
        {
            $n = 1;
        }
        else
        {
            $n = 0;
        }
        $username = $_SESSION['username'];

        //updating notification settings
        try
        {
            $conn = new PDO($db_dsn, $db_username, $db_password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("UPDATE users SET notify = :n WHERE user_name = :username");
            $stmt->execute(array(':n' => $n, ':username' => $username));
            header("Location: ../notify.php?updated");
        }
        catch(PDOException $e)
        {
            header("Location: ../notify.php?server_error");
            exit();
        }
    }
    else
    {
        header("Location: ../notify.php?error");
        exit();
    }
}
else
{
    header("Location: ../index.php?user=log");
    exit();
}
?>