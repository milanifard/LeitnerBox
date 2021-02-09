<?php
include_once('database.php');
ini_set('upload_max_filesize', '20M');
ini_set('post_max_size', '25M');
function recurse_copy($src, $dst)
{
    $dir = opendir($src);
    @mkdir($dst);
    while (false !== ($file = readdir($dir))) {
        if (($file != '.') && ($file != '..')) {
            if (is_dir($src . '/' . $file)) {
                recurse_copy($src . '/' . $file, $dst . '/' . $file);
            } else {
                copy($src . '/' . $file, $dst . '/' . $file);
            }
        }
    }
    closedir($dir);
}
function recursive_rmdir($dir)
{
    if (is_dir($dir)) {
        $objects = array_diff(scandir($dir), array('..', '.'));
        foreach ($objects as $object) {
            $objectPath = $dir . "/" . $object;
            if (is_dir($objectPath))
                recursive_rmdir($objectPath);
            else
                unlink($objectPath);
        }
        rmdir($dir);
    }
}


$check = move_uploaded_file($_FILES['import']['tmp_name'], "./export.zip");

$codeError = $_FILES['import']['error'];
if ($codeError == 1) {
    echo "<script>alert('خطا: حجم فایل انتخاب شده بیش از حدمجاز است');window.location.href = '../../www/sadaf/LeitnerBox.php';</script>";
} else if ($codeError == 0) {
    $path = "export.zip";
    mkdir("export");
    $unzip = new ZipArchive;
    $out = $unzip->open($path);
    if ($out === TRUE) {
        $unzip->extractTo("./export");
        $unzip->close();
    } else {
        echo 'Error';
    }
    recurse_copy("./export/user_files", "../user_files");
    recursive_rmdir("./export");

    $db =  new Database();
    $conn = $db->getConnection();


    $tables = ['box', 'card', 'section'];
    $data = file_get_contents("../user_files/data.txt");
    $data = explode("\n---\n", $data);

    $query = "";
    $attrs = "";
    foreach ($data as $i => $dataBase) {
        if ($i == count($tables))
            break;
        $dataBase = explode("\n", $dataBase);
        foreach ($dataBase as $row) {
            $row = explode(",", $row);
            $stmt = $conn->prepare("DESCRIBE $tables[$i]");
            $stmt->execute();
            $type_attrs = $stmt->fetchAll();

            $query = "";
            $attrs = "";
            foreach ($row as $j => $attr) {
                if (!$attr)
                    continue;
                if (strpos($type_attrs[$j][1], 'int') !== false) {
                    $attrs .= $type_attrs[$j][0] . ",";
                    $query .= $attr . ",";
                } else {
                    $attrs .= $type_attrs[$j][0] . ",";
                    $query .= "'$attr'" . ",";
                }
            }
            $query = substr($query, 0, -1);
            $attrs = substr($attrs, 0, -1);


            $stmt = $conn->prepare("INSERT into $tables[$i]($attrs) VALUES ($query) on DUPLICATE KEY UPDATE id=id");
            $stmt->execute();
        }
        // $query = "";

    }
    header("Location: ../../www/sadaf/LeitnerBox.php");
}
