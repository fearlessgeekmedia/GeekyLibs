<?php
// Initialize variables
$upload_message = "";

// Directory paths
$submitted_dir = "submitted-templates/"; // Store user-submitted templates here
$approved_dir = "templates/"; // Approved templates go here

// Ensure the submitted-templates directory exists
if (!is_dir($submitted_dir)) {
    mkdir($submitted_dir, 0755, true); // Create the directory if it doesn't exist
}

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['template_file'])) {
    $original_file_name = basename($_FILES["template_file"]["name"]);
    $target_file = $submitted_dir . $original_file_name;
    $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
    // Check if the file is a .txt file
    if ($file_type !== "txt") {
        $upload_message = "Sorry, only .txt files are allowed.";
    } else {
        // Check if file already exists, and increment the filename if necessary
        $counter = 1;
        while (file_exists($target_file)) {
            $target_file = $submitted_dir . pathinfo($original_file_name, PATHINFO_FILENAME) . '-' . $counter . '.txt';
            $counter++;
        }
        
        // Try to move the uploaded file to the submitted templates directory
        if (move_uploaded_file($_FILES["template_file"]["tmp_name"], $target_file)) {
            $upload_message = "The file " . htmlspecialchars(basename($_FILES["template_file"]["name"])) . " has been uploaded successfully! It is now awaiting approval.";
        } else {
            $upload_message = "Sorry, there was an error uploading your file.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Submit a Template | GeekyLibs</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="./styles.css">
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Fira+Code:wght@600&display=swap');
    @import url('https://fonts.googleapis.com/css?family=Lobster&effect=shadow-multiple');
    </style>
</head>
<body>

<?php require("menu.php"); ?> <!-- Include your menu -->

<div class="w3-container w3-green w3-center">
    <h1 class="fira-code-header">Submit Your Template</h1>
    <h2 class="fira-code-header">Upload Your Template File (Text Format)</h2>
</div>

<div class="w3-container w3-padding-16">
    <!-- File upload form -->
    <form action="submit-template.php" method="post" enctype="multipart/form-data" class="w3-container w3-card-4 w3-light-grey w3-padding-16">
        <label class="w3-text-green" for="template_file">Choose a Template File (Text):</label>
        <input type="file" name="template_file" id="template_file" class="w3-input w3-border w3-margin-bottom" accept=".txt" required>
        <button type="submit" class="w3-button w3-green w3-margin-top">Upload Template</button>
    </form>

    <?php if (!empty($upload_message)): ?>
        <div class="w3-container w3-margin-top">
            <p><?= $upload_message ?></p>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
