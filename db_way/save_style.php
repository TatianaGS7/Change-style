<?php
session_start();
// Connect to Data base
$connection = new Connection($_SESSION['host'], $_SESSION['port'], $_SESSION['db'], $_SESSION['user'], $_SESSION['pass']);
$dbh = $connection->getConnection();

$style = "";
$ip = $_SESSION["IP"];
$data['result']= 0; // 0 - error, 1 - no error

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data['result'] = 1;

    if (empty($_POST['schema'])) {
        $data['result'] = 0;
        $data['error'] = "Schema";
        $data['mes'] = 'Color scheme not selected';
    }
    else {
        $style = $_POST['schema'];
    }
}
else {
    $data['result'] = 0;
    $data['error'] = "POST";
    $data['mes'] = "Error: the transfer method is not POST";
}

// If no error
if ($data['result'] == 1) {
// Find a record in the database with this IP
    $query_ip = $dbh->query("SELECT id FROM style_choice WHERE ip = '".$ip."'");
    // If not, write it to the database
    if ($query_ip->rowCount() != 1){
        $sql_txt = "INSERT INTO style_choice (ip, style) VALUES (?,?)";
        $save_choice= $dbh->prepare($sql_txt);
        $res_save = $save_choice->execute([$ip, $style]);
    }
    else{
        $update_choice = $dbh->query("UPDATE style_choice SET style='$style' WHERE ip='".$ip."'");
    }
    // Test record/update the data
    if ($res_save == true || $update_choice->rowCount()>0) {
        $data['choice'] = $style; 
        $_SESSION["style"] = $style;
    }
    else{
        $data['result'] = 0;
        $data['error'] = "Style";
        $data['mes'] = "The style could not be changed";
    }

}

$connection = null;
echo json_encode($data);