<?php
include 'db.php';

// Fetch all schedules with movie title
$query = "SELECT s.schedule_id, m.title AS movie_title, s.show_date, s.show_time
          FROM schedules s
          JOIN movies m ON s.movie_id = m.movie_id
          ORDER BY s.show_date ASC, s.show_time ASC";

$result = $connect->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Schedules List - Cinema Management System</title>
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
            flex-wrap: wrap;
        }

        .top-actions div {
            display: flex;
            gap: 10px;
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
            flex-grow: 1;
            text-align: center;
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

        .btn-edit { background: #ffc107; }
        .btn-delete { background: #dc3545; }

        .btn-edit:hover { background: #ffca2c; }
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
        <div>
            <a href="index.php" class="btn">🏠 Home</a>
            <a href="add_schedule.php" class="btn">+ Add New Schedule</a>
        </div>
        <h2>Schedules List</h2>
    </div>

    <table>
        <tr>
            <th>ID</th>
            <th>Movie Title</th>
            <th>Show Date</th>
            <th>Show Time</th>
            <th>Actions</th>
        </tr>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['schedule_id'] ?></td>
                    <td><?= htmlspecialchars($row['movie_title']) ?></td>
                    <td><?= htmlspecialchars($row['show_date']) ?></td>
                    <td><?= htmlspecialchars($row['show_time']) ?></td>
                    <td>
                        <a class="action-btn btn-edit" href="update_schedule.php?id=<?= $row['schedule_id'] ?>">Edit</a>
                        <a class="action-btn btn-delete" href="delete_schedule.php?id=<?= $row['schedule_id'] ?>" onclick="return confirm('Are you sure you want to delete this schedule?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="5">No schedules found.</td>
            </tr>
        <?php endif; ?>
    </table>
</div>

<footer>© <?= date("Y") ?> Cinema Management System</footer>

</body>
</html>
