<?php
include_once 'dbh.php';
echo '<h3>Edit orders</h3>';
$sql = "SELECT * FROM orders ORDER BY id ASC";
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql)) {
    echo 'sqlerror';
    exit();
} else {
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($result)) {
        $year = date("Y", strtotime($row['date']));
        $month = date("m", strtotime($row['date']));
        $day = date("d", strtotime($row['date']));
        $hour = date("H", strtotime($row['date']));
        $minutes = date("m", strtotime($row['date']));

        echo '<div class="box">
    <form action="validator.php" method="POST">
    <input type="hidden" name="id" value="' . $row["id"] . '"><br>
    <input type="text" name="name" value="' . $row["name"] . '" placeholder="Your name"><br>
    <input type="text" name="email" value="' . $row["email"] . '" placeholder="E-mail"><br>
    <input type="text" name="address" value="' . $row["address"] . '" placeholder="Address"><br>
    <input type="number" name="phone" value="' . $row["phone"] . '" placeholder="Phone number"><br>
    <select name="year">
    <option value="' . $year . '">' . $year . '</option>
    <option value="' . ($year + 1) . '">' . ($year + 1) . '</option>
    </select>
    <select name="month">
    <option value="">Select Month</option>';

        for ($i = 1; $i <= 12; $i++) {
            if ($month == $i) {
                echo '<option value="' . $i . '" selected>' . $i . '</option>';
            } else {
                echo '<option value="' . $i . '">' . $i . '</option>';
            }
        }

        echo '</select>
    <select name="day">
    <option value="">Select day</option>';

        for ($i = 1; $i <= 31; $i++) {
            if ($day == $i) {
                echo '<option value="' . $i . '" selected>' . $i . '</option>';
            } else {
                echo '<option value="' . $i . '">' . $i . '</option>';
            }
        }

        echo '</select>
    <select name="hour">
    <option value="">Select hour</option>';

        for ($i = 6; $i <= 19; $i++) {
            if ($hour == $i) {
                echo '<option value="' . $i . '" selected>' . $i . '</option>';
            } else {
                echo '<option value="' . $i . '">' . $i . '</option>';
            }
        }

        echo '</select>
    <select name="minutes">
    <option value="">Select minutes</option>';

        for ($i = 1; $i <= 5; $i++) {
            if ($minutes == $i) {
                echo '<option value="' . $i . '0" selected>' . $i . '0</option>';
            } else {
                echo '<option value="' . $i . '0">' . $i . '0</option>';
            }
        }

        echo '</select>
    <button type="submit" name="order-update">Update order</button>
    </form></div>';
    }
}
