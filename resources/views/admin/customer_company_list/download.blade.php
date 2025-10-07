<?php 
$hearder = "No"
. "\t" . "ID"
. "\t" . "OEM Company Name"
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
. "\t" . "Sales Person 1"
. "\t" . "Sales Person 2"
. "\t" . "Sales Person 3"
. "\t" . "Technical Person 1"
. "\t" . "Technical Person 2"
. "\t" . "Technical Person 3"
. "\t" . "User defined 1"
. "\t" . "User defined 2"
. "\t" . "User defined 3";
$hearder .=  "\n";
$iCounter = 1;
$data = "";
foreach ($CompanyClients as $rowData)  {
    $OEMCompany = DB::table('companymaster')->where(["iCompanyId" => $rowData->iCompanyId])->first();
    $salesperson = DB::table('salesperson')->where(['personType' => 2, "iCompanyClientId" => $rowData->iCompanyClientId])->orderBy('iSalesId', 'DESC')->get();
    $technicalperson = DB::table('technicalperson')->where(['personType' => 2, "iCompanyClientId" => $rowData->iCompanyClientId])->orderBy('iTechnicalId', 'DESC')->get();
    $userdefined = DB::table('userdefined')->where(["type" => 2,"iCompanyClientId" => $rowData->iCompanyClientId])->first();
    
    $iStatus= $rowData->iStatus == 1 ? 'Active' : 'Inactive';
    $data .= $iCounter. "\t" . 'EN'. $rowData->iCompanyClientId;
    $data .= "\t" . $OEMCompany->strOEMCompanyName;
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
    if(count($salesperson) == 3){
        foreach($salesperson as $sales){
           $data .= "\t" . $sales->salesPersonName. "," .$sales->salesPersonNumber.",". $sales->salesPersonEmail ."";
        }
    } else if(count($salesperson) == 2){
        foreach($salesperson as $sales){
           $data .= "\t" . $sales->salesPersonName. "," .$sales->salesPersonNumber.",". $sales->salesPersonEmail ."";
        }
        $data .= "\t" . "";
    } else if(count($salesperson) == 1){
        foreach($salesperson as $sales){
           $data .= "\t" . $sales->salesPersonName. "," .$sales->salesPersonNumber.",". $sales->salesPersonEmail ."";
        }
        $data .= "\t" . "";
        $data .= "\t" . "";
    }else{
        $data .= "\t" . "";
        $data .= "\t" . "";
        $data .= "\t" . "";
    }
    if(count($technicalperson) == 3){
        foreach($technicalperson as $technical){
            $data .= "\t" . $technical->technicalPersonName. "," .$technical->technicalPersonNumber.",". $technical->technicalPersonEmail ."";
        }
    } else if(count($technicalperson) == 2){
        foreach($technicalperson as $technical){
            $data .= "\t" . $technical->technicalPersonName. "," .$technical->technicalPersonNumber.",". $technical->technicalPersonEmail ."";
        }
        $data .= "\t" . "";
    } else if(count($technicalperson) == 1){
        foreach($technicalperson as $technical){
            $data .= "\t" . $technical->technicalPersonName. "," .$technical->technicalPersonNumber.",". $technical->technicalPersonEmail ."";
        }
        $data .= "\t" . "";
        $data .= "\t" . "";
    }else{
        $data .= "\t" . "";
        $data .= "\t" . "";
        $data .= "\t" . "";
    }
    // foreach($technicalperson as $technical){
    //     $data .= "\t" . $technical->technicalPersonName. "," .$technical->technicalPersonNumber.",". $technical->technicalPersonEmail ."<br />";
    // }
    $userDefineOne = $userdefined->userDefine1 ?? "";
    $userDefineTwo = $userdefined->userDefine2 ?? "";
    $userDefineThree = $userdefined->userDefine3 ?? "";
    $data .= "\t" . $userDefineOne;
    $data .= "\t" . $userDefineTwo;
    $data .= "\t" . $userDefineThree;
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