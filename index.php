<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>order form</title>
    <!-- juts a little bit of style -->
    <style>
        * {
            margin: 2px;
        }

        .container {
            border: 1px solid grey;
            width: 600px;
            padding: 4px;
        }

        .box {
            border: 1px solid grey;
            padding: 4px;
        }
    </style>
</head>

<body>
    <div class="container">
        <span><a href="?page=order">Place new order</a></span>
        <span><a href="?page=edit">Edit orders</a></span>
        <span><a href="?page=print">Print orders</a></span>
    </div>
    <div class="container">
        <?php
        // setting navigation variable and including required php file
        if (isset($_GET['page'])) {
            $page = ($_GET['page']);
        } else {
            $page = 'order';
        }
        switch ($page) {
            case 'edit':
                include 'editor.php';
                break;
            case 'print':
                include 'print.php';
                break;
            case 'order':
                include 'order.php';
                break;
        }
        ?>
    </div>
</body>

</html>