<?php
// Include the database connection
include('sessionsdb.php');

// Fetch track data from the database
$tracks_sql = "SELECT * FROM tracks";
$tracks_result = $conn->query($tracks_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track List</title>
</head>
<body>

<h1>Track List</h1>

<table border="1">
    <thead>
        <tr>
            <th>Track ID</th>
            <th>Track Title</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($tracks_result->num_rows > 0) {
            while ($row = $tracks_result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["track_id"] . "</td>";
                echo "<td>" . $row["title"] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='2'>No tracks found</td></tr>";
        }
        ?>
    </tbody>
</table>

</body>
</html>
