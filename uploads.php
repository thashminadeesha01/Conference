<?php
// Handle file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload'])) {
    // Check if a file is uploaded and there is no error
    if (isset($_FILES['proceeding_file']) && $_FILES['proceeding_file']['error'] === 0) {
        // Get file details
        $fileTmpPath = $_FILES['proceeding_file']['tmp_name'];
        $fileName = $_FILES['proceeding_file']['name'];
        $fileType = $_FILES['proceeding_file']['type'];

        // Allowed file types (only PDF)
        $allowedTypes = ['application/pdf'];

        // Check if the file is a PDF
        if (in_array($fileType, $allowedTypes)) {
            // Specify the upload directory
            $uploadDir = 'uploads/';

            // Ensure the upload directory exists
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Generate a unique file name to avoid overwriting
            $filePath = $uploadDir . basename($fileName);

            // Move the uploaded file to the desired location
            if (move_uploaded_file($fileTmpPath, $filePath)) {
                // Redirect with success message
                header('Location: ' . $_SERVER['PHP_SELF'] . '?success=true');
                exit;
            } else {
                // Redirect with error message
                header('Location: ' . $_SERVER['PHP_SELF'] . '?error=true');
                exit;
            }
        } else {
            // If the file is not a PDF, redirect with error message
            header('Location: ' . $_SERVER['PHP_SELF'] . '?error=true');
            exit;
        }
    } else {
        // Handle case if no file was uploaded or if there was an error
        header('Location: ' . $_SERVER['PHP_SELF'] . '?error=true');
        exit;
    }
}

// Handle file deletion
if (isset($_GET['delete']) && isset($_GET['file'])) {
    $fileToDelete = $_GET['file'];
    $filePath = 'uploads/' . basename($fileToDelete);

    // Check if the file exists and delete it
    if (file_exists($filePath)) {
        unlink($filePath);  // Delete the file
        header('Location: ' . $_SERVER['PHP_SELF'] . '?deleted=true');
        exit;
    } else {
        header('Location: ' . $_SERVER['PHP_SELF'] . '?error=true');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Conference Proceedings</title>
    <style>
        body {
            font-family: Arial, sans-serif;
             background-image: url('https://t4.ftcdn.net/jpg/02/16/47/85/360_F_216478580_H5Vd0IuSqnlPzn6k44qrbWxNqJVZDUWL.jpg'); 
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
			
            margin: 0;
            padding: 0;
            text-align: center;
        }
        .upload-form {
            margin-top: 50px;
            padding: 20px;
            background-color:rgba(255,255,255,0.8);
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.9);
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
        .upload-form h2 {
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
        }
        .upload-form input {
            padding: 10px;
            margin: 10px 0;
            width: 80%;
            max-width: 400px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        .upload-form button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .upload-form button:hover {
            background-color: #F39E60;
        }
        .upload-form p {
            font-size: 16px;
            margin-top: 10px;
        }
        .upload-form p.success {
            color: green;
        }
        .upload-form p.error {
            color: red;
        }
        .file-list {
            margin-top: 40px;
            padding: 20px;
            background-color:rgba(255,255,255,0.5);
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
        .file-list h2 {
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
        }
        .file-list ul {
            list-style-type: none;
            padding: 0;
        }
        .file-list li {
            margin: 10px 0;
            font-size: 18px;
        }
        .file-list a {
            color: black;
            text-decoration: none;
        }
        .file-list a:hover {
            text-decoration: underline;
        }
        .file-list a.delete-link {
            color: red;
            font-weight: bold;
            margin-left: 10px;
        }
        .file-list a.delete-link:hover {
            text-decoration: underline;
        }
	@media (max-width: 1280px) {
		.upload-form {
			width: 80%;
		}
		.file-list {
			width: 80%;
		}
	}

/* For screen widths up to 1024px */
	@media (max-width: 1024px) {
		.upload-form {
			width: 85%;
		}
		.file-list {
			width: 85%;
		}
		.upload-form input {
			width: 90%;
		}
	}

/* For screen widths up to 768px */
	@media (max-width: 768px) {
		.upload-form {
			width: 90%;
		}
		.file-list {
			width: 90%;
		}
		.upload-form h2 {
			font-size: 20px;
		}
		.upload-form input {
			width: 100%;
		}
		.upload-form button {
			width: 100%;
		}
		.file-list h2 {
			font-size: 20px;
		}
		.file-list li {
			font-size: 16px;
		}
	}
		
    </style>
</head>
<body>

    <!-- File Upload Form -->
    <div class="upload-form">
        <h2>Upload Conference Proceedings</h2>

        <!-- Display success or error message -->
        <?php if (isset($_GET['success'])): ?>
            <p class="success">File uploaded successfully!</p>
        <?php elseif (isset($_GET['error'])): ?>
            <p class="error">Error uploading file. Please try again.</p>
        <?php elseif (isset($_GET['deleted'])): ?>
            <p class="success">File deleted successfully!</p>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <input type="file" name="proceeding_file" accept="application/pdf" required><br>
            <button type="submit" name="upload">Upload File</button>
        </form>
    </div>

    <!-- Display Uploaded Files -->
    <div class="file-list">
        <h2>Uploaded Conference Proceedings</h2>
        <ul>
            <?php
            // Get all PDF files in the uploads directory
            $uploadDir = 'uploads/';
            $files = glob($uploadDir . '*.pdf');

            // Display each file as a download link and a delete button
            foreach ($files as $file) {
                $fileName = basename($file);
                echo '<li>';
                echo '<a href="' . $file . '" class="download-link" download>' . $fileName . '</a>';
                echo ' <a href="?delete=true&file=' . urlencode($fileName) . '" class="delete-link">Delete</a>';
                echo '</li>';
            }
            ?>
        </ul>
    </div>

</body>
</html>
