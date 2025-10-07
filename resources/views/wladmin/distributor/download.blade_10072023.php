<?php 
$hearder = "No"
. "\t" . "ID"
. "\t" . "Status"
. "\t" . "Distributor Name"
. "\t" . "Email ID"
. "\t" . "Contact"
. "\t" . "Address" 
. "\t" . "State" 
. "\t" . "City" 
. "\t" . "Branch Office"
. "\t" . "Sales Person"
. "\t" . "Technical Person"
. "\t" . "User defined";
$hearder .=  "\n";
$iCounter = 1;
$data = "";
foreach ($distributors as $rowData)  {
    $salesperson = DB::table('salesperson')->where(['personType' => 2, "iCompanyClientId" => $rowData->iDistributorId])->orderBy('iSalesId', 'DESC')->get();
    $technicalperson = DB::table('technicalperson')->where(['personType' => 2, "iCompanyClientId" => $rowData->iDistributorId])->orderBy('iTechnicalId', 'DESC')->get();
    $userdefined = DB::table('userdefined')->where(["type" => 2,"iCompanyClientId" => $rowData->iDistributorId])->first();
    //dd($userdefined->userDefine1);
    $iStatus= $rowData->iStatus == 1 ? 'Active' : 'Inactive';
    $data .= $iCounter. "\t" . 'EN'. $rowData->iDistributorId;
    $data .= "\t" . $iStatus;
    $data .= "\t" . $rowData->Name;
    $data .= "\t" . $rowData->EmailId;
    $data .= "\t" . $rowData->distributorPhone;
    $data .= "\t" . $rowData->Address;
    $data .= "\t" . $rowData->strStateName;
    $data .= "\t" . $rowData->strCityName;
    $data .= "\t" . $rowData->branchOffice;
    foreach($salesperson as $sales){
        // $data .= "". "\t" . "";
        // $data .= "\t" . "";
        // $data .= "\t" . "";
        // $data .= "\t" . "";
        // $data .= "\t" . "";
        // $data .= "\t" . "";
        // $data .= "\t" . "";
        // $data .= "\t" . "";
        // $data .= "\t" . "";
        $data .= "\t" . $sales->salesPersonName. "," .$sales->salesPersonNumber.",". $sales->salesPersonEmail ."<br />";
    }
    foreach($technicalperson as $technical){
        // $data .= "". "\t" . "";
        // $data .= "\t" . "";
        // $data .= "\t" . "";
        // $data .= "\t" . "";
        // $data .= "\t" . "";
        // $data .= "\t" . "";
        // $data .= "\t" . "";
        // $data .= "\t" . "";
        // $data .= "\t" . "";
        // $data .= "\t" . "";
        $data .= "\t" . $technical->technicalPersonName. "," .$technical->technicalPersonNumber.",". $technical->technicalPersonEmail ."<br />";
    }
    $userDefineOne = $userdefined->userDefine1 ?? "";
    $userDefineTwo = $userdefined->userDefine2 ?? "";
    $userDefineThree = $userdefined->userDefine3 ?? "";
    $data .= "\t" . $userDefineOne . "," .$userDefineTwo .",". $userDefineThree ."<br />";
    $iCounter++;
    $data .=  "\n";
}

$filename = 'Distributor_'.date('dmyHis') . '.xls';
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-disposition: attachment; filename=" . $filename);
ob_end_clean();
// echo chr(255) . chr(254) .mb_convert_encoding($firstLine, 'UTF-16LE', 'UTF-8');
echo chr(255) . chr(254) .mb_convert_encoding($hearder, 'UTF-16LE', 'UTF-8');
echo chr(255) . chr(254) .mb_convert_encoding($data, 'UTF-16LE', 'UTF-8');

?>