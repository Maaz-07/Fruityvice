<?php include 'email.php'; ?>

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
    </style>
</head>
<body>

<?php sendEmailWithFruitData(); ?>
<?php $fruits = extractDataFromDatabase(); ?>

// Display Fruits
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
                        <?php $nutritions = $fruit['nutritions']; ?>
                        Calories: <?php echo $nutritions['calories']; ?><br>
                        Fat: <?php echo $nutritions['fat']; ?><br>
                        Sugar: <?php echo $nutritions['sugar']; ?><br>
                        Carbohydrates: <?php echo $nutritions['carbohydrates']; ?><br>
                        Protein: <?php echo $nutritions['protein']; ?><br>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>No fruit data found.</p>
<?php endif; ?>

</body>
</html>