<?php
include 'db.php';

// Fetch all movies for dropdown
$movies_result = $connect->query("SELECT * FROM movies ORDER BY title ASC");

// Fetch all schedules
$schedules_result = $connect->query("SELECT * FROM schedules ORDER BY show_date, show_time ASC");

// Build an array of schedules grouped by movie_id
$schedules_by_movie = [];
while ($row = $schedules_result->fetch_assoc()) {
    $schedules_by_movie[$row['movie_id']][] = $row;
}

// Build an array of movie titles for reference
$movies_titles = [];
$movies_result->data_seek(0);
while($m = $movies_result->fetch_assoc()){
    $movies_titles[$m['movie_id']] = $m['title'];
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $movie_id = intval($_POST['movie_id']);
    $schedule_id = intval($_POST['schedule_id']);
    $seat_no = trim($_POST['seat_no']);
    $customer_name = trim($_POST['customer_name']);

    if (empty($movie_id) || empty($schedule_id) || empty($seat_no) || empty($customer_name)) {
        $error = "All fields are required!";
    } else {
        $stmt = $connect->prepare("INSERT INTO reservations (schedule_id, seat_no, customer_name) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $schedule_id, $seat_no, $customer_name);

        if ($stmt->execute()) {
            $success = "Reservation added successfully!";
        } else {
            $error = "Failed to add reservation: " . $stmt->error;
        }

        $stmt->close();
    }
}

$connect->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Reservation - Cinema Management System</title>
    <style>
        body {margin:0; padding:0; background:#111; font-family:Arial,sans-serif; color:white;}
        header {background:#e50914; padding:20px; text-align:center; font-size:32px; font-weight:bold;}
        .container {width:90%; max-width:600px; margin:40px auto; background:#1c1c1c; padding:20px; border-radius:10px;}
        h2 {text-align:center; margin-bottom:20px; color:#e50914;}
        form input, form select, form button {width:100%; padding:10px; margin-bottom:15px; border-radius:5px; border:none; font-size:16px;}
        input, select {background:#333; color:white;}
        button {background:#e50914; color:white; cursor:pointer; transition:0.3s;}
        button:hover {background:#ff1a1a;}
        .message {text-align:center; margin-bottom:15px;}
        a.home-btn {display:block; margin-top:10px; text-align:center; color:#1a73e8; text-decoration:none;}
        a.home-btn:hover {text-decoration:underline;}
    </style>

    <script>
        const schedules = <?= json_encode($schedules_by_movie) ?>;
        const movieTitles = <?= json_encode($movies_titles) ?>;

        function updateScheduleOptions() {
            const movieId = document.getElementById('movie_id').value;
            const scheduleSelect = document.getElementById('schedule_select');

            scheduleSelect.innerHTML = '<option value="">-- Select Schedule --</option>';

            if (schedules[movieId]) {
                schedules[movieId].forEach(s => {
                    const option = document.createElement('option');
                    option.value = s.schedule_id;

                    // Format: Movie Title - Day, DD/MM/YYYY at HH:MM
                    const dateObj = new Date(s.show_date + ' ' + s.show_time);
                    const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                    const dayName = days[dateObj.getDay()];
                    const day = String(dateObj.getDate()).padStart(2, '0');
                    const month = String(dateObj.getMonth() + 1).padStart(2, '0');
                    const year = dateObj.getFullYear();
                    const hours = String(dateObj.getHours()).padStart(2, '0');
                    const minutes = String(dateObj.getMinutes()).padStart(2, '0');

                    const title = movieTitles[movieId] || 'Unknown';
                    option.text = `${title} - ${dayName}, ${day}/${month}/${year} at ${hours}:${minutes}`;

                    scheduleSelect.add(option);
                });
            }
        }
    </script>
</head>
<body>

<header>🎬 CINEMA MANAGEMENT SYSTEM</header>

<div class="container">
    <h2>Add Reservation</h2>

    <?php if (!empty($error)) echo "<div class='message' style='color:red;'>$error</div>"; ?>
    <?php if (!empty($success)) echo "<div class='message' style='color:green;'>$success</div>"; ?>

    <form method="POST" action="">
        <label for="movie_id">Select Movie:</label>
        <select name="movie_id" id="movie_id" onchange="updateScheduleOptions()" required>
            <option value="">-- Select Movie --</option>
            <?php
            foreach ($movies_titles as $id => $title) {
                echo "<option value=\"$id\">" . htmlspecialchars($title) . "</option>";
            }
            ?>
        </select>

        <label for="schedule_id">Select Schedule:</label>
        <select name="schedule_id" id="schedule_select" required>
            <option value="">-- Select Schedule --</option>
        </select>

        <label for="seat_no">Seat Number:</label>
        <input type="text" name="seat_no" placeholder="e.g. A5" required>

        <label for="customer_name">Customer Name:</label>
        <input type="text" name="customer_name" placeholder="Customer Name" required>

        <button type="submit">Add Reservation</button>
    </form>

    <a class="home-btn" href="index.php">🏠 Back to Home</a>
</div>

</body>
</html>
