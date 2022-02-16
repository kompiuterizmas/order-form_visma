<?php
echo 'Select date for report generating';
$year = date("Y");
$nextYear = $year + 1;
?>
<form action="index.php?page=print" method="GET">
    <input type="hidden" name="page" value="print">
    <select name="year">
        <option value="<?php echo $year ?>"><?php echo $year; ?></option>
        <option value="<?php echo $nextYear; ?>"><?php echo $nextYear; ?></option>
    </select>
    <select name="month">
        <option value=''>Select Month</option>
        <?php for ($i = 1; $i <= 12; $i++) {
            echo '<option value="' . $i . '">' . $i . '</option>';
        } ?>
    </select>
    <select name='day'>
        <option value=''>Select day</option>
        <?php for ($i = 1; $i <= 31; $i++) {
            echo '<option value="' . $i . '">' . $i . '</option>';
        } ?>
    </select>
    <button type="submit" name="printing">Print orders</button>
</form>
<div class="box">
    <h3>Results will be displayed here:</h3>
    <?php
    include 'printing.php';
    ?>
</div>