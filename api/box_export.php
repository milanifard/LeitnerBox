<?php

include_once 'box.php';
include_once 'section.php';
include_once 'card.php';


function export_box($box_id, $conn, $person_id)
{
    $date_str = date("Y_m_d_G_i");
    $user_dir =  BASE_PATH . 'user_files/boxes/' . $person_id;
    if (!file_exists($user_dir)) {
        mkdir($user_dir, 0777, true);
    }
    $section = new Section($conn);
    $box_sections = $section->readByBoxId(100, $box_id);
    $file_name = $user_dir . '/sections_' . $box_id . '_' . $date_str . '_.json';
    $fp = fopen($file_name, 'w');
    fwrite($fp, json_encode($box_sections));
    fclose($fp);
    foreach ($box_sections as $current_section) {
        $card = new Card($conn);
        $section_cards = $card->readBySectionId(100,  $current_section['id']);
        $file_name = $user_dir . '/cards_' . $box_id . '_' . $date_str . '_.txt';
          


        $sections_var = var_export($section_cards,true);
        $sections_var =  mb_convert_encoding($sections_var, 'UTF-8', 'auto');
        $fp = fopen($file_name, 'w');
        fwrite($fp, $sections_var);
        fclose($fp);
        var_dump($sections_var);
    }
}
