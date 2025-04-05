<?php
// Start output buffering
ob_start();

// Handle Step 2 (fill in the blanks)
$template = $_GET['template'] ?? ''; // Get template from query string
$placeholders = [];

// Find placeholders in the template
preg_match_all('/\[\[(.*?)\]\]/', $template, $matches);
$placeholders = array_unique($matches[1]);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect user inputs and replace placeholders
    $user_inputs = [];
    foreach ($placeholders as $placeholder) {
        $sanitized_placeholder = str_replace(' ', '_', $placeholder);
        $user_inputs[$placeholder] = htmlspecialchars($_POST[$sanitized_placeholder] ?? '');
    }

    // Replace placeholders with user inputs
    foreach ($user_inputs as $placeholder => $input) {
        $template = str_replace("[[$placeholder]]", $input, $template);
    }

    // Redirect to the final template display page
    header("Location: display-template.php?template=" . urlencode($template));
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GeekyLibs - Fill In the Blanks</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet"  href="./styles.css">
</head>
<body>
<?php
    require("menu.php");
?>
        
<div class="w3-container w3-green w3-center">
    <h1>GeekyLibs</h1>
    <h2>Fill in the Blanks</h2>
</div>

<div class="w3-container w3-padding-16">
    <form method="post" class="w3-container w3-card-4 w3-light-grey w3-padding-16">
        <h2>Fill in the Blanks:</h2>
        <?php foreach ($placeholders as $placeholder): ?>
            <label class="w3-text-green" for="<?= str_replace(' ', '_', $placeholder) ?>"><?= ucfirst(str_replace('_', ' ', $placeholder)) ?>: </label>
            <input type="text" name="<?= str_replace(' ', '_', $placeholder) ?>" class="w3-input w3-border w3-margin-bottom"><br>
        <?php endforeach; ?>
        <input type="hidden" name="template" value="<?= htmlspecialchars($template) ?>">
        <button type="submit" class="w3-button w3-green w3-margin-top">Generate GeekyLib</button>
    </form>
</div>

</body>
</html>

<?php
// End output buffering and flush
ob_end_flush();
