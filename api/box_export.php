<?php
include_once 'database.php';

class Zipper
{

    /**
     * Add files and sub-directories in a folder to zip file.
     *
     * @param string $folder
     *   Path to folder that should be zipped.
     *
     * @param ZipArchive $zipFile
     *   Zipfile where files end up.
     *
     * @param int $exclusiveLength
     *   Number of text to be exclusived from the file path.
     */
    private static function folderToZip($folder, &$zipFile, $exclusiveLength)
    {
        $handle = opendir($folder);

        while (FALSE !== $f = readdir($handle)) {
            // Check for local/parent path or zipping file itself and skip.
            if ($f != '.' && $f != '..' && $f != basename(__FILE__)) {
                $filePath = "$folder/$f";
                // Remove prefix from file path before add to zip.
                $localPath = substr($filePath, $exclusiveLength);

                if (is_file($filePath)) {
                    $zipFile->addFile($filePath, $localPath);
                } elseif (is_dir($filePath)) {
                    // Add sub-directory.
                    $zipFile->addEmptyDir($localPath);
                    self::folderToZip($filePath, $zipFile, $exclusiveLength);
                }
            }
        }
        closedir($handle);
    }

    /**
     * Zip a folder (including itself).
     *
     * Usage:
     *   Zipper::zipDir('path/to/sourceDir', 'path/to/out.zip');
     *
     * @param string $sourcePath
     *   Relative path of directory to be zipped.
     *
     * @param string $outZipPath
     *   Relative path of the resulting output zip file.
     */
    public static function zipDir($sourcePath, $outZipPath)
    {
        $pathInfo = pathinfo($sourcePath);
        $parentPath = $pathInfo['dirname'];
        $dirName = $pathInfo['basename'];

        $z = new ZipArchive();
        $z->open($outZipPath, ZipArchive::CREATE);
        $z->addEmptyDir($dirName);
        if ($sourcePath == $dirName) {
            self::folderToZip($sourcePath, $z, 0);
        } else {
            self::folderToZip($sourcePath, $z, strlen("$parentPath/"));
        }
        $z->close();

        $GLOBALS['status'] = array('success' => 'Successfully created archive ' . $outZipPath);
    }
}
function getTable($table)
{
    $db =  new Database();
    $conn = $db->getConnection();
    $query = "SELECT * from `$table`";
    $stmt = $conn->prepare($query);
    $stmt->execute();

    return  $stmt->fetchAll();
}

if (isset($_REQUEST["path"])) {
    $path = $_REQUEST["path"];

    $tables = ['box', 'card', 'section'];
    $out = "";
    foreach ($tables as $table) {
        $result = getTable($table);
        foreach ($result as $row) {
            for ($i = 0; $i < count($row) / 2; $i++)
                $out .= $row[$i] . ",";
            $out .= "\n";
        }
        $out .= "---\n";
    }

    file_put_contents("../user_files/data.txt", $out);
    Zipper::zipDir("../user_files", "export.zip");
}
