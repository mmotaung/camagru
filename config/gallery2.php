<?php

session_start();
require_once('database.php');

if (isset($_SESSION['username']))
{

    $user = $_SESSION['username'];
    $email = $_SESSION['email'];

   
    $user_image = $_GET['picname'];
    $user_pic_name = $_GET['liker'];
    $user_pic_name2 = $_GET['user'];

    if (isset($_POST['like']))
    {
        
        try
        {
            $conn3 = new PDO($db_dsn, $db_username, $db_password);
            $conn3->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt3 = $conn3->prepare("SELECT * FROM likes WHERE picname = :picname AND liker = :liker");
            $stmt3->execute(array(':picname' => $user_image, ':liker' => $user_pic_name));

            $results3 = $stmt3->fetchAll();
            if (count($results3))
            {
                header("Location: ../gallery.php?not_allowed");
                exit();
            }
        }
        catch (PDOException $e)
        {
            header("Location: ../gallery.php?server_error");
            exit();
        }
        try
        {
            $conn = new PDO($db_dsn, $db_username, $db_password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         
            $stmt = $conn->prepare("INSERT INTO likes (picname, liker, user_image)
            VALUES (:picname, :liker, :user_image)");

            $stmt->execute(array(':picname' => $user_image, ':liker' => $user, ':user_image' => $user_pic_name));
            header("Location: ../gallery.php?liked");
            exit();
        }
        catch(PDOException $e)
        {
            header("Location: ../gallery.php?server_error");
            exit();
        }
    }
    else if (isset($_POST['comment']))
    {
        $comment = $_POST['comment'];
        if ($comment == "")
        {
            header("Location: ../gallery.php?no_comment");
            exit();
        }
        
        //sending the comment to user and to the database

        try
        {
            $conn = new PDO($db_dsn, $db_username, $db_password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            //getting email of the user
            $stmt = $conn->prepare("SELECT * FROM users WHERE user_name = :username");
            $stmt->execute(array(':username' => $user_pic_name2));

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $pic_email = $row['email'];
            $noti = $row['notify'];

            $stmt = $conn->prepare("INSERT INTO comments (name, user_name, user_image, comment)
            VALUES (:name, :user_name, :user_image, :comment)");

            $stmt->execute(array(':name' => $user_image, ':user_name' => $user, 'user_image' => $user_pic_name2, ':comment' => $comment));

            //Sending email to image user
            if ($noti == 1)
            {
                $to = $pic_email;
                $subject = "Notifications: New comment";
                $msg = $user." commented on your picture:\n\n".$comment;
                $headers = 'From: noreply@camagru.com';
                mail($to, $subject, $msg, $headers);
                header("Location: ../gallery.php?comment_sent");
                exit();
            }
            else
            {
                header("Location: ../gallery.php?comment_sent");
                exit();
            }
        }
        catch(PDOException $e)
        {
            header("Location: ../gallery.php?server_error");
            exit();
        }

    }
    else
    {
        header("Location: ../gallery.php?no_comment");
        exit();
    }
}
else
{
    header("Location: ../gallery.php?hacker_vibes");
    exit();
}
?>