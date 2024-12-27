<?php
include('trackdb.php');

// Add Track functionality
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_track'])) {
    $track_name = $_POST['track_name'];
    $track_description = $_POST['track_description'];
    $status = $_POST['status'];

    // Insert track into the database
    $stmt = $conn->prepare("INSERT INTO tracks (track_name, track_description, status) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $track_name, $track_description, $status);
    $stmt->execute();
    $stmt->close();
}

// Update Track functionality
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_track'])) {
    $track_id = $_POST['track_id'];
    $track_name = $_POST['track_name'];
    $track_description = $_POST['track_description'];
    $status = $_POST['status'];

    // Update track details
    $stmt = $conn->prepare("UPDATE tracks SET track_name = ?, track_description = ?, status = ? WHERE track_id = ?");
    $stmt->bind_param("sssi", $track_name, $track_description, $status, $track_id);
    $stmt->execute();
    $stmt->close();

    // Redirect to the main page (Add Track page)
    header("Location: track.php");
    exit();
}

// Delete Track functionality
if (isset($_GET['delete_track_id'])) {
    $track_id = $_GET['delete_track_id'];
    $stmt = $conn->prepare("DELETE FROM tracks WHERE track_id = ?");
    $stmt->bind_param("i", $track_id);
    $stmt->execute();
    $stmt->close();
}

// Get all tracks (but will only show the list if not editing)
if (!isset($_GET['edit_track_id'])) {
    $result = $conn->query("SELECT * FROM tracks");
}

// If an edit is requested, fetch track data
if (isset($_GET['edit_track_id'])) {
    $track_id = $_GET['edit_track_id'];
    $stmt = $conn->prepare("SELECT * FROM tracks WHERE track_id = ?");
    $stmt->bind_param("i", $track_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $track = $result->fetch_assoc();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Manage Tracks</title>
    <style>
        /* Styling for the form and table */
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        .form-container {
            width: 60%;
            margin: 20px auto;
            background: #f4f4f4;
            padding: 20px;
            border-radius: 5px;
        }
        .form-container input, .form-container textarea, .form-container select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-container button {
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .form-container button:hover {
            background-color: #45a049;
        }
		
		/* For screen width up to 1280px */
	@media (max-width: 1280px) {
		table {
			width: 90%;
		}
		.form-container {
			width: 70%;
		}
	}

	@media (max-width: 1024px) {
		table {
			width: 95%;
		}
		.form-container {
			width: 80%;
		}
	}

	@media (max-width: 768px) {
		table {
			width: 100%;
		}
		.form-container {
			width: 90%;
		}
		.form-container button {
			width: 100%;
		}
	}
    </style>
</head>
<body>

    <div class="form-container">
        <h2><?= isset($track) ? "Edit Track" : "Create Track" ?></h2>
        <form method="POST">
            <?php if (isset($track)): ?>
                <!-- When editing, the track_id is hidden and not displayed to the user -->
                <input type="hidden" name="track_id" value="<?= $track['track_id'] ?>">
            <?php else: ?>
                <!-- When adding, allow the user to input the track_id -->
                <input type="text" name="track_id" placeholder="Track ID" value="<?= isset($track) ? $track['track_id'] : '' ?>" required>
            <?php endif; ?>

            <input type="text" name="track_name" placeholder="Track Name" value="<?= isset($track) ? $track['track_name'] : '' ?>" required>
            <textarea name="track_description" placeholder="Track Description" required><?= isset($track) ? $track['track_description'] : '' ?></textarea>
            <select name="status">
                <option value="Active" <?= (isset($track) && $track['status'] == 'Active') ? 'selected' : '' ?>>Active</option>
                <option value="Inactive" <?= (isset($track) && $track['status'] == 'Inactive') ? 'selected' : '' ?>>Inactive</option>
            </select>
            <button type="submit" name="<?= isset($track) ? 'update_track' : 'add_track' ?>">
                <?= isset($track) ? 'Update Track' :'Create Track' ?>
            </button>
        </form>
    </div>

    <?php if (!isset($track)): ?>
        <!-- Only display Track List if not editing a track -->
        <h2 style="text-align: center;">Track List</h2>

        <table>
            <thead>
                <tr>
                    <th>Track ID</th>
                    <th>Track Name</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['track_id'] ?></td>
                        <td><?= $row['track_name'] ?></td>
                        <td><?= $row['track_description'] ?></td>
                        <td><?= $row['status'] ?></td>
                        <td>
                            <a href="track.php?delete_track_id=<?= $row['track_id'] ?>" onclick="return confirm('Are you sure you want to delete this track?')">Delete</a> | 
                            <a href="track.php?edit_track_id=<?= $row['track_id'] ?>">Edit</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>

</body>
</html>

<?php
$conn->close();
?>
