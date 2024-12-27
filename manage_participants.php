<?php
include('db.php');

// Add participant functionality
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_participant'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $event = $_POST['event'];
    $irc_id = $_POST['irc_id']; // IRC_ID as the unique identifier

    // Insert into database (using 'IRC_ID' as the identifier)
    $stmt = $conn->prepare("INSERT INTO participants (IRC_ID, name, email, event) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $irc_id, $name, $email, $event);
    $stmt->execute();
    $stmt->close();
}

// Delete participant functionality
if (isset($_GET['delete_irc_id'])) {
    $irc_id = $_GET['delete_irc_id'];
    // Using IRC_ID as the identifier for deletion
    $stmt = $conn->prepare("DELETE FROM participants WHERE IRC_ID = ?");
    $stmt->bind_param("s", $irc_id);
    $stmt->execute();
    $stmt->close();
}

// Get all participants
$result = $conn->query("SELECT * FROM participants");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Manage Participants</title>
    <style>
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
        .form-container input {
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
        <h2>Add Participant</h2>
        <form method="POST">
           
            <input type="text" name="irc_id" placeholder="IRC_ID" required>
            <input type="text" name="name" placeholder="Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="event" placeholder="Event" required>
            <button type="submit" name="add_participant">Add Participant</button>
        </form>
    </div>

    <h2 style="text-align: center;">Participants List</h2>

    <table>
        <thead>
            <tr>
                <th>IRC_ID</th> 
                <th>Name</th>
                <th>Email</th>
                <th>Event</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['IRC_ID'] ?></td> 
                    <td><?= $row['name'] ?></td>
                    <td><?= $row['email'] ?></td>
                    <td><?= $row['event'] ?></td>
                    <td><?= $row['status'] ?></td>
                    <td>
                        <!-- Use IRC_ID to delete a participant -->
                        <a href="admin.php?delete_irc_id=<?= $row['IRC_ID'] ?>" onclick="return confirm('Are you sure you want to delete this participant?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

</body>
</html>

<?php
$conn->close();
?>
