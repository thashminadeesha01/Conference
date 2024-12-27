<?php
session_start();

// Admin credentials
$admin_email = 'admin@gmail.com';
$admin_pass = 'admin123';

// Handle admin login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Admin login validation
    if ($email === $admin_email && $password === $admin_pass) {
        $_SESSION['admin_loggedin'] = true;
        header('Location: dashboard.html'); // Redirect to admin dashboard
        exit;
    } else {
        $loginError = "Invalid email or password. Only the admin can log in.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>International Research Conference Login</title>
    <style>
        * {
			margin: 0;
			padding: 0;
			box-sizing: border-box;
		}

		body {
			font-family: Arial, sans-serif;
			background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)),
			url('https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&q=80');
			background-size: cover;
			background-position: center;
			background-attachment: fixed;
			display: flex;
			justify-content: center;
			align-items: center;
			height: 100vh;
			color: #333;
		}

		.container {
			display: flex;
			justify-content: space-between;
			gap: 40px;
			width: 100%;
			max-width: 1280px;
			padding: 20px;
		}

		.login-section {
			background-color: rgba(233, 247, 255, 0.6); /* Transparent background */
			border-radius: 8px;
			box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
			padding: 20px;
			width: 300px;
			text-align: center;
		}

		.login-section h2 {
			font-size: 24px;
			margin-bottom: 20px;
			color: #0056b3;
		}

		label {
			display: block;
			margin-bottom: 8px;
			font-size: 14px;
			font-weight: bold;
		}

		input {
			width: 100%;
			padding: 10px;
			margin-bottom: 15px;
			border: 1px solid #ccc;
			border-radius: 4px;
			font-size: 16px;
		}

		input[type="email"] {
			font-size: 14px;
		}

		button {
			width: 50%;
			padding: 12px;
			background-color: #0056b3;
			color: white;
			border: none;
			border-radius: 4px;
			font-size: 16px;
			cursor: pointer;
			transition: background-color 0.3s ease;
		}	

		button:hover {
			background-color: #003b73;
		}

		p.error {
			color: red;
			font-size: 14px;
		}



/* For screens larger than 1280px */
	@media (min-width: 1280px) {
		.container {
			justify-content: center;
			gap: 60px; 
		}

			.login-section {
				width: 350px; 
			}

		.login-section h2 {
			font-size: 28px; /* Larger font size for the heading */
		}

		input, button {
			font-size: 18px; /* Larger font size for inputs and button */
		}
	}

/* For screens between 1024px and 1279px */
	@media (max-width: 1279px) and (min-width: 1024px) {
		.container {
			justify-content: center;
			gap: 40px;
		}

		.login-section {
			width: 320px; 
		}

		.login-section h2 {
			font-size: 26px; 
		}

		input, button {
			font-size: 16px; 
		}
	}

/* For screens smaller than 1024px (including 768px) */
	@media (max-width: 1024px) {
		.container {
			flex-direction: column;
			justify-content: center;
			gap: 20px;
			width: 90%; 
		}

		.login-section {
			width: 100%; 
		}

		.login-section h2 {
			font-size: 24px; 
		}

		input, button {
			font-size: 16px;
			padding: 12px;
		}

		button {
			width: 100%; 
		}
	}

/* For very small screens (up to 768px) */
	@media (max-width: 768px) {
		.container {
			flex-direction: column;
			align-items: center;
			gap: 20px;
			width: 100%; 
		}

		.login-section {
			width: 90%; 
			padding: 15px; 
		}

		.login-section h2 {
			font-size: 20px; 
		}

		input, button {
			font-size: 14px; 
			padding: 10px; 
		}

		button {
			width: 100%;
		}
	}

    </style>
</head>
<body>
    <div class="container">
        <!-- Admin Login Section -->
        <div class="login-section admin">
            <h2>Admin Login</h2>
            <form action="" method="post">
                <label for="email">Email:</label>
                <input type="email" id="admin-email" name="email" required>

                <label for="password">Password:</label>
                <input type="password" id="admin-password" name="password" required>

                <?php if (isset($loginError)) { echo "<p class='error'>$loginError</p>"; } ?>

                <button type="submit" name="login">Login</button>
            </form>
        </div>
    </div>
</body>
</html>
