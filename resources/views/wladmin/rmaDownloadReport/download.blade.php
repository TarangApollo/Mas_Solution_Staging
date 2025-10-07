<?php

// Start output buffering to prevent any output from being sent prematurely
ob_start();

// Clean the output buffer before setting headers (this prevents unexpected output)
ob_end_clean();

$firstLine = 'Date Range :' . "\t" . $date_range;
$firstLine .= "\n";
$hearder = 'NO' . "\t" . 'RMAID' . "\t" . 'CUSTOMER' . "\t" . 'DISTRIBUTOR' . "\t" . 'MONTH' . "\t" . 'QUANTITY' . "\t" . 'ITEM' . "\t" . 'MODEL NO' . "\t" . 'SYSTEM' . "\t" . 'WARRANTY' . "\t" . 'APPROVED' . "\t" . 'CUSTOMERSTATUS' . "\t" . 'HFISTATUS' . "\t" . 'FACTORY RMA NO' . "\t" . 'RMA REGISTRATION DATE' . "\t" . 'FAULT DESCRIPTION' . "\t" . 'MATERIAL DESPATCHED DATE';
$hearder .= "\n";
$iCounter = 1;
$data = '';

// Loop over the grouped data (RMA + RMA details)
foreach ($groupedRmadetailList as $rmaId => $rmaData) {
    // First row: RMA data
    $rma = (object) $rmaData; // Convert array to object to access properties easily
    
    $data .= $iCounter;
    $data .= "\t" . $rma->iRMANumber;
    $data .= "\t" . $rma->CustomerName;
    $data .= "\t" . $rma->strDistributor;
    $data .= "\t" . \Carbon\Carbon::parse($rma->strRMARegistrationDate)->format('F');
    $data .= "\t" . $rma->strQuantity;
    $data .= "\t" . $rma->strItem;
    $data .= "\t" . $rma->model_number;
    $data .= "\t" . $rma->strSystem;
    $data .= "\t" . $rma->strInwarranty;
    $data .= "\t" . $rma->strReplacementApproved;
    $data .= "\t" . $rma->strStatus;
    $data .= "\t" . $rma->Factory_Status;
    
    $data .= "\t" . $rma->Factory_rma_no;
    $data .= "\t" . date('d-m-Y',strtoTime($rma->strRMARegistrationDate));
    $data .= "\t" . $rma->strFaultdescription;
    $data .= "\t" . date('d-m-Y',strtoTime($rma->strMaterialDispatchedDate));
    $iCounter++;

    $data .= "\n";

    // Check if rma_details are available and print them in the next row
    if (!empty($rmaData['rma_details'])) {
        foreach ($rmaData['rma_details'] as $detail) {
            // If there are details, add them as a new row
            $data .= "\t";  
            $data .= "\t";  
            $data .= "\t"; 
            $data .= "\t"; 
            $data .= "\t" . $detail->strQuantity;
            $data .= "\t" . $detail->strItem;
            $data .= "\t" . $rma->model_number;
            $data .= "\t" . $detail->strSystem;
            $data .= "\t" . $detail->strInwarranty;
            $data .= "\t" . $detail->strReplacementApproved;
            $data .= "\t" . $detail->strStatus;
            $data .= "\t" . $detail->Additional_Factory_Status;
            
            $data .= "\t" . $detail->Additional_Factory_rma_no;
            $data .= "\t" . date('d-m-Y',strtoTime($detail->strRMARegistrationDate));
            $data .= "\t" . $detail->strFaultdescription;
            $data .= "\t" . date('d-m-Y',strtoTime($detail->strRMARegistrationDate));
            $data .= "\n";  // End row
        }
    }
}

$filename = 'Download_RMA_Report_' . date('dmyHis') . '.xls';
header('Content-Type: application/vnd.ms-excel; charset=utf-8');
header('Content-disposition: attachment; filename=' . $filename);
ob_end_clean();
echo chr(255) . chr(254) . mb_convert_encoding($firstLine, 'UTF-16LE', 'UTF-8');
echo chr(255) . chr(254) . mb_convert_encoding($hearder, 'UTF-16LE', 'UTF-8');
echo chr(255) . chr(254) . mb_convert_encoding($data, 'UTF-16LE', 'UTF-8');
