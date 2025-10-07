<?php
$hearder = "No"
. "\t" . "State Name"
. "\t" . "City";
$hearder .=  "\n";
$iCounter = 1;
$data = "";
foreach ($cityList as $rowData)  {
    $data .= $iCounter;
    $data .= "\t" . $rowData->strStateName;
    $data .= "\t" . $rowData->strCityName;
    $iCounter++;
    $data .=  "\n";
}

$filename = 'City_'.date('dmyHis') . '.xls';
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-disposition: attachment; filename=" . $filename);
ob_end_clean();
// echo chr(255) . chr(254) .mb_convert_encoding($firstLine, 'UTF-16LE', 'UTF-8');
echo chr(255) . chr(254) .mb_convert_encoding($hearder, 'UTF-16LE', 'UTF-8');
echo chr(255) . chr(254) .mb_convert_encoding($data, 'UTF-16LE', 'UTF-8');

?>
