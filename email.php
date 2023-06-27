<?php
require 'D:\XAMPP\htdocs\Fruityvice\vendor\phpmailer\phpmailer\src\PHPMailer.php';
require 'D:\XAMPP\htdocs\Fruityvice\vendor\phpmailer\phpmailer\src\SMTP.php';
require 'D:\XAMPP\htdocs\Fruityvice\vendor\phpmailer\phpmailer\src\Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Get Data from API
function getDataFromAPI() {
    $url = 'https://fruityvice.com/api/fruit/all';
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        $error_message = curl_error($ch);
        echo "Error: " . $error_message;
        curl_close($ch);
        exit;
    }

    curl_close($ch);

    $data = json_decode($response, true);
    if (!$data) {
        echo "No fruit data found.";
        exit;
    }

    return $data;
}

// Save Data in Database
function saveDataToDatabase($data) {
    $hostname = 'localhost';
    $database = 'fruityvice';
    $username = 'root';
    $password = '';
    $connection = mysqli_connect($hostname, $username, $password, $database);

    if (mysqli_connect_errno()) {
        $error_message = mysqli_connect_error();
        die("Failed to connect to MySQL: " . $error_message);
    }

    foreach ($data as $fruit) {
        $query = "SELECT COUNT(*) AS count FROM fruits WHERE id = " . $fruit['id'];
        $result = mysqli_query($connection, $query);
        $row = mysqli_fetch_assoc($result);
        $fruitCount = $row['count'];

        if ($fruitCount > 0) {
            continue;
        }

        $sql = "INSERT INTO fruits (id, name, family, genus, order_name, nutritions) 
                VALUES (" . $fruit['id'] . ", '" . mysqli_real_escape_string($connection, $fruit['name']) . "', '" . mysqli_real_escape_string($connection, $fruit['family']) . "', '" . mysqli_real_escape_string($connection, $fruit['genus']) . "', '" . mysqli_real_escape_string($connection, $fruit['order']) . "', '" . mysqli_real_escape_string($connection, json_encode($fruit['nutritions'])) . "')";

        if (mysqli_query($connection, $sql)) {
            echo "";
        } else {
            $error_message = mysqli_error($connection);
            echo "Error: " . $error_message;
        }
    }

    mysqli_close($connection);
}

// Extract Data from Database
function extractDataFromDatabase() {
    $hostname = 'localhost';
    $database = 'fruityvice';
    $username = 'root';
    $password = '';
    $connection = mysqli_connect($hostname, $username, $password, $database);

    if (mysqli_connect_errno()) {
        $error_message = mysqli_connect_error();
        die("Failed to connect to MySQL: " . $error_message);
    }

    $query = "SELECT * FROM fruits";
    $result = mysqli_query($connection, $query);
    $fruits = array();

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $fruit = array(
                'name' => $row['name'],
                'id' => $row['id'],
                'family' => $row['family'],
                'genus' => $row['genus'],
                'order' => $row['order_name'],
                'nutritions' => json_decode($row['nutritions'], true)
            );
            $fruits[] = $fruit;
        }
    }

    mysqli_close($connection);

    return $fruits;
}

// Compose and Send Email
function sendEmail($fruits) {
    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'maazchoudhry07@gmail.com';
    $mail->Password = 'wwgnrdmyqaskydmz';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;
    $mail->setFrom('maazchoudhry07@gmail.com', 'Maaz');
    $mail->addAddress('swearengen1204@gmail.com', 'Al');
    $mail->Subject = 'Fruit Data from Fruityvice API';

    $message = '';
    $message .= "<table>";
    $message .= "<tr><th>Serial Number</th><th>Name</th><th>ID</th><th>Family</th><th>Genus</th><th>Order</th><th>Nutritions</th></tr>";

    foreach ($fruits as $key => $fruit) {
        $message .= "<tr>";
        $message .= "<td>" . ($key + 1) . "</td>";
        $message .= "<td>" . $fruit['name'] . "</td>";
        $message .= "<td>" . $fruit['id'] . "</td>";
        $message .= "<td>" . $fruit['family'] . "</td>";
        $message .= "<td>" . $fruit['genus'] . "</td>";
        $message .= "<td>" . $fruit['order'] . "</td>";
        if (isset($fruit['nutritions'])) {
            $nutritions = $fruit['nutritions'];
            $message .= "<td>";
            $message .= "Calories: " . $nutritions['calories'] . "<br>";
            $message .= "Fat: " . $nutritions['fat'] . "<br>";
            $message .= "Sugar: " . $nutritions['sugar'] . "<br>";
            $message .= "Carbohydrates: " . $nutritions['carbohydrates'] . "<br>";
            $message .= "Protein: " . $nutritions['protein'] . "<br>";
            $message .= "</td>";
        } else {
            $message .= "<td></td>";
        }
        $message .= "</tr>";
    }

    $message .= "</table>";

    $mail->Body = $message;
    $mail->isHTML(true);

    if ($mail->send()) {
        return true;
    } else {
        echo 'Error sending email: ' . $mail->ErrorInfo;
        return false;
    }
}

// Driver Function
function sendEmailWithFruitData() {
    $data = getDataFromAPI();
    saveDataToDatabase($data);
    $fruits = extractDataFromDatabase();
    sendEmail($fruits);
}

// Calling the main function to send email with fruit data
sendEmailWithFruitData();
?>