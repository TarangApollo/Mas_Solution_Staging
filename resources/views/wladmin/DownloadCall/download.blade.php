<?php
$firstLine = 'Date Range :' . "\t" . $date_range;
$firstLine .= "\n";
$hearder = 'Compalain ID' . "\t" . 'Status' . "\t" . 'OEM Company' . "\t" . 'Contact Name' . "\t" . 'Contact No.' . "\t" . 'Company Name' . "\t" . 'Company Profile' . "\t" . 'Company Email ID' . "\t" . 'Distributor' . "\t" . 'System' . "\t" . 'Component' . "\t" . 'Sub Component' . "\t" . 'Support Type' . "\t" . 'Issue' . "\t" . 'Issue Type' . "\t" . 'Resolution Details' . "\t" . 'Solution Type' . "\t" . 'Compalaint Date' . "\t" . 'Resolved Date' . "\t" . 'Project' . "\t" . 'Project State' . "\t" . 'Project City' . "\t" . 'Reopen Date' . "\t" . 'CompanySuggested Resolution';
$hearder .= "\n";
$iCounter = 1;
$data = '';
foreach ($callAttendent as $rowData) {
    $Compalain_ID = $rowData->strTicketUniqueID ?? $rowData->Compalain_ID;
    $data .= $Compalain_ID;
    $data .= "\t" . $rowData->Status;
    $data .= "\t" . $rowData->OEM_Company;
    $data .= "\t" . $rowData->Contact_Name;
    $data .= "\t" . $rowData->Contact_No;
    $data .= "\t" . $rowData->Company_Name;
    $data .= "\t" . $rowData->Company_Profile;
    $data .= "\t" . $rowData->Company_Email_ID;
    $data .= "\t" . $rowData->Distributor;
    $data .= "\t" . $rowData->System;
    $data .= "\t" . $rowData->Component;
    $data .= "\t" . $rowData->Sub_Component;
    $data .= "\t" . $rowData->Support_Type;
    $data .= "\t" . $rowData->Issue;
    $data .= "\t" . $rowData->Issue_Type;
    $data .= "\t" . $rowData->Resolution_Details;
    $data .= "\t" . $rowData->Resolution_Category;
    $data .= "\t" . $rowData->Compalaint_Date;
    $data .= "\t" . $rowData->Resolved_Date;
    $data .= "\t" . $rowData->Project;
    $data .= "\t" . $rowData->Project_State;
    $data .= "\t" . $rowData->Project_City;
    $data .= "\t" . $rowData->Reopen_Date;
    $data .= "\t" . $rowData->Suggested_Resolution;
    $iCounter++;
    $data .= "\n";
}

$filename = 'Download_Call_Report_' . date('dmyHis') . '.xls';
header('Content-Type: application/vnd.ms-excel; charset=utf-8');
header('Content-disposition: attachment; filename=' . $filename);
ob_end_clean();
echo chr(255) . chr(254) . mb_convert_encoding($firstLine, 'UTF-16LE', 'UTF-8');
echo chr(255) . chr(254) . mb_convert_encoding($hearder, 'UTF-16LE', 'UTF-8');
echo chr(255) . chr(254) . mb_convert_encoding($data, 'UTF-16LE', 'UTF-8');
