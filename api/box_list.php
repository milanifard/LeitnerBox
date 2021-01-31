<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

include_once '../config/database.php';
include_once '../model/box.php';

$db =  new Database();
$conn = $db->getConnection();
?>

<?php
if (isset($_REQUEST["box-name"])) {

    $box =  new Box($conn);
    $box->ownerId = $_REQUEST["ownerId"];

    $box->title = $_REQUEST["box-name"];
    if (isset($_REQUEST["box-description"]))
        $box->description_text = $_REQUEST["box-description"];
    $box->create();
    echo "<script>window.location.href = '../../sadaf/sadaf/LeitnerBox.php';</script>";
}
if (isset($_REQUEST['remove_id'])) //if login in session is not set
{
    $box =  new Box($conn);
    $box->id = $_REQUEST['remove_id'];
    $box->deleteByID();
    echo "<script>window.location.href = '../../sadaf/sadaf/LeitnerBox.php';</script>";
}
?>