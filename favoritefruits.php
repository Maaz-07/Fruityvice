<?php
session_start();
$favoriteFruits = isset($_SESSION['favorite_fruits']) ? $_SESSION['favorite_fruits'] : [];
$fruitsPerPage = 6;
$currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
$totalFruits = count($favoriteFruits);
$totalPages = ceil($totalFruits / $fruitsPerPage);

$offset = ($currentPage - 1) * $fruitsPerPage;
$fruits = array_slice($favoriteFruits, $offset, $fruitsPerPage);

// Search for Fruit by Id in Database
function getFruitById($fruitId)
{
    $hostname = 'localhost';
    $database = 'fruityvice';
    $username = 'root';
    $password = '';
    $conn = mysqli_connect($hostname, $username, $password, $database);
    if (!$conn) {
        die('Failed to connect to the database: ' . mysqli_connect_error());
    }
    $query = "SELECT * FROM fruits WHERE id = '$fruitId'";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die('Failed to fetch fruit from the database: ' . mysqli_error($conn));
    }

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
        mysqli_close($conn);
        return $fruit;
    }
    mysqli_close($conn);
    return null;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Favorite Fruits</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        .pagination {
            display: flex;
            justify-content: flex-start;
            align-items: center;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .pagination a {
            display: inline-block;
            padding: 5px 15px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-right: 5px;
        }

        .pagination a.active {
            background-color: #4CAF50;
            color: white;
            border: 1px solid #4CAF50;
        }

        .pagination a:hover:not(.active) {
            background-color: lightgreen;
        }

        .bottom-left {
            position: fixed;
            bottom: 20px;
            left: 20px;
        }

        .favorite-fruits-link {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .bottom-right {
            position: fixed;
            bottom: 20px;
            right: 20px;
        }
    </style>
</head>
<body>
    <h1>Favorite Fruits</h1>
    <?php if (!empty($fruits)) : ?>
        <table>
            <tr>
                <th>Serial Number</th>
                <th>Name</th>
                <th>ID</th>
                <th>Family</th>
                <th>Genus</th>
                <th>Order</th>
                <th>Nutritions</th>
            </tr>
            <?php foreach ($fruits as $key => $fruitId) : ?>
                <?php $fruit = getFruitById($fruitId); ?>
                <tr>
                    <td><?php echo $key + 1; ?></td>
                    <td><?php echo $fruit['name']; ?></td>
                    <td><?php echo $fruit['id']; ?></td>
                    <td><?php echo $fruit['family']; ?></td>
                    <td><?php echo $fruit['genus']; ?></td>
                    <td><?php echo $fruit['order']; ?></td>
                    <td>
                        <?php if (isset($fruit['nutritions'])) : ?>
                            Calories: <?php echo $fruit['nutritions']['calories']; ?><br>
                            Fat: <?php echo $fruit['nutritions']['fat']; ?><br>
                            Sugar: <?php echo $fruit['nutritions']['sugar']; ?><br>
                            Carbohydrates: <?php echo $fruit['nutritions']['carbohydrates']; ?><br>
                            Protein: <?php echo $fruit['nutritions']['protein']; ?><br>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <div class="pagination bottom-left">
            <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                <a href="?page=<?php echo $i; ?>" <?php if ($i == $currentPage) echo 'class="active"'; ?>><?php echo $i; ?></a>
            <?php endfor; ?>
        </div>
    <?php else : ?>
        <p>No favorite fruits found.</p>
    <?php endif; ?>
    <a href="index.php" class="favorite-fruits-link bottom-right">Back to Fruit Data</a>
</body>
</html>