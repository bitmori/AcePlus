<!DOCTYPE HTML>
<html>
<head>
    <!-- Load jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <!-- Load custom js -->
    <script src="profile.js"></script>
</head>
</html>
<?php
/**
 * Created by PhpStorm.
 * User: yimingjiang
 * Date: 12/8/13
 * Time: 8:46 PM
 */

    $con = mysqli_connect("localhost","root","","ace-plus_maindb");

    // Check connection
    if (mysqli_connect_errno($con)) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

    //mysqli_query($con, "TRUNCATE TABLE USER");
    $id = 1; // update record to user with this id

    if (isset($_POST['last_name'])) {
        $last_name_string = mysqli_real_escape_string($con, $_POST['last_name']);
        // do update
        $sql = "UPDATE USER SET lastname = \"";
        $sql .= $last_name_string;
        $sql .= "\" WHERE id =";
        $sql .= $id;
        mysqli_query($con,$sql);
    }

    if (isset($_POST['first_name'])) {
        $first_name_string = mysqli_real_escape_string($con, $_POST['first_name']);
        // do update
        $sql = "UPDATE USER SET firstname = \"";
        $sql .= $first_name_string;
        $sql .= "\" WHERE id =";
        $sql .= $id;
        mysqli_query($con,$sql);
    }

    if (isset($_POST['birthday'])) {
        $birthday_string = mysqli_real_escape_string($con, $_POST['birthday']);
        // do update
        $sql = "UPDATE USER SET birthday = \"";
        $sql .= $birthday_string;
        $sql .= "\" WHERE id =";
        $sql .= $id;
        mysqli_query($con,$sql);
    }

    if (isset($_POST['signature'])) {
        $signature_string = mysqli_real_escape_string($con, $_POST['signature']);
        // do update
        $sql = "UPDATE USER SET personal_signature = \"";
        $sql .= $signature_string;
        $sql .= "\" WHERE id =";
        $sql .= $id;
        mysqli_query($con,$sql);
    }

    echo "Your profile has saved. Thank you.";