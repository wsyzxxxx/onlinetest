<?php

function get_username_or_redirect()
{
    session_start();

    if (!isset($_SESSION["username"]))
    {
        header("location: login.php");
        exit();
    }
    else {
        return $_SESSION["username"];
    }
}

function insert_user($username, $dbh)
{
    $sth = $dbh->prepare("INSERT INTO student (username) VALUES (:username)");
    $sth->execute([":username" => $username]);

}

function get_grade_by_id($sid, $dbh)
{
    $sth = $dbh->prepare("SELECT * FROM grade WHERE sid=:sid LIMIT 1");
    $sth->execute([":sid" => $sid]);
    $row = $sth->fetch(PDO::FETCH_ASSOC);

    return $row;
}

function get_user_info_by_id($id, $dbh)
{
    $sth = $dbh->prepare("SELECT * FROM student WHERE id=:id LIMIT 1");
    $sth->execute([":id" => $id]);
    $row = $sth->fetch(PDO::FETCH_ASSOC);

    return $row;
}

function get_user_info_by_username($username, $dbh)
{
    $sth = $dbh->prepare("SELECT * FROM student WHERE username=:username LIMIT 1");
    $sth->execute([":username" => $username]);
    $row = $sth->fetch(PDO::FETCH_ASSOC);

    return $row;
}

function user_login($id, $username)
{
    $_SESSION["id"] = $id;
    $_SESSION["username"] = $username;
}
 ?>
