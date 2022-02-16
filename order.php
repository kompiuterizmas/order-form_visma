<?php
echo '<h3>New order</h3>';
// selecting current year
$year = date("Y");
// setting next year
$nextYear = $year + 1;
// checking if date value returned by validator and converting it to array
if (isset($_GET['date'])) {
    $darr = explode("-", ($_GET['date']));
}
// checking for message and printing it
if (isset($_GET['message'])) {
    echo ($_GET['message']);
}
?>
<form action="validator.php" method="POST">
    <input type="text" name="name" value="<?php if (isset($_GET['name'])) {
                                                echo ($_GET['name']);
                                            } ?>" placeholder="Your name"><br>
    <input type="text" name="email" value="<?php if (isset($_GET['email'])) {
                                                echo ($_GET['email']);
                                            } ?>" placeholder="E-mail"><br>
    <input type="text" name="address" value="<?php if (isset($_GET['address'])) {
                                                    echo ($_GET['address']);
                                                } ?>" placeholder="Address"><br>
    <input type="number" name="phone" value="<?php if (isset($_GET['phone'])) {
                                                    echo ($_GET['phone']);
                                                } ?>" placeholder="Phone number"><br>
    <select name="year">
        <option value="<?php echo $year ?>"><?php echo $year; ?></option>
        <option value="<?php echo $nextYear; ?>"><?php echo $nextYear; ?></option>
    </select>
    <select name="month">
        <option value=''>Select Month</option>
        <?php for ($i = 1; $i <= 12; $i++) {
            if (isset($darr) && $darr[1] == $i) {
                echo '<option value="' . $i . '" selected>' . $i . '</option>';
            } else {
                echo '<option value="' . $i . '">' . $i . '</option>';
            }
        } ?>
    </select>
    <select name='day'>
        <option value='' disabled selected>Select day</option>
        <?php for ($i = 1; $i <= 31; $i++) {
            if (isset($darr) && $darr[2] == $i) {
                echo '<option value="' . $i . '" selected>' . $i . '</option>';
            } else {
                echo '<option value="' . $i . '">' . $i . '</option>';
            }
        } ?>
    </select>
    <select name='hour'>
        <option value='' disabled selected>Select hour</option>
        <?php for ($i = 6; $i <= 19; $i++) {
            if (isset($darr) && $darr[3] == $i) {
                echo '<option value="' . $i . '" selected>' . $i . '</option>';
            } else {
                echo '<option value="' . $i . '">' . $i . '</option>';
            }
        }
        ?>
    </select>
    <select name='minutes'>
        <option value='' disabled selected>Select minutes</option>
        <?php for ($i = 1; $i <= 5; $i++) {
            if (isset($darr) && $darr[3] == $i) {
                echo '<option value="' . $i . '0" selected>' . $i . '0</option>';
            } else {
                echo '<option value="' . $i . '0">' . $i . '0</option>';
            }
        }
        ?>
    </select>
    <button type="submit" name="order-submit">Place order</button>
</form>