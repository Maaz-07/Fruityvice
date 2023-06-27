<?php
session_start();
include 'email.php';

if (!isset($_SESSION['email_sent'])) {
    sendEmailWithFruitData();
    $_SESSION['email_sent'] = true;
}

$fruitsPerPage = 6;
$filterName = isset($_GET['filter_name']) ? $_GET['filter_name'] : '';
$filterFamily = isset($_GET['filter_family']) ? $_GET['filter_family'] : '';

if (!isset($_SESSION['fruits'])) {
    $fruits = extractDataFromDatabase();
    $_SESSION['fruits'] = $fruits;
} else {
    $fruits = $_SESSION['fruits'];
}

$filteredFruits = filterFruits($filterName, $filterFamily);
$totalFruits = count($filteredFruits);
$totalPages = ceil($totalFruits / $fruitsPerPage);
$currentPage = isset($_GET['page']) ? $_GET['page'] : 1;

$offset = ($currentPage - 1) * $fruitsPerPage;
$fruits = array_slice($filteredFruits, $offset, $fruitsPerPage);

$favoriteFruits = isset($_SESSION['favorite_fruits']) ? $_SESSION['favorite_fruits'] : [];

if (isset($_GET['action']) && $_GET['action'] === 'toggle_favorite' && isset($_GET['fruit_id'])) {
    $fruitId = $_GET['fruit_id'];

    if (in_array($fruitId, $favoriteFruits)) {
        $index = array_search($fruitId, $favoriteFruits);
        unset($favoriteFruits[$index]);
    } else {
        if (count($favoriteFruits) < 10) {
            $favoriteFruits[] = $fruitId;
        } else {
            echo "<script>alert('You can only add up to 10 favorite fruits.');</script>";
        }
    }
    $_SESSION['favorite_fruits'] = $favoriteFruits;
}

// Filter Fruits
function filterFruits($name, $family)
{
    global $fruits;
    $filteredFruits = [];
    foreach ($fruits as $fruit) {
        $fruitName = strtolower($fruit['name']);
        $fruitFamily = strtolower($fruit['family']);
        $nameMatch = empty($name) || stripos($fruitName, strtolower($name)) !== false;
        $familyMatch = empty($family) || stripos($fruitFamily, strtolower($family)) !== false;
        if ($nameMatch && $familyMatch) {
            $filteredFruits[] = $fruit;
        }
    }
    return $filteredFruits;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Fruit Data</title>
    <style>
        form {
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }

        label {
            margin-right: 10px;
            font-weight: bold;
            ;
        }

        input[type="text"] {
            padding: 10px;
            width: 550px;
            margin-right: 10px;
            border-radius: 5px;
        }

        input[type="submit"] {
            padding: 10px 20px;
            background-color: #4CAF50;
            margin-left: 15px;
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
        }

        .filter-label {
            margin-right: 30px;
        }

        .filter-label2 {
            margin-left: 50px;
        }

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
    <form method="get">
        <label class="filter-label" for="filter_name">Filter by Name:</label>
        <input type="text" id="filter_name" name="filter_name" value="<?php echo $filterName; ?>">
        <label class="filter-label2" for="filter_family">Filter by Family:</label>
        <input type="text" id="filter_family" name="filter_family" value="<?php echo $filterFamily; ?>">
        <input type="submit" value="Filter">
    </form>

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
                <th>Favorite</th>
            </tr>
            <?php foreach ($fruits as $key => $fruit) : ?>
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
                    <td>
                        <a href="?page=<?php echo $currentPage; ?>&action=toggle_favorite&fruit_id=<?php echo $fruit['id']; ?>">
                            <?php if (in_array($fruit['id'], $favoriteFruits)) : ?>
                                &#9733;
                            <?php else : ?>
                                &#9734;
                            <?php endif; ?>
                        </a>
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
        <p>No fruit data found.</p>
    <?php endif; ?>
    <a href="favoritefruits.php" class="favorite-fruits-link bottom-right">Favorite Fruits</a>
</body>

</html>