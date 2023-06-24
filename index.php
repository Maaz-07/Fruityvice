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
    }
} else {
    echo "No fruit data found.";
}
?>