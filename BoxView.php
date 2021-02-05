<!DOCTYPE html>
<html lang="en" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>جعبه لایتنر</title>
    <link rel="stylesheet" href="https://cdn.rtlcss.com/bootstrap/v4.0.0/css/bootstrap.min.css" integrity="sha384-P4uhUIGk/q1gaD/NdgkBIl3a6QywJjlsFJFk7SPRdruoGddvRVSwv5qFnvZ73cpz" crossorigin="anonymous">
    <base href="../../LeitnerBox/">
    <link rel="stylesheet" href="html/leitner.css">
    <?php
    include_once 'header.inc.php';

    ?>

</head>

<body>
    <?php
    function wh_log($log_msg)
    {
        $log_filename = "log";
        if (!file_exists($log_filename)) {
            // create directory/folder uploads.
            mkdir($log_filename, 0777, true);
        }
        $log_file_data = $log_filename . '/letnerlog' . date('d-M-Y') . '.log';
        // if you don't add `FILE_APPEND`, the file will be erased each time you add a log
        file_put_contents($log_file_data, date('Y_m_d_G_i') . var_export($log_msg, true) . "\n", FILE_APPEND);
    }


    date_default_timezone_set('Asia/Tehran');
    error_reporting(E_ALL);
    define('BASE_PATH', '../../LeitnerBox/');
    ini_set('display_errors', '1');
    ini_set("file_uploads", "On");
    ini_set('post_max_size', '4M');
    ini_set('upload_max_filesize', '8M');

    include_once 'database.php';
    include_once 'box.php';
    include_once 'section.php';
    include_once 'card.php';
    include_once 'box_export.php';
    //  include_once 'box_import.php';


    if (isset($_SESSION['UserID'])) { //if login in session is not set
        // TODO : check if user owns box
        $db =  new Database();
        $conn = $db->getConnection();
        if (isset($_REQUEST['box_id'])) {
            $box =  new Box($conn);
            $box->readById($_REQUEST['box_id']);

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                wh_log(" POST:");
                wh_log($_POST);

                if (isset($_REQUEST["create_section"])) {
                    $section =  new Section($conn);
                    $section->box_id = $box->id;

                    $existing_sections = $section->readByBoxId(100, $_REQUEST['box_id']);
                    wh_log("\n existing sections  : ");
                    wh_log($existing_sections);


                    $previous_section = new Section($conn);
                    $previous_section->id =  end($existing_sections)['id'];

                    $previous_section->readById();

                    $section->prev_section = $previous_section->id;

                    $section->create();
                    $previous_section->next_section = $section->id;
                    wh_log("\n prev section : ");
                    wh_log($previous_section);
                    $previous_section->update();

                    wh_log("\n ncreated section : ");
                    wh_log($section);
                } else if (isset($_REQUEST['remove_section_id'])) //if login in session is not set
                {
                    $section =  new Section($conn);
                    $section->id = $_REQUEST['remove_section_id'];
                    $section->deleteByID();
                } else if (isset($_REQUEST["create_card"])) {


                    wh_log("FILEs:");
                    wh_log($_FILES);
                    $card =  new card($conn);
                    $card->section_id = $box->default_section;
                    $card->front_text = $_REQUEST["front_text"];
                    $card->back_text = $_REQUEST["back_text"];

                    $date_str = date("Y_m_d_G_i");
                    if (isset($_FILES["front_image"]) && $_FILES['front_image']['name'] != '') {
                        $file_name = $_SESSION['UserID'] . "_frontimg_" . $date_str . "_" . $_FILES['front_image']['name'];
                        move_uploaded_file($_FILES['front_image']['tmp_name'], BASE_PATH . "user_files/images/" . $file_name);
                        $card->front_image_name = $file_name;
                    }
                    if (isset($_FILES["back_image"])  && $_FILES['back_image']['name'] != '') {
                        $file_name = $_SESSION['UserID'] . "_backimg_" . $date_str . "_" . $_FILES['back_image']['name'];
                        move_uploaded_file($_FILES['back_image']['tmp_name'], BASE_PATH . "user_files/images/" . $file_name);
                        $card->back_image_name = $file_name;
                    }

                    if (isset($_FILES["front_audio"]) && $_FILES['front_audio']['name'] != '') {
                        $file_name = $_SESSION['UserID'] . "_frontaudio_" . $date_str . "_" . $_FILES['front_audio']['name'];
                        echo "\n front audio : ";
                        wh_log($file_name);
                        move_uploaded_file($_FILES['front_audio']['tmp_name'], BASE_PATH . "user_files/audios/" . $file_name);
                        $card->front_audio_name = $file_name;
                    }
                    if (isset($_FILES["back_audio"]) && $_FILES['back_audio']['name'] != '') {
                        $file_name = $_SESSION['UserID'] . "_backaudio_" . $date_str . "_" . $_FILES['back_audio']['name'];
                        echo "\n back audio : ";
                        wh_log($file_name);
                        move_uploaded_file($_FILES['back_audio']['tmp_name'], BASE_PATH . "user_files/audios/" . $file_name);
                        $card->back_audio_name = $file_name;
                    }
                    $card->create();
                } else if (isset($_REQUEST["answer_card"])) {
                    $card =  new card($conn);
                    $card->readById(intval($_REQUEST["card_id"]));
                    $card_section =  new Section($conn);
                    $card_section->id = intval($card->section_id);
                    $card_section->readById();
                    wh_log("card to update");
                    wh_log($card);
                    if ($_REQUEST["answer_card"] == 'true') {
                        wh_log("CORRECT ANSWER , card_id=" . $_REQUEST["card_id"]);
                        if ($card_section->next_section) {
                            $card->section_id = $card_section->next_section;
                            $card->update();
                        }
                    } else if ($_REQUEST["answer_card"] == 'false') {
                        wh_log("WRONG ANSWER , card_id=" . $_REQUEST["card_id"]);
                        if ($card_section->prev_section) {
                            $card->section_id = $card_section->prev_section;
                            $card->update();
                        }
                    }
                } else if (isset($_REQUEST["export"])) {
                    export_box($_REQUEST['box_id'], $conn, $_SESSION['UserID']);
                }

                // echo "<script>window.location.href = window.location.href;</script>";
                die();
            } else {


                $section = new Section($conn);
                $box_sections = $section->readByBoxId(100, $_REQUEST['box_id']);
            }
        } else if (isset($_REQUEST['delCard'])) {
            $id = $_REQUEST['delCard'];
            echo "<script>console.log('$id')</script>";
            $id = explode(",", $id);
            $card = new Card($conn);
            $card->id = $id[1];
            $card->deleteByID();
            header("Location: ./BoxView.php?box_id=$id[0]");
            exit();
        } else {
            die();
        }
    }

    ?>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark ">
        <a class="navbar-brand" href="#">جعبه لایتنر</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsExampleDefault">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="#">خانه<span class="sr-only">(current)</span></a>
                </li>

                </li>
            </ul>
            <form class="form-inline my-2 my-lg-0">
                <input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
            </form>
        </div>
    </nav>

    <main role="main" class="container-fluid">
        <div class="modal-wrapper">

            <div class="create-card-modal" id="create-card-modal">
                <div class="close-modal-btn" onclick="close_all_modals(event)"></div>
                <h3>ساختن یک کارت جدید</h3>
                <form action=<?php echo ($_SERVER['REQUEST_URI']); ?> method="POST" enctype="multipart/form-data">
                    <input type="hidden" id="create-card-action" name="create_card" value="create_card">

                    <input required class="text-inp form-control" type="text" name="front_text" id="front_text" placeholder="متن جلوی کارت">
                    <div class="file-input-wrapper">
                        <input type="file" id="front_image" name="front_image" accept="image/*">
                        <span>آپلود عکس جلوی کارت</span>
                    </div>
                    <div class="file-input-wrapper">
                        <input type="file" id="front_audio" name="front_audio" accept="audio/*">
                        <span>آپلود صدای جلوی کارت</span>
                    </div>
                    <input required class="text-inp form-control" type="text" name="back_text" id="back_text" placeholder="متن پشت کارت">
                    <div class="file-input-wrapper">
                        <input type="file" id="back_image" name="back_image" accept="image/*">
                        <span>آپلود عکس پشت کارت</span>
                    </div>
                    <div class="file-input-wrapper">
                        <input type="file" id="back_audio" name="back_audio" accept="audio/*">
                        <span>آپلود صدای پشت کارت</span>
                    </div>
                    <button class="ltn-button">ساختن</button>
                </form>
            </div>

            <div class="card-modal create-card-modal" id="card-view">

            </div>
        </div>

        <div class="container-rsp">
            <div class="top-header">
                <h1 class="leitner-header" id="<?php echo ($box->id) ?>">
                    <span class="little-text">
                    </span><?php echo $box->title ?>
                </h1>
            </div>
            <hr>
            <div class="create-section">
                <form action=<?php echo ($_SERVER['REQUEST_URI']); ?> method="POST">
                    <input type="hidden" id="create-section-action" name="create_section" value="create_section">
                    <button class="ltn-button">
                        اضافه کردن بخش جدید به جعبه لایتنر
                    </button>
                </form>
            </div>
            <hr>
            <div class="create-cart">
                <a class="ltn-button" onclick="open_create_card_modal(event)">
                    اضافه کردن کارت جدید به جعبه لایتنر
                </a>
            </div>

            <hr>
            <div class="d-flex justify-content-center">

                <form action=<?php echo $_SERVER['REQUEST_URI']; ?> method="post" id="import"><input type="hidden" name="import" value=<?php echo $_REQUEST['box_id'] ?> /> <button type="submit" form="import" class="btn btn-primary ml-3 mr-3">Import</button></form>
                <form action=<?php echo $_SERVER['REQUEST_URI']; ?> method="post" id="export"><input type="hidden" name="export" value=<?php echo $_REQUEST['box_id'] ?> /> <button type="submit" form="export" class="btn btn-primary ml-3 mr-3">Export</button></form>

            </div>
            <hr>
            <div class="leitner-game">

                <?php
                $k = 1;
                foreach ($box_sections as $current_section) {
                    echo '<div class="leitner-section">';
                    echo '<div class="section-header">';
                    echo '<h4 >بخش شماره ' . $k . '</h4>';
                    echo "<form action=\"" . $_SERVER['REQUEST_URI'] . "\" method=\"post\" id=\"form" . $current_section['id'] . "\"><input type=\"hidden\" name=\"remove_section_id\" value=\"" . $current_section['id'] . "\" /><button type=\"submit\" form=\"form" . $current_section['id'] . "\" onlick=\"removeBox()\"  class=\"btn btn-danger\">حذف</button></form>";
                    echo '</div>';
                    echo '<hr>';
                    echo '<div class="card-wrapper">';


                    $card = new Card($conn);
                    $section_cards = $card->readBySectionId(100,  $current_section['id']);


                    foreach ($section_cards as $current_card) {
                        echo '<div class="card">';
                        echo '<div class="top-pic">';
                        echo '<img onerror="this.onerror=null; this.src=\'placeholder.png\'" src="user_files/images/' . $current_card['front_image_name'] . '" alt="alternative">';
                        echo '</div>';
                        echo '<div class="middle-audio">';
                        echo '</div>';
                        echo '<div class="bottom-text">' . $current_card['front_text'] . '</div>';

                        echo "<a  class=\"open-card-btn\"
                        onclick=\"open_card_modal(
                            event,
                            " . $current_card['id'] . ",
                            '" . $current_card['front_text'] . "',
                            'user_files/images/" . $current_card['front_image_name'] . "',
                            'user_files/audios/" . $current_card['front_audio_name'] . "',
                            '" . $current_card['back_text'] . "',
                            'user_files/images/" . $current_card['back_image_name'] . "',
                            'user_files/audios/" . $current_card['back_audio_name'] . "',
                        )\">
                            باز کردن کارت
                        </a>";

                        echo '</div>';
                    }


                    echo '</div>';
                    echo '</div>';
                    $k++;
                }

                ?>

            </div>
        </div>


        <script src="html/leitner.js"></script>
    </main>
</body>