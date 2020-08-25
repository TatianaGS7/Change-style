<?php
session_start();
// Connect to Data base
// Data Base has 1 table 'style_choice' (id, ip(varchar), style(varchar))

// Get the user's IP
$q_ip = gethostbyname($_SERVER['HTTP_CLIENT_IP']);
if (empty($q_ip)){
    $_SESSION["IP"] = gethostbyname($_SERVER['SERVER_ADDR']);
}
else{
    $_SESSION["IP"] = $q_ip;
}

// Request to db
if (empty($_SESSION["style"])) {
    $connection = new Connection($_SESSION['host'], $_SESSION['port'], $_SESSION['db'], $_SESSION['user'], $_SESSION['pass']);
    $dbh = $connection->getConnection();
    $result = $dbh->query("SELECT style FROM style_choice WHERE ip = '".$_SESSION["IP"]."'");
    $connection = null;
        // If there is no record in the database for this IP, then set the default value $_SESSION["style"]
    if ($result->rowCount() != 1) {
        $_SESSION["style"] = "s_1";
    }
    else{
        $res = $result->fetch(PDO::FETCH_NUM);
        $_SESSION["style"] = $res[0];
    }
}

echo json_encode($_SESSION["style"]);