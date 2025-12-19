<?php
include 'db.php';

// Fetch all reservations with movie title and schedule info
$query = "
    SELECT r.reservation_id, m.title AS movie_title, s.show_date, s.show_time, r.seat_no, r.customer_name
    FROM reservations r
    JOIN schedules s ON r.schedule_id = s.schedule_id
    JOIN movies m ON s.movie_id = m.movie_id
    ORDER BY s.show_date ASC, s.show_time ASC, r.seat_no ASC
";

$result = $connect->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reservations List - Cinema Management System</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: #111;
            font-family: Arial, sans-serif;
            color: white;
        }

        header {
            background: #e50914;
            padding: 20px;
            text-align: center;
            font-size: 32px;
            font-weight: bold;
            letter-spacing: 2px;
        }

        .container {
            width: 95%;
            max-width: 1000px;
            margin: 40px auto;
        }

        .top-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #1a73e8;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 16px;
            transition: 0.3s;
        }

        .btn:hover {
            background: #4285f4;
        }

        h2 {
            font-size: 28px;
            margin-bottom: 10px;
            text-transform: uppercase;
            border-bottom: 2px solid #e50914;
            padding-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #1c1c1c;
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            padding: 12px 15px;
            text-align: center;
            border-bottom: 1px solid #333;
        }

        th {
            background: #e50914;
        }

        tr:hover {
            background: #333;
        }

        a.action-btn {
            text-decoration: none;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            margin: 2px;
            display: inline-block;
            transition: 0.3s;
        }

        .btn-delete { background: #dc3545; }
        .btn-delete:hover { background: #ff4d4d; }

        footer {
            margin-top: 50px;
            text-align: center;
            color: #777;
            font-size: 14px;
        }
    </style>
</head>
<body>

<header>🎬 CINEMA MANAGEMENT SYSTEM</header>

<div class="container">
    <div class="top-actions">
        <h2>Reservations List</h2>
        <a href="index.php" class="btn">🏠 Home</a>
    </div>

    <table>
        <tr>
            <th>ID</th>
            <th>Movie Title</th>
            <th>Show Date</th>
            <th>Show Time</th>
            <th>Seat No</th>
            <th>Customer Name</th>
            <th>Actions</th>
        </tr>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['reservation_id'] ?></td>
                    <td><?= htmlspecialchars($row['movie_title']) ?></td>
                    <td><?= htmlspecialchars($row['show_date']) ?></td>
                    <td><?= htmlspecialchars($row['show_time']) ?></td>
                    <td><?= htmlspecialchars($row['seat_no']) ?></td>
                    <td><?= htmlspecialchars($row['customer_name']) ?></td>
                    <td>
                        <a class="action-btn btn-delete" href="delete_reservation.php?id=<?= $row['reservation_id'] ?>" onclick="return confirm('Are you sure you want to delete this reservation?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="7">No reservations found.</td>
            </tr>
        <?php endif; ?>
    </table>
</div>

<footer>© <?= date("Y") ?> Cinema Management System</footer>

</body>
</html>
