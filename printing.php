<?php
if (isset($_GET['printing'])) {
    $year = $_GET['year'];
    $month = sprintf("%02d", ($_GET['month']));
    $day = sprintf("%02d", ($_GET['day']));
    $date = $year . $month . $day;
    include_once 'dbh.php';
    $sql = "SELECT * FROM orders WHERE date>=? AND date<=?+1 ORDER BY date ASC";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo 'sqlerror';
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, "ss", $date, $date);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        while ($row = mysqli_fetch_assoc($result)) {
            echo
            "<div class='box'>Name: " . $row['name'] . ".<br>
        Email: " . $row['email'] . ".<br>
        Phone: " . $row['phone'] . ".<br>
        Address: " . $row['address'] . ".<br>
        Time: " . $row['date'] . "<br></div>";
        }
    }
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
