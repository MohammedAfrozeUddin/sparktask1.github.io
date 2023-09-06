<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>
</head>
<body>
    <h1>Error</h1>
    <p>
        <?php
        // Display the error message passed as a query parameter
        if (isset($_GET['message'])) {
            echo htmlspecialchars($_GET['message']);
        } else {
            echo "An error occurred.";
        }
        ?>
    </p>
    <p><a href="index.html">Go back to the home page</a></p>
</body>
</html>
