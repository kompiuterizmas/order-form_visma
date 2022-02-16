<?php
while (true) {
    $numb = "";
    while (empty($numb) || $numb !== 1 || $numb !== 2 || $numb !== 3 || $numb !== 4) {
        echo "---------------\n";
        echo "1 - place order\n";
        echo "2 - edit orders\n";
        echo "3 - print orders\n";
        echo "Please select required function by typing it's number: ";
        // clearing entry from accidental characters
        $numb = preg_replace("~[^1-3:]~i", "", fgets(STDIN, 1024));
        if ($numb == 2 or $numb == 1) {
            // selecting if it is order update
            if ($numb == 2) {
                // setting array of id's to compare with selection
                $arr = array();
                // preparing SQL statement
                include_once 'dbh.php';
                $sql = "SELECT * FROM orders ORDER BY id ASC";
                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    echo 'sqlerror';
                    exit();
                } else {
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    while ($row = mysqli_fetch_assoc($result)) {
                        array_push($arr, $row['id']);
                        echo "-------\n";
                        echo "id: " . $row['id'] . "\n";
                        echo "name: " . $row['name'] . "\n";
                        echo "email: " . $row['email'] . "\n";
                        echo "phone: " . $row['phone'] . "\n";
                        echo "address: " . $row['address'] . "\n";
                        echo "date: " . $row['date'] . "\n";
                    }
                    echo "----------------------------------\n";
                    echo "Insert order id from a list above: ";
                    // clearing input from accidental and unwanted characters
                    $entry = preg_replace("~[^0-9:]~i", "", fgets(STDIN, 1024));
                    // looping entry procedure till correct entry will be provided
                    while (!is_numeric($entry) || !in_array($entry, $arr)) {
                        echo "\nit is not an id, please try again: \n";
                        $entry = ctype_alnum(trim(str_replace(' ', '', fgets(STDIN, 1024))));
                    }
                    if (is_numeric($entry) && in_array($entry, $arr)) {
                        $order = $entry;
                    }
                }
            }
            echo "Please enter your name: ";
            $entry = preg_replace('/[^A-Za-z0-9 \-]/', '', (fgets(STDIN, 1024)));
            // validating entry step by step
            while (!ctype_alnum(trim(str_replace(' ', '', $entry)))) {
                echo "Entered name can not be accepted, please try again.\n";
                echo "Please enter your name: ";
                $entry = preg_replace('/[^A-Za-z0-9 \-]/', '', (fgets(STDIN, 1024)));
            }
            if (ctype_alnum(trim(str_replace(' ', '', $entry)))) {
                $name = $entry;
            }
            if (isset($name)) {
                echo "please enter E-mail address: ";
                $entry = filter_var(fgets(STDIN, 1024), FILTER_SANITIZE_EMAIL);
                while (filter_var($entry, FILTER_VALIDATE_EMAIL) === false) {
                    echo "Entered E-mail can not be accepted, please try again.\n";
                    echo "Please enter your E-mail: ";
                    $entry = filter_var(fgets(STDIN, 1024), FILTER_SANITIZE_EMAIL);
                }
            }
            if (filter_var($entry, FILTER_VALIDATE_EMAIL)) {
                $email = $entry;
            }
            if (isset($email)) {
                echo "please enter phone number: ";
                $entry = preg_replace("~[^0-9:]~i", "", fgets(STDIN, 1024));
                while ((strlen((string)$entry) < 8 || strlen((string)$entry) > 11) || (!is_numeric($entry))) {
                    echo "Entered phone number is not correct, please try again.\n";
                    echo "Please enter your phone number: ";
                    $entry = preg_replace("~[^0-9:]~i", "", fgets(STDIN, 1024));
                }
            }
            if ((strlen((string)$entry) >= 8 || strlen((string)$entry) <= 11) && (is_numeric($entry))) {
                $phone = $entry;
            }
            if (isset($phone)) {
                echo "please enter your address: ";
                $entry = preg_replace('/[^A-Za-z0-9 \-]/', '', (fgets(STDIN, 1024)));
                while (!ctype_alnum(trim(str_replace(' ', '', $entry)))) {
                    echo "Entered address can not be accepted, please try again.\n";
                    echo "Please enter your address: ";
                    $entry = preg_replace('/[^A-Za-z0-9 \-]/', '', (fgets(STDIN, 1024)));
                }
            }
            if (ctype_alnum(trim(str_replace(' ', '', $entry)))) {
                $address = $entry;
            }
            $currentDate = new DateTime("now", new DateTimeZone('Europe/Vilnius'));
            if (isset($address)) {
                echo "please enter desired service date in format YYYYmmddHHmm: ";
                $entry = preg_replace("~[^0-9:]~i", "", fgets(STDIN, 1024));
                while ($entry <= $currentDate->format("YmdHi") || (!is_numeric($entry))) {
                    echo "Entered date can not be accepted, please try again.\n";
                    echo "Please enter desired service date: ";
                    $entry = preg_replace("~[^0-9:]~i", "", fgets(STDIN, 1024));
                }
            }
            if ((is_numeric($entry)) && ($entry > $currentDate->format("YmdHi"))) {
                $date = date('Y-m-d H-i', strtotime($entry));
            }
            if (isset($date)) {
                echo "please check if all data entered properly: \n";
                echo "Name: " . $name . "\n";
                echo "E-mail: " . $email . "\n";
                echo "Phone number: " . $phone . "\n";
                echo "Address: " . $address . "\n";
                echo "Service date: " . $date . "\n";
                echo "Y/N: ";
                $entry = strtolower(fgets(STDIN, 1024));
                // checking if user confirm all data provided
                if (isset($entry) && (trim(str_replace(' ', '', $entry)) == "y")) {
                    include_once 'dbh.php';
                    // selecting is it new order or update
                    if ($numb == 2) {
                        $sql = "UPDATE orders SET name=?, email=?, phone=?, address=?, date=? WHERE id=?";
                    } elseif ($numb == 1) {
                        $sql = "INSERT INTO orders (name, email, phone, address, date) VALUES (?, ?, ?, ?, ?)";
                    }
                    $stmt = mysqli_stmt_init($conn);
                    if (!mysqli_stmt_prepare($stmt, $sql)) {
                        echo "\nserver connection error\n";
                    } else {
                        // selecting is it new order or update
                        if ($numb == 2) {
                            mysqli_stmt_bind_param($stmt, "ssssss", $name, $email, $phone, $address, $date, $order);
                        } elseif ($numb == 1) {
                            mysqli_stmt_bind_param($stmt, "sssss", $name, $email, $phone, $address, $date);
                        }
                        mysqli_stmt_execute($stmt);
                        echo "----------------------------\n";
                        // selecting is it new order or update
                        if ($numb == 2) {
                            echo "order successfully updated.\n";
                        } elseif ($numb == 1) {
                            echo "order successfully inserted.\n";
                        }
                    }
                } else {
                    echo "order cancelled.";
                }
            }
        } elseif ($numb == 3) {
            echo "--------------------------------\n";
            echo "Enter a date for report in format yyyymmdd: ";
            // validating entry, only integers are acceptable
            $entry = preg_replace("~[^0-9:]~i", "", fgets(STDIN, 1024));
            while (!is_numeric($entry)) {
                echo "Entered date can not be accepted, please try again.\n";
                echo "Enter a date for report in format yyyymmdd: ";
                $entry = fgets(STDIN, 1024);
            }
            if (is_numeric($entry)) {
                // converting entry into date object and generating date plus 1 variable for query purposes
                $entryplus = $entry + 1;
                $x = date_create_from_format('Ymd', $entry);
                $y = date_create_from_format('Ymd', $entryplus);
                $input1 = $x->format('Y-m-d');
                $input2 = $y->format('Y-m-d');
                include_once 'dbh.php';
                $sql = "SELECT * FROM orders WHERE date>? AND date<? ORDER BY date ASC";
                $stmt = mysqli_stmt_init($conn);
            }
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                echo 'sqlerror';
                exit();
            } else {
                mysqli_stmt_bind_param($stmt, "ss", $input1, $input2);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "id: " . $row['id'] . ".\n";
                    echo "Name: " . $row['name'] . ".\n";
                    echo "Email: " . $row['email'] . ".\n";
                    echo "Phone: " . $row['phone'] . ".\n";
                    echo "Address: " . $row['address'] . ".\n";
                    echo "Time: " . $row['date'] . "\n";
                    echo "--------------------\n";
                }
            }
        }
    }
}
