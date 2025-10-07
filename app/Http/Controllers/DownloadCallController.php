<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use Illuminate\Support\Facades\Session;

class DownloadCallController extends Controller
{
    public function index()
    {
        return view('wladmin.DownloadCall.index');
    }

    public function download(Request $request)
    {
        if (Auth::User()->role_id == 2) {
            $formDate = "";
            $toDate = "";
            $date_range = "";
            if ($request->daterange != "") {
                $date_range = $request->daterange;
                $daterange = explode("-", $request->daterange);
                $formDate = date('Y-m-d H:i:s', strtotime($daterange[0]));
                $toDate = date('Y-m-d 23:59:59', strtotime($daterange[1]));
            } else {
                $formDate = date('Y-m-01 00:00:00');
                $toDate  = date('Y-m-t 23:59:59'); // A leap year!
                $date_range = date('m/01/Y') . " - " . date('m/t/Y');
            }

            /*$callAttendent = DB::SELECT("select `iTicketId` as 'Compalain_ID',`ticketName` as 'Status',`companymaster`.`strOEMCompanyName` 'OEM_Company',`CustomerName` as 'Contact_Name',`CustomerMobile` as 'Contact_No',`companyclient`.`CompanyName` 'Company_Name',`icompanyclientprofile`.`strCompanyClientProfile` as 'Company_Profile', IFNULL(ticketmaster.CustomerEmail,'') as 'Company_Email_ID',IFNULL(`companydistributor`.`Name`,'') as 'Distributor',IFNULL(`system`.`strSystem`,'') as 'System', IFNULL(`component`.`strComponent`,'') as 'Component', IFNULL(`subcomponent`.`strSubComponent`,'') as 'Sub_Component',IFNULL(`supporttype`.`strSupportType`,'') as 'Support_Type', 
            IFNULL(ticketmaster.issue,'') as 'Issue', IFNULL(`issuetype`.`strIssueType`,'') as 'Issue_Type',
            IFNULL(ticketmaster.Resolutiondetail,'') as 'Resolution_Details',
            IFNULL(`resolutioncategory`.`strResolutionCategory`,'') as 'Resolution_Category', IFNULL(`ticketmaster`.`ComplainDate`,'') as 'Compalaint_Date',IFNULL(`ticketmaster`.`ResolutionDate`,'') as 'Resolved_Date', 
            IFNULL(ticketmaster.ProjectName,'') as 'Project', IFNULL(`statemaster`.`strStateName`,'') as 'Project_State',
            IFNULL(`citymaster`.`strCityName`,'') as 'Project_City',
            IFNULL((select ticketlog.strEntryDate from ticketlog where ticketlog.iticketId=ticketmaster.iTicketId and ticketlog.iStatus = 3 order by ticketlog.iTicketLogId DESC limit 1),'') as 'Reopen_Date',
            IFNULL((select ticketlog.newResolution from ticketlog where ticketlog.iticketId=ticketmaster.iTicketId order by ticketlog.iTicketLogId DESC limit 1),'') as 'Suggested_Resolution'
            from `ticketmaster` left outer join `ticketstatus` on `ticketstatus`.`istatusId` = `ticketmaster`.`finalStatus` inner join `users` on `users`.`id` = `ticketmaster`.`iCallAttendentId` 
            left outer join `companymaster` on `companymaster`.`iCompanyId` = `ticketmaster`.`OemCompannyId` left outer join `system` on `system`.`iSystemId` = `ticketmaster`.`iSystemId` 
            left outer join `companyclient` on `companyclient`.`iCompanyClientId` = `ticketmaster`.`iCompanyId` left outer join `icompanyclientprofile` on `icompanyclientprofile`.`iCompanyClientProfileId` = `ticketmaster`.`iCompanyProfileId` 
            left outer join `companydistributor` on `companydistributor`.`iDistributorId` = `ticketmaster`.`iDistributorId` left outer join `component` on `component`.`iComponentId` = `ticketmaster`.`iComnentId` 
            left outer join `subcomponent` on `subcomponent`.`iSubComponentId` = `ticketmaster`.`iSubComponentId` left outer join `resolutioncategory` on `resolutioncategory`.`iResolutionCategoryId` = `ticketmaster`.`iResolutionCategoryId` 
            left outer join `issuetype` on `issuetype`.`iSSueTypeId` = `ticketmaster`.`iIssueTypeId` left outer join `statemaster` on `statemaster`.`iStateId` = `ticketmaster`.`iStateId` left outer join `citymaster` on `citymaster`.`iCityId` = `ticketmaster`.`iCityId` 
            left outer join `supporttype` on `supporttype`.`iSuppotTypeId` = `ticketmaster`.`iSupportType` left outer join `callcompetency` on `callcompetency`.`iCallCompetency` = `ticketmaster`.`CallerCompetencyId` 
            where  ticketmaster.strEntryDate>='" . $formDate . "' and ticketmaster.strEntryDate<='" . $toDate . "'");*/

            $callAttendent = DB::SELECT("select `iTicketId` as 'Compalain_ID',`strTicketUniqueID` as 'strTicketUniqueID',
            `ticketName` as 'Status',
            `companymaster`.`strOEMCompanyName` 'OEM_Company',
            `CustomerName` as 'Contact_Name',
            `CustomerMobile` as 'Contact_No',
            `companyclient`.`CompanyName` 'Company_Name',
            `icompanyclientprofile`.`strCompanyClientProfile` as 'Company_Profile', 
            IFNULL(ticketmaster.CustomerEmail,'') as 'Company_Email_ID',
            IFNULL(`companydistributor`.`Name`,'') as 'Distributor',
            IFNULL(`system`.`strSystem`,'') as 'System', 
            IFNULL(`component`.`strComponent`,'') as 'Component', 
            IFNULL((SELECT GROUP_CONCAT(strSubComponent SEPARATOR ',') FROM subcomponent  WHERE FIND_IN_SET(iSubComponentId, ticketmaster.iSubComponentId) GROUP BY iCompanyId),'') as 'Sub_Component',
            IFNULL(`supporttype`.`strSupportType`,'') as 'Support_Type', 
            IFNULL(ticketmaster.issue,'') as 'Issue', 
            IFNULL(`issuetype`.`strIssueType`,'') as 'Issue_Type',
            IFNULL(ticketmaster.Resolutiondetail,'') as 'Resolution_Details',
            IFNULL(`resolutioncategory`.`strResolutionCategory`,'') as 'Resolution_Category', 
            IFNULL(`ticketmaster`.`ComplainDate`,'') as 'Compalaint_Date',IFNULL(`ticketmaster`.`ResolutionDate`,'') as 'Resolved_Date', 
            IFNULL(ticketmaster.ProjectName,'') as 'Project', IFNULL(`statemaster`.`strStateName`,'') as 'Project_State',
            IFNULL(`citymaster`.`strCityName`,'') as 'Project_City',
            IFNULL((select ticketlog.strEntryDate from ticketlog where ticketlog.iticketId=ticketmaster.iTicketId and ticketlog.iStatus = 3 order by ticketlog.iTicketLogId DESC limit 1),'') as 'Reopen_Date',
            IFNULL((select ticketlog.newResolution from ticketlog where ticketlog.iticketId=ticketmaster.iTicketId order by ticketlog.iTicketLogId DESC limit 1),'') as 'Suggested_Resolution'
            from `ticketmaster` left outer join `ticketstatus` on `ticketstatus`.`istatusId` = `ticketmaster`.`finalStatus` inner join `users` on `users`.`id` = `ticketmaster`.`iCallAttendentId` 
            left outer join `companymaster` on `companymaster`.`iCompanyId` = `ticketmaster`.`OemCompannyId` left outer join `system` on `system`.`iSystemId` = `ticketmaster`.`iSystemId` 
            left outer join `companyclient` on `companyclient`.`iCompanyClientId` = `ticketmaster`.`iCompanyId` left outer join `icompanyclientprofile` on `icompanyclientprofile`.`iCompanyClientProfileId` = `ticketmaster`.`iCompanyProfileId` 
            left outer join `companydistributor` on `companydistributor`.`iDistributorId` = `ticketmaster`.`iDistributorId` left outer join `component` on `component`.`iComponentId` = `ticketmaster`.`iComnentId` 
            left outer join `resolutioncategory` on `resolutioncategory`.`iResolutionCategoryId` = `ticketmaster`.`iResolutionCategoryId` 
            left outer join `issuetype` on `issuetype`.`iSSueTypeId` = `ticketmaster`.`iIssueTypeId` left outer join `statemaster` on `statemaster`.`iStateId` = `ticketmaster`.`iStateId` left outer join `citymaster` on `citymaster`.`iCityId` = `ticketmaster`.`iCityId` 
            left outer join `supporttype` on `supporttype`.`iSuppotTypeId` = `ticketmaster`.`iSupportType` left outer join `callcompetency` on `callcompetency`.`iCallCompetency` = `ticketmaster`.`CallerCompetencyId` 
            where  ticketmaster.strEntryDate>='" . $formDate . "' and ticketmaster.strEntryDate<='" . $toDate . "' and OemCompannyId='" . Session::get('CompanyId') . "'");
            return view('wladmin.DownloadCall.download', compact('callAttendent', 'date_range'));
        } else {
            return redirect()->route('home');
        }
    }
}
