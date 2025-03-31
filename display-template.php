<?php
// Handle Step 3 (display final template)
$template = $_GET['template'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GeekyLibs - Your Generated Template</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Fira+Code:wght@600&display=swap');
    @import url('https://fonts.googleapis.com/css?family=Lobster&effect=shadow-multiple');
    </style>
</head>
<body>

<?php
    require("menu.php");
?>
<div class="w3-container w3-green w3-center">
    <h1>GeekyLibs</h1>
    <h2>Your Generated Template</h2>
</div>

<div class="w3-container w3-padding-16 w3-card-4 w3-light-grey">
    <h3>Here is your final template:</h3>
    <p><?= nl2br($template) ?></p>
</div>

</body>
</html>

