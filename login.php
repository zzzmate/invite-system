<?php

session_start();
require_once "./backend/db_con.php";

$errorCode = isset($_GET["errorCode"]) ? isset($_GET["errorCode"]) : "";
$successCode = isset($_GET["successCode"]) ? isset($_GET["successCode"]) : "";

if($_SERVER["REQUEST_METHOD"] == "POST")
{

    if(!$_POST["logUsername"]) return header("Location: login.php?errorCode=Blank username.");
    if(!$_POST["logPassword"]) return header("Location: login.php?errorCode=Blank password.");

    $username = $_POST["logUsername"];
    $password = $_POST["logPassword"];

    $registrationQuery = "SELECT * FROM users WHERE `username` = ?";
    $prepareStmt = mysqli_prepare($connection, $registrationQuery);
    mysqli_stmt_bind_param($prepareStmt, "s", $username);
    mysqli_stmt_execute($prepareStmt);
    $result = mysqli_stmt_get_result($prepareStmt);
    if($rows = mysqli_num_rows($result) < 0) return header("Location: login.php?errorCode=Wrong username or password.");
    $details = mysqli_fetch_assoc($result);
    $detailsPassword = $details["password"];
    $detailsId = $details["id"];
    $detailsMail = $details["email"];
    if($password != $detailsPassword) return header("Location: login.php?errorCode=Wrong username or password.");

    $detailsInvites = $details["invites"];

    $_SESSION["logged_in"] = true;
    $_SESSION["name"] = $username;
    $_SESSION["invites"] = $detailsInvites;
    $_SESSION["id"] = $detailsId;
    $_SESSION["email"] = $detailsMail;

    header("Location: index.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/css/style.css">
    <title>Login</title>
</head>
<body>
    <div class="container">
        <div class="regForm">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <h2>Login</h2>
                <?php if ($errorCode != ""): ?>
                    <p class="error"><?php echo $_GET["errorCode"]; ?></p>
                <?php elseif ($successCode != ""): ?>
                    <p class="success"><?php echo $_GET["successCode"] ?></p>
                <?php endif; ?>
                <input type="text" name="logUsername" placeholder="Username" maxlength="15" id="">
                <input type="password" name="logPassword" placeholder="Password" maxlength="20" id="">
                <button type="submit">Login</button>
            </form>
        </div>
    </div>
</body>
</html>
