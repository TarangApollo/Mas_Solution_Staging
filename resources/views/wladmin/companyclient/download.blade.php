<?php 
$hearder = "No"
. "\t" . "ID"
. "\t" . "Status"
. "\t" . "Company Name"
. "\t" . "Email ID"
. "\t" . "Owner"
. "\t" . "Owner Email" 
. "\t" . "Owner Phone" 
. "\t" . "Address" 
. "\t" . "State Name"
. "\t" . "City Name"
. "\t" . "Branch Office"
;
$hearder .=  "\n";
$iCounter = 1;
$data = "";
foreach ($CompanyClients as $rowData)  {

    $iStatus= $rowData->iStatus == 1 ? 'Active' : 'Inactive';
    $data .= $iCounter. "\t" . 'EN'. $rowData->iCompanyClientId;
    $data .= "\t" . $iStatus;
    $data .= "\t" . $rowData->CompanyName;
    $data .= "\t" . $rowData->email;
    $data .= "\t" . $rowData->owner;
    $data .= "\t" . $rowData->owneremail;
    $data .= "\t" . $rowData->ownerphone;
    $data .= "\t" . $rowData->address;
    $data .= "\t" . $rowData->strStateName;
    $data .= "\t" . $rowData->strCityName;
    $data .= "\t" . $rowData->branchOffice;
    $iCounter++;
    $data .=  "\n";
}

$filename = 'Customer_Companies_'.date('dmyHis') . '.xls';
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-disposition: attachment; filename=" . $filename);
ob_end_clean();
// echo chr(255) . chr(254) .mb_convert_encoding($firstLine, 'UTF-16LE', 'UTF-8');
echo chr(255) . chr(254) .mb_convert_encoding($hearder, 'UTF-16LE', 'UTF-8');
echo chr(255) . chr(254) .mb_convert_encoding($data, 'UTF-16LE', 'UTF-8');

?>