<?php
// Directory containing your template files
$template_directory = 'templates/';

// Get all .txt files from the directory
$template_files = glob($template_directory . '*.txt');

// Handle the case where no .txt files are found
if (empty($template_files)) {
    die("No templates found in the 'templates/' directory.");
}

// Check if a template file is selected or not
if (isset($_GET['template']) && in_array($template_directory . $_GET['template'], $template_files)) {
    // Load the selected template from the file
    $template = file_get_contents($template_directory . $_GET['template']);
} else {
    // If no template is selected, use the first template or show an error
    $template = ''; // Don't load the template if no selection is made
}

// Regex to match all placeholders like [[adjective]], [[verb]], etc.
preg_match_all('/\[\[(.*?)\]\]/', $template, $matches);

// Get the unique placeholder names
$placeholders = array_unique($matches[1]);

// Initialize an array to hold the user inputs
$user_inputs = [];

// If the form is submitted, collect the inputs
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach ($placeholders as $placeholder) {
        // Sanitize placeholder name for form field (replace spaces with underscores)
        $sanitized_placeholder = str_replace(' ', '_', $placeholder);
        
        // Collect the user input using the sanitized placeholder name
        $user_inputs[$placeholder] = htmlspecialchars($_POST[$sanitized_placeholder] ?? '');
    }

    // Replace placeholders in the template with the user inputs
    foreach ($user_inputs as $placeholder => $input) {
        // Convert underscores back to spaces for final template replacement
        $final_placeholder = str_replace(' ', '_', "[[$placeholder]]");
        
        // Replace the placeholders in the template
        $template = str_replace("[[$placeholder]]", $input, $template);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GeekyLibs</title>

    <link rel="stylesheet" href="http://172.234.245.214/styles.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Fira+Code:wght@600&display=swap');
    @import url('https://fonts.googleapis.com/css?family=Lobster&effect=shadow-multiple');
    </style>
</head>
<body class="w3-light-grey">
  <?php
      require("menu.php");
  ?>
    <div class="w3-container w3-green w3-center">
        <h1 class="fira-code-header">GeekyLibs</h1>
        <h2 class="fira-code-header">from <a href="https://fearlessgeekmedia.com" target="_blank">Fearless Geek Media</a></h2>
    </div>

    <div class="w3-container w3-padding-16">
        <?php if ($_SERVER['REQUEST_METHOD'] != 'POST'): ?>
            <!-- Dropdown for selecting a template -->
            <form method="get" class="w3-container w3-card-4 w3-light-grey w3-padding-16">
                <label class="w3-text-green" for="template">Select a Template: </label>
                <select name="template" id="template" class="w3-select w3-border w3-margin-bottom">
                    <?php foreach ($template_files as $file): ?>
                        <option value="<?= basename($file) ?>" <?= (isset($_GET['template']) && $_GET['template'] == basename($file)) ? 'selected' : '' ?>>
                            <?= basename($file) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="w3-button w3-green w3-margin-top">Select Template</button>
            </form>

            <?php if (isset($_GET['template']) && in_array($template_directory . $_GET['template'], $template_files)): ?>
                <!-- Form to collect words dynamically based on the placeholders -->
                <form method="post" class="w3-container w3-card-4 w3-light-grey w3-padding-16">
                    <h2>Please Fill in the Blanks</h2>
                    <?php foreach ($placeholders as $placeholder): ?>
                        <?php
                        // Sanitize the placeholder for the form field name (replace spaces with underscores)
                        $sanitized_placeholder = str_replace(' ', '_', $placeholder);
                        ?>
                        <label class="w3-text-green" for="<?= $sanitized_placeholder ?>"><?= ucfirst(str_replace('_', ' ', $placeholder)) ?>: </label>
                        <input type="text" name="<?= $sanitized_placeholder ?>" id="<?= $sanitized_placeholder ?>" class="w3-input w3-border w3-margin-bottom" value="<?= $user_inputs[$placeholder] ?? '' ?>"><br>
                    <?php endforeach; ?>
                    <button type="submit" class="w3-button w3-green w3-margin-top">Generate GeekyLib</button>
                </form>
            <?php endif; ?>
        <?php else: ?>
            <!-- Show the final Mad Lib after form submission -->
            <h2 class="fira-code-header">Your GeekyLib:</h2>
            <div class="w3-container w3-padding-16 w3-card-4 w3-light-grey">
                <p><?= nl2br($template) ?></p>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>
