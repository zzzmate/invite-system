<?php

require_once "./backend/db_con.php";

$errorCode = isset($_GET["errorCode"]) ? isset($_GET["errorCode"]) : "";
$successCode = isset($_GET["successCode"]) ? isset($_GET["successCode"]) : "";

if($_SERVER["REQUEST_METHOD"] == "GET") {
    if(isset($_GET["invite"])) {
        $inviteId = $_GET["invite"];
        $currentCurrentDate = date("Y-m-d H:i:s");
        
        $checkInviteQuery = "SELECT * FROM users WHERE `id` = ?";
        $prepareStmt = mysqli_prepare($connection, $checkInviteQuery);
        mysqli_stmt_bind_param($prepareStmt, "s", $inviteId);
        mysqli_stmt_execute($prepareStmt);
        $result = mysqli_stmt_get_result($prepareStmt);
        if($rows = mysqli_num_rows($result) == 0) return header("Location: register.php");
        $details = mysqli_fetch_assoc($result);
        $detailsCooldown = $details["cooldown_invite"];
        if($detailsCooldown > $currentCurrentDate) return header("Location: register.php");
        $detailsInvites = $details["invites"];
        
        $inviteQuery = "UPDATE users SET `invites` = ?, `cooldown_invite` = ? WHERE `id` = ?";
        $prepareStmt = mysqli_prepare($connection, $inviteQuery);
        $setInvites = $detailsInvites + 1;
        $currentDate = date("Y-m-d H:i:s", strtotime('+5 minute'));

        mysqli_stmt_bind_param($prepareStmt, "sss", $setInvites, $currentDate, $inviteId);
        mysqli_stmt_execute($prepareStmt);
    }
}

if($_SERVER["REQUEST_METHOD"] == "POST")
{
    if(!$_POST["regUsername"]) return header("Location: register.php?errorCode=blank username.");
    if(!$_POST["regPassword"]) return header("Location: register.php?errorCode=blank password.");
    if(!$_POST["regPasswordAgain"]) return header("Location: register.php?errorCode=blank password again.");
    if(!$_POST["regEmail"]) return header("Location: register.php?errorCode=blank email.");
    if($_POST["regPassword"] != $_POST["regPasswordAgain"]) return header("Location: register.php?errorCode=password missmatch.");

    $username = $_POST["regUsername"];
    $password = $_POST["regPassword"];
    $email = $_POST["regEmail"];

    $registrationQuery = "SELECT * FROM users WHERE `username` = ?";
    $prepareStmt = mysqli_prepare($connection, $registrationQuery);
    mysqli_stmt_bind_param($prepareStmt, "s", $username);
    mysqli_stmt_execute($prepareStmt);
    $result = mysqli_stmt_get_result($prepareStmt);
    if($rows = mysqli_num_rows($result) > 0) return header("Location: register.php?errorCode=Username already exists.");

    $emailQuery = "SELECT * FROM users WHERE `email` = ?";
    $prepareStmt = mysqli_prepare($connection, $emailQuery);
    mysqli_stmt_bind_param($prepareStmt, "s", $email);
    mysqli_stmt_execute($prepareStmt);
    $result = mysqli_stmt_get_result($prepareStmt);
    if($rows = mysqli_num_rows($result) > 0) return header("Location: register.php?errorCode=Email already exists.");

    $successQuery = "INSERT INTO users (`username`, `password`, `email`, `invites`) VALUES (?, ?, ?, 0)";
    $prepareStmt = mysqli_prepare($connection, $successQuery);
    mysqli_stmt_bind_param($prepareStmt, "sss", $username, $password, $email);
    mysqli_stmt_execute($prepareStmt);

    header("Location: login.php?successCode=Successfull registration.");
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/css/style.css">
    <title>Registration</title>
</head>
<body>
    <div class="container">
        <div class="regForm">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <h2>Registration</h2>
                <?php if ($errorCode != ""): ?>
                    <p class="error"><?php echo $_GET["errorCode"]; ?></p>
                <?php elseif ($successCode != ""): ?>
                    <p class="success"><?php echo $_GET["successCode"] ?></p>
                <?php endif; ?>
                <input type="text" name="regUsername" placeholder="Username" maxlength="15" id="">
                <input type="password" name="regPassword" placeholder="Password" maxlength="20" id="">
                <input type="password" name="regPasswordAgain" placeholder="Password Again" maxlength="20" id="">
                <input type="email" name="regEmail" placeholder="E-Mail" maxlength="25" id="">
                <button type="submit">Registration</button>
            </form>
        </div>
    </div>
</body>
</html>