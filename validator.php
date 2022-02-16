<?php
// connecting to SQL
require 'dbh.php';
// check if form DATA has been sent
if (isset($_POST['order-submit']) || isset($_POST['order-update']) || isset($_POST['order-delete'])) {
    // setting variables from input
    if (isset($_POST['id'])) {
        $id = ($_POST['id']);
    }
    if(isset($_POST['order-delete'])){
        $sql = "DELETE FROM orders WHERE id=?";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location: index.php?error=sqlerror" . $returnData);
            exit();
        }
        else {
            mysqli_stmt_bind_param($stmt, "s", $id);
            mysqli_stmt_execute($stmt);
            $message = 'Order successfully deleted from database';
            header("Location: index.php?order=success&message=" . $message);
            exit();
        }
    }
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $year = $_POST['year'];
    $month = sprintf("%02d", ($_POST['month']));
    $day = sprintf("%02d", ($_POST['day']));
    $hour = sprintf("%02d", ($_POST['hour']));
    $minutes = ($_POST['minutes']);
    $date = $year . '-' . $month . '-' . $day . '-' . $hour . '-' . $minutes;
    // defining current date according timezone
    $currentDate = new DateTime("now", new DateTimeZone('Europe/Vilnius'));
    echo $currentDate->format("Y-m-d-H-m");
    // setting filled DATA callback for user convenience
    $returnData = '&name=' . $name . '&email=' . $email . '&phone=' . $phone . '&address=' . $address . '&date=' . $date;
    // setting error message
    $message = 'Please check these elements if they where filled properly: ';
    // validating received DATA
    if (empty($name) || !ctype_alnum(trim(str_replace(' ', '', $name)))) {
        $nameCheck = 'error';
        $message .= 'name, ';
    } else {
        $nameCheck = 'accept';
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailCheck = 'error';
        $message .= 'email, ';
    } else {
        $emailCheck = 'accept';
    }
    if (empty($phone) || strlen((string)$phone) < 8 || strlen((string)$phone) > 11) {
        $phoneCheck = 'error';
        $message .= 'phone number, ';
    } else {
        $phoneCheck = 'accept';
    }
    if (empty($address) || !ctype_alnum(trim(str_replace(' ', '', $address)))) {
        $addressCheck = 'error';
        $message .= 'address, ';
    } else {
        $addressCheck = 'accept';
    }
    if (empty($date) || $date <= $currentDate->format("Y-m-d H-i")) {
        $dateCheck = 'error';
        $message .= 'date, ';
    } else {
        $dateCheck = 'accept';
    }
    if ($nameCheck === 'error' || $emailCheck === 'error' || $phoneCheck === 'error' || $addressCheck === 'error' || $dateCheck === 'error') {
        header("Location: index.php?error=invdata" . $returnData . '&message=' . $message);
        exit();
    } elseif (isset($_POST['order-submit'])) {
        // if no errors, creating prepared statement
        $sql = "INSERT INTO orders (name, email, phone, address, date) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location: index.php?error=sqlerror" . $returnData);
            exit();
        }
        // if SQL do not report error, executing statement and returning success message
        else {
            mysqli_stmt_bind_param($stmt, "sssss", $name, $email, $phone, $address, $date);
            mysqli_stmt_execute($stmt);
            $message = 'Order successfully added to database';
            header("Location: index.php?order=success&message=" . $message);
            exit();
        }
    } elseif (isset($_POST['order-update'])) {
        // if no errors, creating prepared statement
        $sql = "UPDATE orders SET name=?, email=?, phone=?, address=?, date=? WHERE id=?";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location: index.php?error=sqlerror" . $returnData);
            exit();
        }
        // if SQL do not report error, executing statement and returning success message
        else {
            mysqli_stmt_bind_param($stmt, "ssssss", $name, $email, $phone, $address, $date, $id);
            mysqli_stmt_execute($stmt);
            $message = 'Order successfully updated in database';
            header("Location: index.php?order=success&message=" . $message);
            exit();
        }
    }
    // closing connection
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
// if POST method was not executed, but some extra characters entered into address field, returning to main page
else {
    header("Location: index.php");
    exit();
}
