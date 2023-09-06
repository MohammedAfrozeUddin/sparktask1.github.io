<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Form</title>
    <link rel="stylesheet" href="CSS/style.css">
    <style>
        <style>
    /* Background color for the entire page */
    /* body {
        background-color: #ffffff; /* White background */
    } */

    .container {
        width: 400px;
        margin: 0 auto;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color: #f9f9f9; /* Light gray background */
    }

    h1 {
        margin-right: 5px;
        font-size: 34px;
        text-align: center;
        margin-bottom: 20px;
        color: #333; /* Dark gray text */
    }

    p.success {
        color: #008000; /* Green text for success message */
        font-weight: bold;
        text-align: center;
    }

    p.error {
        color: #ff0000; /* Red text for error message */
        font-weight: bold;
        text-align: center;
    }

    label {
        display: block;
        font-weight: bold;
        margin-bottom: 5px;
        color: #333; /* Dark gray text for labels */
    }

    select, input[type="number"] {
        width: 100%;
        padding: 8px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 3px;
    }

    button[type="submit"] {
        background-color: #007bff; /* Blue button background */
        color: #fff; /* White text for the button */
        padding: 10px 20px;
        border: none;
        border-radius: 3px;
        cursor: pointer;
        font-weight: bold;
    }

    button[type="submit"]:hover {
        background-color: #0056b3; /* Darker blue on hover */
    }
</style>

    </style>
</head>
<body>
    <div class="navbar">
        <a href="index.html">Home</a>
        <a href="users.php">Users</a>
        <!-- <a href=".php">Transfer Money</a> -->
        <a href="history.php">History</a>
    </div>

    
    <?php
    if (isset($_GET['message']) && $_GET['message'] == 'success') {
        echo "<p class='success'>Transaction was completed successfully</p>";
    }
    if (isset($_GET['message']) && $_GET['message'] == 'transactionDenied') {
        echo "<p class='error'>Transaction Failed</p>";
    }
    ?>
    <div class="container">
        <h1>Make a Transaction</h1>

        <form action="transfer.php" method="POST">
            <label for="receiver"><b>To:</b></label>
            <select name="receiver" id="receiver" class="textbox" required>
                <option value="">Select Recipient</option>
                <?php
                // Connect to your database and retrieve user names from the 'users' table
                $db = new mysqli("localhost", "root", "", "banking");
                
                if ($db->connect_error) {
                    die("Connection failed: " . $db->connect_error);
                }

                $sql = "SELECT Name FROM users";
                $result = $db->query($sql);
                
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['Name'] . "'>" . $row['Name'] . "</option>";
                    }
                    $temp = $row['Name'];
                }

                // $db->close();
                ?>
            </select>
            <br><br>
            <label for="sender"><b>From:</b></label>
            <select name="sender" id="sender" class="textbox" required>
                <option value="">Select Recipient</option>
                <?php
                // Connect to your database and retrieve user names from the 'users' table
                $db = new mysqli("localhost", "root", "", "banking");

                if ($db->connect_error) {
                    die("Connection failed: " . $db->connect_error);
                }

                $sql = "SELECT Name FROM users";
                $result = $db->query($sql);
                // echo $temp;
                // echo "<h2>" . $temp . "</h2>";
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        if($row['Name']!=$temp){
                            echo "<option value='" . $row['Name'] . "'>" . $row['Name'] . "</option>";
                        }
                    }
                }

                $db->close();
                ?>
            </select>
            <br><br>

            <label for="amount"><b>Amount (&#8377;):</b></label>
            <input name="amount" type="number" min="100" class="textbox" required>
            <br><br>

            <button type="submit" name="transfer">Transfer</button>
        </form>
    </div>
</body>
</html>
