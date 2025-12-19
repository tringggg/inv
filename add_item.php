<?php include 'db.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Inventory Management System - Add Item</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: #111;
            font-family: Arial, sans-serif;
            color: white;
        }

        header {
            background: #1e90ff;
            padding: 20px;
            text-align: center;
            font-size: 32px;
            font-weight: bold;
            letter-spacing: 2px;
        }

        .container {
            width: 90%;
            max-width: 500px;
            margin: 40px auto;
            text-align: center;
            background: #1c1c1c;
            padding: 30px;
            border-radius: 15px;
            border: 2px solid #333;
        }

        h2 {
            margin-bottom: 20px;
            text-transform: uppercase;
            border-bottom: 2px solid #1e90ff;
            padding-bottom: 10px;
        }

        input[type="text"], input[type="number"] {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 8px;
            border: 1px solid #333;
            background: #222;
            color: white;
            font-size: 16px;
        }

        button {
            padding: 12px 25px;
            margin-top: 15px;
            border: none;
            border-radius: 10px;
            background: #1e90ff;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #3399ff;
        }

        footer {
            margin-top: 50px;
            text-align: center;
            color: #777;
            font-size: 14px;
        }
    </style>
</head>
<body>

<header>📦 INVENTORY MANAGEMENT SYSTEM</header>

<div class="container">
    <h2>Add Item</h2>

    <form method="post" action="added_item.php">
        <input type="text" name="item_name" placeholder="Item Name" required>
        <input type="text" name="category" placeholder="Category" required>
        <input type="number" name="quantity" placeholder="Quantity" required>
        <input type="number" step="0.01" name="price" placeholder="Price per Unit" required>
        <button type="submit">Save Item</button>
    </form>
</div>

<footer>© <?= date("Y") ?> Inventory Management System</footer>

</body>
</html>
