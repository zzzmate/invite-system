<?php

require_once "./backend/db_con.php";

session_start();

if(isset($_SESSION["logged_in"]) != true) return header("Location: login.php");

$name = $_SESSION["name"];
$invites = $_SESSION["invites"];
$id = $_SESSION["id"];
$mail = $_SESSION["email"];

$registeredUsers = "SELECT COUNT(DISTINCT username) AS registrated_users FROM users";
$registeredUsersRes = $connection->query($registeredUsers);
$registeresUsersRow = $registeredUsersRes->fetch_assoc();
$registeredPersons = $registeresUsersRow["registrated_users"]; // THIS IS REAL TIME TOO


// LIVE INVITE COUNT, IF U INVITE SOMEBODY, THE VALUE CHANGES WHEN U REFRESH THE SITE, U CAN DO THIS WITH THE OTHER THINGS TOO

$registrationQuery = "SELECT `invites` FROM users WHERE `username` = ?";
$prepareStmt = mysqli_prepare($connection, $registrationQuery);
mysqli_stmt_bind_param($prepareStmt, "s", $name);
mysqli_stmt_execute($prepareStmt);
$result = mysqli_stmt_get_result($prepareStmt);
$details = mysqli_fetch_assoc($result);
$detailsRealTimeInvites = $details["invites"];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/css/style.css">
    <script src="./assets/js/copyInvite.js"></script>
    <title>Registration</title>
</head>
<body>
    <div class="container">
        <div class="informations">
            <h2>Welcome, <?php echo htmlspecialchars($name) ?></h2>
            <p>Invites: <strong><?php echo htmlspecialchars($detailsRealTimeInvites) ?></strong></p>
            <p>Your email: <strong><?php echo htmlspecialchars($mail)?></strong></p>
            <p style="margin-bottom: 15px;">Registered users: <strong><?php echo htmlspecialchars($registeredPersons) ?></strong></p>
            <p>Your invite code: <a href="#" onclick="copyClipboard(<?php echo htmlspecialchars($id) ?>)">copy</a></p>
            <a href="logout.php">Logout</a>
        </div>
    </div>
</body>
</html>