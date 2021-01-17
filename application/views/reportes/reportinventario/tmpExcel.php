<?php
$filename=$file_name;
$filename=str_replace(" ","",$filename);
$filename=$filename."_".time();

header("Pragma: public");
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8");
//header("Content-Type: application/vnd.ms-excel; charset=utf-8");
//header("Content-Type: text/csv");application/vnd.openxmlformats-officedocument.spreadsheetml.sheet
header("Content-Disposition: attachment; filename=".$filename.".xls");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");
header('Cache-Control: max-age=0');

echo $data;
?>