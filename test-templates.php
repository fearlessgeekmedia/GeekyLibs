<?php
// Start output buffering to prevent header issues
ob_start();

// Handle Step 1 (input template)
$template = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if 'editor' is set in POST data
    if (isset($_POST['editor'])) {
        $template = $_POST['editor'];
    }

    // Debugging: Output the template value before redirect
    echo "<h3>Template value before redirect (POST Data):</h3><p>" . htmlspecialchars($template) . "</p>";

    // Redirect to Step 2 with the template in the query string, only if template exists
    if (!empty($template)) {
        // Debugging: Output the redirect URL
        echo "<p>Redirecting to fill-in-blanks.php with template: " . htmlspecialchars($template) . "</p>";
        header("Location: fill-in-blanks.php?template=" . urlencode($template));
        exit();
    } else {
        echo "<p>Template is empty. Please make sure you've entered some content.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GeekyLibs - Enter Template</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="http://172.234.245.214/style.css">
    <link rel="stylesheet" href="http://172.234.245.214/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Fira+Code:wght@600&display=swap');
    @import url('https://fonts.googleapis.com/css?family=Lobster&effect=shadow-multiple');
    </style>

    <!-- Use CKEditor 5 LTS version -->
    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/44.3.0/ckeditor5.css" crossorigin>
</head>
<body>

<?php
    require("menu.php");
?>


<div class="w3-container w3-green w3-center">
    <h1 class="fira-code-header">GeekyLibs</h1>
    <h2>Enter Your Template</h2>
</div>

<div class="w3-panel w3-red">
<h2><i class="fa fa-warning"></i> Out of Order</h2>
<p>The template tester is currently out of order while I try to figure out what is wrong with it. It was working properly a few weeks ago but has stopped.</p>
</div>

<div class="w3-container w3-padding-16">
    <form method="post" class="w3-container w3-card-4 w3-light-grey w3-padding-16">

        <div class="main-container">
            <div class="editor-container editor-container_classic-editor editor-container_include-block-toolbar" id="editor-container">
                <div class="editor-container__editor">
                    <!-- Ensure $template is safe for HTML output -->
                    <textarea class="template editor" id="editor" name="editor"><?= htmlspecialchars($template) ?></textarea>
                </div>
            </div>
        </div>

        <button type="submit" class="w3-button w3-green w3-margin-top">Next Step: Fill In the Blanks</button>
    </form>
</div>

<script src="https://cdn.ckeditor.com/ckeditor5/44.3.0/ckeditor5.umd.js" crossorigin></script>
<script>
    let editorInstance;

    ClassicEditor
        .create(document.querySelector('#editor'))
        .then(editor => {
            editorInstance = editor;
        })
        .catch(error => {
            console.error(error);
        });

    // Before form submission, ensure CKEditor content is updated in the textarea
    document.querySelector('form').addEventListener('submit', function(event) {
        // Get the data from CKEditor
        const editorData = editorInstance.getData();

        // Debugging: Output the editor data directly into the page
        const debugContainer = document.createElement('div');
        debugContainer.innerHTML = "<p><strong>CKEditor Data:</strong> " + editorData + "</p>";
        document.body.appendChild(debugContainer);

        // Update the textarea with CKEditor data
        document.querySelector('#editor').value = editorData;
    });
</script>

<script src="./main.js"></script>
</body>
</html>

<?php
// End output buffering to ensure header is sent after the output buffer is flushed
ob_end_flush();
