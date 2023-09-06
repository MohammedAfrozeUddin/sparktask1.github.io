<?php
session_start();

$server = "localhost";
$username = "root";
$password = ""; // Provide your MySQL password here
$database = "banking";

// Create a database connection
$con = new mysqli($server, $username, $password, $database);

// Check the connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

$flag = false;

if (isset($_POST['transfer'])) {
    // Assuming you have a form field named 'sender' for the sender's name
    $sender = $_POST['sender'];
    $receiver = $_POST["receiver"];
    $amount = $_POST["amount"];

    // Validate the transaction amount
    if ($amount <= 0) {
        header("Location: error.php?message=Invalid%20transaction%20amount");
        exit;
    }

    // Check if the sender has sufficient balance
    $sql = "SELECT Balance FROM users WHERE Name = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $sender);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        header("Location: error.php?message=Sender%20not%20found");
        exit;
    }

    $row = $result->fetch_assoc();
    $currentBalance = $row["Balance"];

    if ($amount > $currentBalance || $currentBalance - $amount < 100) {
        header("Location: error.php?message=Transaction%20denied:%20Insufficient%20balance%20or%20balance%20below%20minimum%20threshold");
        exit;
    }

    // Use prepared statements to update balances
    $con->begin_transaction();

    $sql = "UPDATE users SET Balance = Balance - ? WHERE Name = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ds", $amount, $sender);
    $stmt->execute();

    $sql = "UPDATE users SET Balance = Balance + ? WHERE Name = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ds", $amount, $receiver);
    $stmt->execute();

    $sql = "INSERT INTO transfer(s_name,s_acc_no,r_name,r_acc_no,amount,date_time) VALUES ('$sender','001','$receiver','002','$amount',NOW())";
    if($con->query($sql)===TRUE){
        echo "New record crated";
    }
    else{
        echo "Error:" . $sql . "<br>" . $con->error;
    }

    // Retrieve sender and receiver IDs
    // $s_id = "";
    // $r_id = "";

    // $sql = "SELECT Sr.No. FROM users WHERE Name = ?";
    // $stmt = $con->prepare($sql);

    // // Bind parameters and execute for sender
    // $stmt->bind_param("s", $sender);
    // $stmt->execute();
    // $result = $stmt->get_result();

    // if ($result->num_rows > 0) {
    //     $row = $result->fetch_assoc();
    //     $s_id = $row['SrNo'];
    // }

    // Bind parameters and execute for receiver
    // $stmt->bind_param("s", $receiver);
    // $stmt->execute();
    // $result = $stmt->get_result();

    // if ($result->num_rows > 0) {
    //     $row = $result->fetch_assoc();
    //     $r_id = $row['SrNo'];
    // }

    // Insert transaction details into the 'transfer' table
    // $sql = "INSERT INTO transfer (s_name, s_acc_no, r_name, r_acc_no, amount) VALUES (?, ?, ?, ?, ?)";
    // $stmt = $con->prepare($sql);
    // $stmt->bind_param("ssssd", $sender, $s_id, $receiver, $r_id, $amount);
    
    // if ($stmt->execute()) {
    //     $flag = true;
    // } else {
    //     $con->rollback();
    //     header("Location: error.php?message=Error%20in%20transaction");
    //     exit;
    // }

    if ($con->commit()) {
        $flag = true;
    } else {
        $con->rollback();
        header("Location: error.php?message=Error%20in%20transaction");
        exit;
    }
}

if ($flag) {
    // Redirect with success message
    $location = 'Details.php?user=' . $sender;
    header("Location: $location&message=success");
    exit;
}

// Close the database connection
$con->close();
?>
