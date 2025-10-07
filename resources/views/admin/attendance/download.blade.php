<?php
$firstLine = "Date Range :"
    . "\t" . $date_range;
$firstLine .=  "\n";
$hearder = "No"
. "\t" . "ID"
. "\t" . "Name"
. "\t" . "Contact"
. "\t" . "Email_ID"
. "\t" . "Executive_Level"
. "\t" . "Days_Count" ;
$hearder .=  "\n";
$iCounter = 1;
$data = "";
foreach ($callAttendent as $rowData)  {
    $loginCount = \App\Models\Loginlog::select(DB::raw("count(DISTINCT(DATE_format(strEntryDate,'%Y-%m-%d'))) as cnt"))
        ->where('strEntryDate','>=',$formDate)->where('strEntryDate','<=',$toDate)
        ->where(["userId" => $rowData->iUserId,"action" => 'Login'])
        ->first();
    // dd($rowData->iCallAttendentId);
    $iExecutiveLevel= $rowData->iExecutiveLevel == 1 ? 'Level_1' : 'Level_2';
    $data .= $iCounter. "\t" . 'EN'. $rowData->iCallAttendentId;
    $data .= "\t" . $rowData->strFirstName ." ". $rowData->strLastName;
    $data .= "\t" . $rowData->strContact;
    $data .= "\t" . $rowData->strEmailId;
    $data .= "\t" . $iExecutiveLevel;
    $data .= "\t" . $loginCount->cnt;
    $iCounter++;
    $data .=  "\n";
}
echo $data;

$filename = 'Attendant_'.date('dmyHis') . '.xls';
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-disposition: attachment; filename=" . $filename);
ob_end_clean();
echo chr(255) . chr(254) .mb_convert_encoding($firstLine, 'UTF-16LE', 'UTF-8');
echo chr(255) . chr(254) .mb_convert_encoding($hearder, 'UTF-16LE', 'UTF-8');
echo chr(255) . chr(254) .mb_convert_encoding($data, 'UTF-16LE', 'UTF-8');