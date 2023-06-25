<?php
$url = 'https://fruityvice.com/api/fruit/all';

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    $error_message = curl_error($ch);
    echo "Error: " . $error_message;
}
curl_close($ch);

$data = json_decode($response, true);
if ($data) {
    $hostname = 'localhost';
    $database = 'fruityvice';
    $username = 'root';
    $password = '';
    $connection = mysqli_connect($hostname, $username, $password, $database);

    if (mysqli_connect_errno()) {
        die("Failed to connect to MySQL: " . mysqli_connect_error());
    }

    foreach ($data as $fruit) {
        echo "Name: " . $fruit['name'] . "<br>";
        echo "ID: " . $fruit['id'] . "<br>";
        echo "Family: " . $fruit['family'] . "<br>";
        echo "Genus: " . $fruit['genus'] . "<br>";
        echo "Order: " . $fruit['order'] . "<br>";
        if (isset($fruit['nutritions'])) {
            $nutritions = $fruit['nutritions'];
            echo "Nutritions:<br>";
            echo "Calories: " . $nutritions['calories'] . "<br>";
            echo "Fat: " . $nutritions['fat'] . "<br>";
            echo "Sugar: " . $nutritions['sugar'] . "<br>";
            echo "Carbohydrates: " . $nutritions['carbohydrates'] . "<br>";
            echo "Protein: " . $nutritions['protein'] . "<br>";
        }
        echo "<br>";

        $sql = "INSERT INTO fruits (id, name, family, genus, order_name, nutritions) 
                VALUES (" . $fruit['id'] . ", '" . mysqli_real_escape_string($connection, $fruit['name']) . "', '" . mysqli_real_escape_string($connection, $fruit['family']) . "', '" . mysqli_real_escape_string($connection, $fruit['genus']) . "', '" . mysqli_real_escape_string($connection, $fruit['order']) . "', '" . mysqli_real_escape_string($connection, json_encode($fruit['nutritions'])) . "')";

        if (mysqli_query($connection, $sql)) {
            echo "Fruit saved to the database successfully.<br>";
        } else {
            echo "Error: " . mysqli_error($connection);
        }
    }
    mysqli_close($connection);
} else {
    echo "No fruit data found.";
}
?>