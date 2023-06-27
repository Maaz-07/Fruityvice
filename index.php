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
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
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
            display: inline-block;
        }

        .pagination a {
            color: black;
            float: left;
            padding: 8px 16px;
            text-decoration: none;
            transition: background-color .3s;
            border: 1px solid #ddd;
            margin: 0 4px;
        }

        .pagination a.active {
            background-color: #4CAF50;
            color: white;
            border: 1px solid #4CAF50;
        }

        .pagination a:hover:not(.active) {
            background-color: #ddd;
        }
    </style>
</head>
<body>
<form method="get">
    <label for="filter_name">Filter by Name:</label>
    <input type="text" id="filter_name" name="filter_name" value="<?php echo $filterName; ?>">
    <label for="filter_family">Filter by Family:</label>
    <input type="text" id="filter_family" name="filter_family" value="<?php echo $filterFamily; ?>">
    <input type="submit" value="Filter">
</form>

<?php if (!empty($fruits)): ?>
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
        <?php foreach ($fruits as $key => $fruit): ?>
            <tr>
                <td><?php echo $key + 1; ?></td>
                <td><?php echo $fruit['name']; ?></td>
                <td><?php echo $fruit['id']; ?></td>
                <td><?php echo $fruit['family']; ?></td>
                <td><?php echo $fruit['genus']; ?></td>
                <td><?php echo $fruit['order']; ?></td>
                <td>
                    <?php if (isset($fruit['nutritions'])): ?>
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

    <div class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?php echo $i; ?>&filter_name=<?php echo $filterName; ?>&filter_family=<?php echo $filterFamily; ?>" <?php if ($i == $currentPage) echo 'class="active"'; ?>><?php echo $i; ?></a>
        <?php endfor; ?>
    </div>
<?php else: ?>
    <p>No fruit data found.</p>
<?php endif; ?>

</body>
</html>