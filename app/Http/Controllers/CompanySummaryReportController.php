<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TicketMaster;
use App\Models\TicketLog;
use App\Models\TicketDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\CompanyClient;
use Auth;
use Carbon\Carbon;

class CompanySummaryReportController extends Controller
{
    public function index(Request $request)
    {
        //dd($request);
        if (Auth::User()->role_id == 2) {
            $daterange = $request->daterange;
            $formDate = "";
            $toDate = "";
            if ($request->daterange != "") {
                $daterange = explode("-", $request->daterange);
                $formDate = date('Y-m-d  H:i:s', strtotime($daterange[0]));
                $toDate = date('Y-m-d 23:59:59', strtotime($daterange[1]));
            }

            $ticketLists = TicketMaster::select(
                DB::raw('DISTINCT ticketmaster.iCompanyId'),
                DB::raw('count(ticketmaster.iTicketId) as issueCount'),
                DB::raw('count(ticketmaster.iCompanyId) as compaycount'),
                DB::raw("(select companyclient.CompanyName from companyclient where companyclient.iCompanyClientId=ticketmaster.iCompanyId) as CompanyName")
            )
                ->join('companyclient', 'companyclient.iCompanyClientId', '=', 'ticketmaster.iCompanyId')
                ->where(['ticketmaster.isDelete' => 0, 'ticketmaster.iStatus' => 1])
                ->when($request->search_city, fn($query, $search_city) => $query->where('ticketmaster.iCityId', $search_city))
                ->when($request->search_state, fn($query, $search_state) => $query->where('ticketmaster.iStateId', $search_state))
                ->when($request->daterange, fn($query, $daterange) => $query->whereBetween('ticketmaster.strEntryDate', [$formDate, $toDate]))
                ->where('ticketmaster.OemCompannyId', '=', Session::get('CompanyId'))
                ->where('companyclient.iCompanyId', '=', Session::get('CompanyId'))
                ->groupBy('ticketmaster.iCompanyId')
                ->orderBy('CompanyName', 'asc');
                if (isset($request->status) && $request->status == "TotalTD") {
                    /*if (isset($request->New_Company_Month) && $request->New_Company_Month != "") {
                        if (isset($request->iCompnayYearID) && $request->iCompnayYearID != "") {
                            $yeardetail = DB::table('yearlog')->where('iYearId', $request->iCompnayYearID)->first();
                        } else {
                            $yeardetail = DB::table('yearlog')->orderBy('iYearId', 'desc')->first();
                        }
                        $year = 0;
                        if($request->New_Company_Month >=4 && $request->New_Company_Month <= 12){
                            $year = date('Y',strtotime($yeardetail->startDate));
                        } else {
                            $year = date('Y',strtotime($yeardetail->endDate));
                        }
                        $endDate = Carbon::create($year, $request->New_Company_Month, 1);
                        $endOfMonth = $endDate->endOfMonth()->format('Y-m-d 23:59:59');
                        $datefrom = date('Y-m-d H:i:s', strtotime($yeardetail->startDate));
                        $ticketLists->where('ticketmaster.strEntryDate', '>=', $datefrom)->where('ticketmaster.strEntryDate', '<=', $endOfMonth);
                    } else {
                        $year = 0;
                        $fMonth = date('m');
                        if($fMonth >=4 && $fMonth <= 12){
                            $year = date('Y',strtotime($yeardetail->startDate));
                        } else {
                            $year = date('Y',strtotime($yeardetail->endDate));
                        }
                        $datefrom = date('Y-m-d H:i:s', strtotime($yeardetail->startDate));
                        $endDate = Carbon::create($year, $fMonth, 1);
                        $endOfMonth = $endDate->endOfMonth()->format('Y-m-d 23:59:59');
                        $ticketLists->where('ticketmaster.strEntryDate', '>=', $datefrom)
                        ->where('ticketmaster.strEntryDate', '<=', $endOfMonth);
                    }
                    
                    $ticketLists->whereNotIn(
                        'ticketmaster.iCompanyId',
                        function ($query) use ($datefrom) {
                            $query->select('ticketmaster.iCompanyId')
                                ->from(with(new TicketMaster)->getTable())
                                ->where('strEntryDate', '<', $datefrom)
                                ->where('OemCompannyId', Session::get('CompanyId'));
                        }
                    );*/
                    $endOfMonth = "";
                    $year = 0;
                    $fMonth = 0;
                    if (isset($request->iCompnayYearID) && $request->iCompnayYearID != "") {
                        $yeardetail = DB::table('yearlog')->where('iYearId', $request->iCompnayYearID)->first();
                    } else {
                        $yeardetail = DB::table('yearlog')->orderBy('iYearId', 'desc')->first();
                    }
                    $datefrom = $yeardetail->startDate;
                    if ($request->New_Company_Month != "") {
                        if($request->New_Company_Month >=4 && $request->New_Company_Month <= 12){
                            $year = date('Y',strtotime($yeardetail->startDate));
                        } else {
                            $year = date('Y',strtotime($yeardetail->endDate));
                        }
                        $fMonth = $request->New_Company_Month;
                        $endDate = Carbon::create($year, $fMonth, 1);
                        $endOfMonth = $endDate->endOfMonth()->format('Y-m-d 23:59:59');
                    }
                    else {
                        $endOfMonth = $yeardetail->endDate;
                    }
                    $ticketLists->whereBetween('ticketmaster.strEntryDate', [$datefrom, $endOfMonth]);
                    if($fMonth > 0){
                        $ticketLists->whereMonth('ticketmaster.strEntryDate', $fMonth);
                    }
                    if($year > 0){
                        $ticketLists->whereYear('ticketmaster.strEntryDate', $year);
                    }
                    
                } else if (isset($request->status) && ($request->status == "TotalAll" || $request->status == "Total")) {
                    if (isset($request->iCompnayYearID) && $request->iCompnayYearID != "") {
                        $yeardetail = DB::table('yearlog')->where('iYearId', $request->iCompnayYearID)->first();
                    } else {
                        $yeardetail = DB::table('yearlog')->orderBy('iYearId', 'desc')->first();
                    }
                    if (isset($request->New_Company_Month) && $request->New_Company_Month != "") {
                        $year = 0;
                        if($request->New_Company_Month >=4 && $request->New_Company_Month <= 12){
                            $year = date('Y',strtotime($yeardetail->startDate));
                        } else {
                            $year = date('Y',strtotime($yeardetail->endDate));
                        }
                        $month = $request->New_Company_Month;; // Assuming fMonth is in 'YYYY-MM' format
                        $endDate = Carbon::create($year, $month, 1);
                        $endOfMonth = $endDate->endOfMonth()->format('Y-m-d 23:59:59');
                    } 
                    
                    if ($formDate == "" || $toDate == "") {
                        $endOfMonth = date('Y-m-d 23:59:59', strtotime($yeardetail->endDate));
                        $ticketLists->where('ticketmaster.strEntryDate', '<=', $endOfMonth);
                    } 
                } else if($request->status == "new"){
                    if (isset($request->iCompnayYearID) && $request->iCompnayYearID != "") {
                        $yeardetail = DB::table('yearlog')->where('iYearId', $request->iCompnayYearID)->first();
                    } else {
                        $yeardetail = DB::table('yearlog')->orderBy('iYearId', 'desc')->first();
                    }
                    if ($request->New_Company_Month != "") {
                        $year = 0;
                        if($request->New_Company_Month >=4 && $request->New_Company_Month <= 12){
                            $year = date('Y',strtotime($yeardetail->startDate));
                        } else {
                            $year = date('Y',strtotime($yeardetail->endDate));
                        }
                        
                        $endDate = Carbon::create($year, $request->New_Company_Month, 1);
                        $end_Date = Carbon::create($year, $request->New_Company_Month, 1)->subMonth();
                        $endOfMonth = $endDate->endOfMonth()->format('Y-m-d 23:59:59');
                        $end_Of_Month = $end_Date->endOfMonth()->format('Y-m-d 23:59:59');
                        $datefrom = date('Y-m-d H:i:s', strtotime($yeardetail->startDate));
                        
                        $dateto = date('Y-m-d 23:59:59', strtotime($yeardetail->endDate));
                        $ticketLists->where('ticketmaster.strEntryDate', '>=', $datefrom)->where('ticketmaster.strEntryDate', '<=', $endOfMonth)
                            ->whereNotIn(
                                'ticketmaster.iCompanyId',
                                function ($query) use ($end_Of_Month) {
                                    $query->select('ticketmaster.iCompanyId')
                                        ->from(with(new TicketMaster)->getTable())
                                        ->where('strEntryDate', '<', $end_Of_Month)
                                        ->where('OemCompannyId', Session::get('CompanyId'));
                                }
                            );
                    } else {
                        if(isset($request->daterange)){
                            $fMonth = date('m',strtotime($toDate));
                            $year = 0;
                            if($fMonth >=4 && $fMonth <= 12){
                                $year = date('Y',strtotime($yeardetail->startDate));
                            } else {
                                $year = date('Y',strtotime($yeardetail->endDate));
                            }
                            $endDate = Carbon::create($year, $fMonth, 1);
                            $end_Date = Carbon::create($year, $fMonth, 1)->subMonth();
                            $endOfMonth = $endDate->endOfMonth()->format('Y-m-d 23:59:59');
                            $end_Of_Month = $end_Date->endOfMonth()->format('Y-m-d 23:59:59');
                            $datefrom = date('Y-m-d H:i:s', strtotime($yeardetail->startDate));
                            
                            $dateto = date('Y-m-d 23:59:59', strtotime($yeardetail->endDate));
                            $ticketLists->where('ticketmaster.strEntryDate', '>=', $datefrom)->where('ticketmaster.strEntryDate', '<=', $endOfMonth)
                                ->whereNotIn(
                                    'ticketmaster.iCompanyId',
                                    function ($query) use ($end_Of_Month) {
                                        $query->select('ticketmaster.iCompanyId')
                                            ->from(with(new TicketMaster)->getTable())
                                            ->where('strEntryDate', '<', $end_Of_Month)
                                            ->where('OemCompannyId', Session::get('CompanyId'));
                                    }
                                );
                        } else {
                            $year = 0;
                            $fMonth = date('m');
                            if($fMonth >=4 && $fMonth <= 12){
                                $year = date('Y',strtotime($yeardetail->startDate));
                            } else {
                                $year = date('Y',strtotime($yeardetail->endDate));
                            }
                            $endDate = Carbon::create($year, $fMonth, 1);
                            
                            $datefrom = date('Y-m-d H:i:s', strtotime($yeardetail->startDate));
                            $dateto = date('Y-m-d 23:59:59', strtotime($yeardetail->endDate));
                            
                            $endOfMonth = $endDate->endOfMonth()->format('Y-m-d 23:59:59');
                            //$ticketLists->where('ticketmaster.strEntryDate', '>=', $datefrom)->where('ticketmaster.strEntryDate', '<=', $endOfMonth)
                            $ticketLists->whereNotIn(
                                'ticketmaster.iCompanyId',
                                function ($query) use ($datefrom) {
                                    $query->select('ticketmaster.iCompanyId')
                                        ->from(with(new TicketMaster)->getTable())
                                        ->where('strEntryDate', '<', $datefrom)
                                        ->where('OemCompannyId', Session::get('CompanyId'));
                                }
                            );
                        }
                    }
                } else {
                    if (isset($request->iCompnayYearID) && $request->iCompnayYearID != "") {
                        $yeardetail = DB::table('yearlog')->where('iYearId', $request->iCompnayYearID)->first();
                    } else {
                        $yeardetail = DB::table('yearlog')->orderBy('iYearId', 'desc')->first();
                    }
                    $year = 0;
                    $year = date('Y',strtotime($yeardetail->endDate));
                    $fMonth = date('m',strtotime($toDate));
                    if($fMonth >=4 && $fMonth <= 12){
                        $year = date('Y',strtotime($yeardetail->startDate));
                    } else {
                        $year = date('Y',strtotime($yeardetail->endDate));
                    }
                    $endDate = Carbon::create($year, $fMonth, 1);
                    //$end_Date = Carbon::create($year, $fMonth, 1)->subMonth();
                    $endOfMonth = $endDate->endOfMonth()->format('Y-m-d 23:59:59');
                    $ticketLists->whereNotIn(
                                'ticketmaster.iCompanyId',
                            function ($query) use ($endOfMonth) {
                                $query->select('CustomerMobile')
                                    ->from(with(new TicketMaster)->getTable())
                                    //->where('strEntryDate', '<', $pastMonthDate)
                                    // ->where('strEntryDate', '<=', $endOfMonth)
                                    ->whereMonth('strEntryDate', date('m',strtotime($endOfMonth)))->whereYear('strEntryDate', date('Y',strtotime($endOfMonth)))
                                    ->where('ticketmaster.OemCompannyId', '=', Session::get('CompanyId'));
                            }
                        );
                }
            $ticketList = $ticketLists->get();
            // $ticketList = $ticketLists->toSql(); 
            // dd($ticketList);    
                
            /*if (isset($request->status) && $request->status == "TotalTD") {
                $fmonth = "";
                if (isset($request->New_Company_Month) && $request->New_Company_Month != "") {
                    $fmonth = $request->New_Company_Month;
                    
                    if (isset($request->iCompnayYearID) && $request->iCompnayYearID != "") {
                        $yeardetail = DB::table('yearlog')->where('iYearId', $request->iCompnayYearID)->first();
                    } else {
                        $yeardetail = DB::table('yearlog')->orderBy('iYearId', 'desc')->first();
                    }
                    $datefrom = date('Y-m-d H:i:s', strtotime($yeardetail->startDate));
                    // $SYear = date('Y',strtotime($yeardetail->startDate));
                    $year = 0;
                    if($request->New_Company_Month >=4 && $request->New_Company_Month <= 12){
                        $year = date('Y',strtotime($yeardetail->startDate));
                    } else {
                        $year = date('Y',strtotime($yeardetail->endDate));
                    }
                    $endDate = Carbon::create($year, $fmonth, 1);
                    // Get the last date and time of the month
                    $endOfMonth = $endDate->endOfMonth()->format('Y-m-d 23:59:59');
                    //$ticketLists->whereMonth('companyclient.strEntryDate', $fmonth)->where('companyclient.strEntryDate', '>=', $datefrom)->where('companyclient.strEntryDate', '<=', $endOfMonth);
                    $ticketLists->where('ticketmaster.strEntryDate', '>=', $datefrom)->where('ticketmaster.strEntryDate', '<=', $endOfMonth);
                } else {
                    //$fmonth = date('m');
                    if (isset($request->iCompnayYearID) && $request->iCompnayYearID != "") {
                        $yeardetail = DB::table('yearlog')->where('iYearId', $request->iCompnayYearID)->first();
                    } else {
                        $yeardetail = DB::table('yearlog')->orderBy('iYearId', 'desc')->first();
                    }
                    $datefrom = date('Y-m-d H:i:s', strtotime($yeardetail->startDate));
                    $year = 0;
                    if($request->New_Company_Month >=4 && $request->New_Company_Month <= 12){
                        $year = date('Y',strtotime($yeardetail->startDate));
                    } else {
                        $year = date('Y',strtotime($yeardetail->endDate));
                    }
                    $fMonth = date('m');
                    $endDate = Carbon::create($year, $fMonth, 1);
        
                    // Get the last date and time of the month
                    $endOfMonth = $endDate->endOfMonth()->format('Y-m-d 23:59:59');
                    
                    $ticketLists->where('ticketmaster.strEntryDate', '>=', $datefrom)
                    ->where('ticketmaster.strEntryDate', '<=', $endOfMonth);
                }
                if ($formDate == "" || $toDate == "") {
                    if (isset($request->iCompnayYearID) && $request->iCompnayYearID != "") {
                        $yeardetail = DB::table('yearlog')->where('iYearId', $request->iCompnayYearID)->first();
                    } else {
                        $yeardetail = DB::table('yearlog')->orderBy('iYearId', 'desc')->first();
                    }
                    $formDate = date('Y-m-d  00:00:00', strtotime($yeardetail->startDate));
                    $toDate = date('Y-m-d 23:59:59', strtotime($yeardetail->endDate));
                    // $ticketLists->whereMonth('ticketmaster.strEntryDate', $fmonth)
                    //         ->where('ticketmaster.strEntryDate', '>=', $formDate)->where('ticketmaster.strEntryDate', '<=', $toDate);
                    //$ticketLists->whereMonth('companyclient.strEntryDate', $fmonth)
                    $ticketLists->where('ticketmaster.strEntryDate', '>=', $formDate)->where('ticketmaster.strEntryDate', '<=', $toDate);
                } else {
                    $ticketLists->whereMonth('ticketmaster.strEntryDate', date('m'))->whereYear('ticketmaster.strEntryDate', date('Y'));
                }
            } else if (isset($request->status) && $request->status == "TotalAll") {
                //dd($request);
                $fmonth = "";
                $endOfMonth = "";
                if (isset($request->iCompnayYearID) && $request->iCompnayYearID != "") {
                    $yeardetail = DB::table('yearlog')->where('iYearId', $request->iCompnayYearID)->first();
                } else {
                    $yeardetail = DB::table('yearlog')->orderBy('iYearId', 'desc')->first();
                }
                if (isset($request->New_Company_Month) && $request->New_Company_Month != "") {
                    $year = 0;
                    if($request->New_Company_Month >=4 && $request->New_Company_Month <= 12){
                        $year = date('Y',strtotime($yeardetail->startDate));
                    } else {
                        $year = date('Y',strtotime($yeardetail->endDate));
                    }
                    $month = $request->New_Company_Month;; // Assuming fMonth is in 'YYYY-MM' format
                    $endDate = Carbon::create($year, $month, 1);
                    $endOfMonth = $endDate->endOfMonth()->format('Y-m-d 23:59:59');
                    $ticketLists->where('ticketmaster.strEntryDate', '<=', $endOfMonth);
                } 
                if ($formDate == "" || $toDate == "") {
                    // if (isset($request->iCompnayYearID) && $request->iCompnayYearID != "") {
                    //     $yeardetail = DB::table('yearlog')->where('iYearId', $request->iCompnayYearID)->first();
                    // } else {
                    //     $yeardetail = DB::table('yearlog')->orderBy('iYearId', 'desc')->first();
                    // }
                    $endOfMonth = date('Y-m-d 23:59:59', strtotime($yeardetail->endDate));
                    $ticketLists->where('ticketmaster.strEntryDate', '<=', $endOfMonth);
                } 
                // else {
                //     $ticketLists->where('companyclient.strEntryDate', '<=', $toDate);
                // }
            } else {
                if (isset($request->iCompnayYearID) && $request->iCompnayYearID != "") {
                    
                    $yeardetail = DB::table('yearlog')->where('iYearId', $request->iCompnayYearID)->first();
                    $datefrom = date('Y-m-d H:i:s', strtotime($yeardetail->startDate));
                    $dateto = date('Y-m-d 23:59:59', strtotime($yeardetail->endDate));
                    if ($request->New_Company_Month != "") {
                        $SYear = date('Y',strtotime($yeardetail->startDate));
                        $year = 0;
                        if($request->New_Company_Month >=4 && $request->New_Company_Month <= 12){
                            $year = date('Y',strtotime($yeardetail->startDate));
                        } else {
                            $year = date('Y',strtotime($yeardetail->endDate));
                        }
                    $end_Date = Carbon::create($year, $request->New_Company_Month, 1)->subMonth();
                    $end_Of_Month = $end_Date->endOfMonth()->format('Y-m-d 23:59:59');
                    $ticketLists->whereMonth('ticketmaster.strEntryDate', $request->New_Company_Month)->whereYear('ticketmaster.strEntryDate', $year)
                        ->whereNotIn(
                            'ticketmaster.iCompanyId',
                            function ($query) use ($end_Of_Month) {
                                $query->select('ticketmaster.iCompanyId')
                                    ->from(with(new TicketMaster)->getTable())
                                    ->where('strEntryDate', '<', $end_Of_Month)
                                    ->where('OemCompannyId', Session::get('CompanyId'));
                            }
                        ); 
                    } else {
                        $startDate = Carbon::parse($datefrom);
                        $endDate = Carbon::parse($dateto);
                        // The date you want to check
                        $checkDate = Carbon::parse(date('Y').'-'.date('m').'-01');
                        if($checkDate >= $startDate && $checkDate <= $endDate){
                            $ticketLists->whereMonth('ticketmaster.strEntryDate', date('m'))->whereYear('ticketmaster.strEntryDate', date('Y')); 
                        } else {
                            $ticketLists->whereMonth('companyclient.strEntryDate', date('m'))->whereYear('companyclient.strEntryDate', date('Y')); 
                        }
                        $ticketLists->whereNotIn(
                            'ticketmaster.iCompanyId',
                            function ($query) use ($datefrom) {
                                $query->select('ticketmaster.iCompanyId')
                                    ->from(with(new TicketMaster)->getTable())
                                    ->where('strEntryDate', '<', $datefrom)
                                    ->where('OemCompannyId', Session::get('CompanyId'));
                            }
                        );
                    }
                    //$ticketLists->where('companyclient.strEntryDate', '>=', $datefrom)->where('companyclient.strEntryDate', '<=', $dateto);
                } else {
                    $yeardetail = DB::table('yearlog')->orderBy('iYearId', 'desc')->first();
                    $datefrom = date('Y-m-d H:i:s', strtotime($yeardetail->startDate));
                    $dateto = date('Y-m-d 23:59:59', strtotime($yeardetail->endDate));
                    if ($request->New_Company_Month != "") {
                        $SYear = date('Y',strtotime($yeardetail->startDate));
                        $year = 0;
                        if($request->New_Company_Month >=4 && $request->New_Company_Month <= 12){
                            $year = date('Y',strtotime($yeardetail->startDate));
                        } else {
                            $year = date('Y',strtotime($yeardetail->endDate));
                        }
                        $ticketLists->whereMonth('ticketmaster.strEntryDate', $request->New_Company_Month)->whereYear('ticketmaster.strEntryDate', $year)
                            ->whereNotIn(
                            'ticketmaster.iCompanyId',
                            function ($query) use ($end_Of_Month) {
                                $query->select('ticketmaster.iCompanyId')
                                    ->from(with(new TicketMaster)->getTable())
                                    ->where('strEntryDate', '<', $end_Of_Month)
                                    ->where('OemCompannyId', Session::get('CompanyId'));
                            }
                        ); 
                    } else {
                        $startDate = Carbon::parse($datefrom);
                        $endDate = Carbon::parse($dateto);
                        // The date you want to check
                        $checkDate = Carbon::parse(date('Y').'-'.date('m').'-01');
                        if($checkDate >= $startDate && $checkDate <= $endDate){
                            $ticketLists->whereMonth('ticketmaster.strEntryDate', date('m'))->whereYear('ticketmaster.strEntryDate', date('Y')); 
                        } else {
                            $ticketLists->whereMonth('ticketmaster.strEntryDate', date('m'))->whereYear('ticketmaster.strEntryDate', date('Y'));  
                        }
                        $ticketLists->whereNotIn(
                            'ticketmaster.iCompanyId',
                            function ($query) use ($datefrom) {
                                $query->select('ticketmaster.iCompanyId')
                                    ->from(with(new TicketMaster)->getTable())
                                    ->where('strEntryDate', '<', $datefrom)
                                    ->where('OemCompannyId', Session::get('CompanyId'));
                            }
                        );
                    }
                }
            }*/
            

            $statemasters = DB::table('statemaster')
                ->where(['isDelete' => 0, "iStatus" => 1])
                ->orderBy('strStateName', 'ASC')
                ->get();
            $citymasters = DB::table('citymaster')
                ->where(['isDelete' => 0, "iStatus" => 1])
                ->orderBy('strCityName', 'ASC')
                ->get();
            $search_city = $request->search_city;
            $search_state = $request->search_state;
            $search_daterange = $request->daterange;
            $status = $request->status;
            return view('wladmin.companySummary.index', compact('ticketList', 'statemasters', 'citymasters', 'search_city', 'search_state', 'search_daterange', 'status'));
        } else {
            return redirect()->route('home');
        }
    }

    public function info(Request $request)
    {
        if (Auth::User()->role_id == 2) {
            $id = $request->iCompanyId;
            $daterange = $request->daterange;
            $formDate = "";
            $toDate = "";
            if ($request->daterange != "") {
                $daterange = explode("-", $request->daterange);
                $formDate = date('Y-m-d', strtotime($daterange[0]));
                $toDate = date('Y-m-d', strtotime($daterange[1]));
            }
            $ticketList = TicketMaster::select(
                'ticketmaster.iTicketId',
                'ticketmaster.strTicketUniqueID',
                'ticketmaster.ProjectName',
                'CustomerName',
                DB::raw("(select companyclient.CompanyName from companyclient where companyclient.iCompanyClientId=ticketmaster.iCompanyId) as CompanyName"),
                DB::raw("(select citymaster.strCityName from citymaster where citymaster.iCityId=ticketmaster.iCityId) as strCityName"),
                DB::raw("(select statemaster.strStateName  from statemaster where statemaster.iStateId=ticketmaster.iStateId) as strStateName")
            )
                ->where(['isDelete' => 0, 'iStatus' => 1])
                ->where('ticketmaster.iCompanyId', '=', $id)
                ->when($request->daterange, fn($query, $daterange) => $query->whereBetween('ticketmaster.strEntryDate', [$formDate, $toDate]))
                ->where('ticketmaster.OemCompannyId', '=', Session::get('CompanyId'))
                ->get();

            $companyClients = CompanyClient::select('CompanyName')
                ->where(['isDelete' => 0, "iStatus" => 1, "companyclient.iCompanyClientId" => $id])
                ->orderBy('CompanyName', 'ASC')
                ->first();
            return view('wladmin.companySummary.info', compact('ticketList', 'companyClients'));
        } else {
            return redirect()->route('home');
        }
    }

    public function callinfo($id)
    {
        if (Auth::User()->role_id == 2) {
            /*$ticketInfo = TicketMaster::
                //select('ticketmaster.*', 'iTicketId', 'ticketName', 'iTicketStatus', 'CustomerMobile', 'CustomerName', 'companymaster.strOEMCompanyName', 'system.strSystem', 'component.strComponent', 'subcomponent.strSubComponent', 'resolutioncategory.strResolutionCategory', 'issuetype.strIssueType', 'ticketmaster.ComplainDate', 'users.first_name', 'users.last_name', 'ticketmaster.ResolutionDate', 'companyclient.CompanyName', 'icompanyclientprofile.strCompanyClientProfile', 'companydistributor.Name', 'statemaster.strStateName', 'citymaster.strCityName', 'supporttype.strSupportType', 'callcompetency.strCallCompetency')
                select(
                    'ticketmaster.*',
                    'iTicketId',
                    'ticketName',
                    'iTicketStatus',
                    'CustomerMobile',
                    'CustomerName',
                    'companymaster.strOEMCompanyName',
                    'system.strSystem',
                    'component.strComponent',
                    //'subcomponent.strSubComponent', 
                    DB::raw('(SELECT GROUP_CONCAT(strSubComponent SEPARATOR ",") AS concatenated_subcomponents
                    FROM subcomponent 
                    WHERE FIND_IN_SET(iSubComponentId, ticketmaster.iSubComponentId)
                    GROUP BY iCompanyId) as strSubComponent'),
                    'resolutioncategory.strResolutionCategory',
                    'issuetype.strIssueType',
                    'ticketmaster.ComplainDate',
                    'users.first_name',
                    'users.last_name',
                    'ticketmaster.ResolutionDate',
                    'companyclient.CompanyName',
                    'icompanyclientprofile.strCompanyClientProfile',
                    'companydistributor.Name',
                    'statemaster.strStateName',
                    'citymaster.strCityName',
                    'supporttype.strSupportType',
                    'callcompetency.strCallCompetency',
                    DB::raw("(select CONCAT(first_name,' ',last_name) from users where users.id=ticketmaster.iTicketEditedBy) as TicketEditedBy"),
                    DB::raw("(select CONCAT(first_name,' ',last_name) from users where users.id=ticketmaster.iCallAttendentId) as TicketCreatedBy"),
                DB::raw("(select CONCAT(first_name,' ',last_name) from users where users.id in (select  callattendent.iUserId from callattendent where callattendent.iCallAttendentId=ticketmaster.iLevel2CallAttendentId)) as TicketAssignTo")
                )
                ->join('ticketstatus', "ticketstatus.istatusId", "=", "ticketmaster.finalStatus", ' left outer')
                ->join('users', "users.id", "=", "ticketmaster.iCallAttendentId")
                ->join('companymaster', "companymaster.iCompanyId", "=", "ticketmaster.OemCompannyId", ' left outer')
                ->join('system', "system.iSystemId", "=", "ticketmaster.iSystemId", ' left outer')
                ->join('companyclient', "companyclient.iCompanyClientId", "=", "ticketmaster.iCompanyId", ' left outer')
                ->join('icompanyclientprofile', "icompanyclientprofile.iCompanyClientProfileId", "=", "ticketmaster.iCompanyProfileId", ' left outer')
                ->join('companydistributor', "companydistributor.iDistributorId", "=", "ticketmaster.iDistributorId", 'left outer')
                ->join('component', "component.iComponentId", "=", "ticketmaster.iComnentId", ' left outer')
                ->join('subcomponent', "subcomponent.iSubComponentId", "=", "ticketmaster.iSubComponentId", ' left outer')
                ->join('resolutioncategory', "resolutioncategory.iResolutionCategoryId", "=", "ticketmaster.iResolutionCategoryId", ' left outer')
                ->join('issuetype', "issuetype.iSSueTypeId", "=", "ticketmaster.iIssueTypeId", ' left outer')
                ->join('statemaster', "statemaster.iStateId", "=", "ticketmaster.iStateId", ' left outer')
                ->join('citymaster', "citymaster.iCityId", "=", "ticketmaster.iCityId", ' left outer')
                ->join('supporttype', "supporttype.iSuppotTypeId", "=", "ticketmaster.iSupportType", ' left outer')
                ->join('callcompetency', "callcompetency.iCallCompetency", "=", "ticketmaster.CallerCompetencyId", ' left outer')
                ->where("iTicketId", "=", $id)
                ->first();*/
            $ticketInfo = TicketMaster::select(
                'ticketmaster.*',
                'iTicketId',
                't2.ticketName as startStatus',
                't1.ticketName as finalstatus',
                't3.ticketName as oldstatus',
                'iTicketStatus',
                'CustomerMobile',
                'CustomerName',
                'companymaster.strOEMCompanyName',
                'system.strSystem',
                'component.strComponent',
                //'subcomponent.strSubComponent',
                DB::raw('(SELECT GROUP_CONCAT(strSubComponent SEPARATOR ",") AS concatenated_subcomponents
                  FROM subcomponent
                  WHERE FIND_IN_SET(iSubComponentId, ticketmaster.iSubComponentId)
                  GROUP BY iCompanyId) as strSubComponent'),
                'resolutioncategory.strResolutionCategory',
                'issuetype.strIssueType',
                'ticketmaster.ComplainDate',
                'users.first_name',
                'users.last_name',
                'ticketmaster.ResolutionDate',
                'companyclient.CompanyName',
                'icompanyclientprofile.strCompanyClientProfile',
                'companydistributor.Name',
                'statemaster.strStateName',
                'citymaster.strCityName',
                'supporttype.strSupportType',
                'callcompetency.strCallCompetency',
                DB::raw("CASE
                        WHEN ticketmaster.iLevel2CallAttendentId != 0 THEN (
                            SELECT ca.iExecutiveLevel
                            FROM callattendent ca
                            WHERE ca.iCallAttendentId = ticketmaster.iLevel2CallAttendentId
                            LIMIT 1
                        )
                        WHEN (
                            SELECT COUNT(*)
                            FROM companyuser cu
                            WHERE cu.iUserId = ticketmaster.iCallAttendentId
                        ) != 0 THEN 2
                        ELSE NULL
                    END AS closelevel"),
                DB::raw("(select iExecutiveLevel from callattendent where callattendent.iUserId=ticketmaster.iCallAttendentId) as openLevel"),
                DB::raw("(select CONCAT(first_name,' ',last_name) from users where users.id=ticketmaster.iTicketEditedBy) as TicketEditedBy"),
                DB::raw("(select CONCAT(first_name,' ',last_name) from users where users.id=ticketmaster.iCallAttendentId) as TicketCreatedBy"),
                DB::raw("(select CONCAT(first_name,' ',last_name) from users where users.id in (select  callattendent.iUserId from callattendent where callattendent.iCallAttendentId=ticketmaster.iLevel2CallAttendentId)) as TicketAssignTo")
            )
                ->join('ticketstatus as t1', "t1.istatusId", "=", "ticketmaster.finalStatus", ' left outer')
                ->join('ticketstatus as t2', "t2.istatusId", "=", "ticketmaster.iTicketStatus", ' left outer')
                ->join('ticketstatus as t3', "t3.istatusId", "=", "ticketmaster.oldStatus", ' left outer')
                ->join('users', "users.id", "=", "ticketmaster.iCallAttendentId")
                //->join('callattendent as l2', "l2.iCallAttendentId", "=", "ticketmaster.iLevel2CallAttendentId", " left outer")
                ->join('companymaster', "companymaster.iCompanyId", "=", "ticketmaster.OemCompannyId", ' left outer')
                ->join('system', "system.iSystemId", "=", "ticketmaster.iSystemId", ' left outer')
                ->join('companyclient', "companyclient.iCompanyClientId", "=", "ticketmaster.iCompanyId", ' left outer')
                ->join('icompanyclientprofile', "icompanyclientprofile.iCompanyClientProfileId", "=", "ticketmaster.iCompanyProfileId", ' left outer')
                ->join('companydistributor', "companydistributor.iDistributorId", "=", "ticketmaster.iDistributorId", 'left outer')
                ->join('component', "component.iComponentId", "=", "ticketmaster.iComnentId", ' left outer')
                //->join('subcomponent', "subcomponent.iSubComponentId", "=", "ticketmaster.iSubComponentId", ' left outer')
                ->join('resolutioncategory', "resolutioncategory.iResolutionCategoryId", "=", "ticketmaster.iResolutionCategoryId", ' left outer')
                ->join('issuetype', "issuetype.iSSueTypeId", "=", "ticketmaster.iIssueTypeId", ' left outer')
                ->join('statemaster', "statemaster.iStateId", "=", "ticketmaster.iStateId", ' left outer')
                ->join('citymaster', "citymaster.iCityId", "=", "ticketmaster.iCityId", ' left outer')
                ->join('supporttype', "supporttype.iSuppotTypeId", "=", "ticketmaster.iSupportType", ' left outer')
                ->join('callcompetency', "callcompetency.iCallCompetency", "=", "ticketmaster.CallerCompetencyId", ' left outer')
                ->where("iTicketId", "=", $id)
                ->first();

            $ticketDetail = TicketDetail::where(["iTicketId" => $id, "iTicketLogId" => 0])->get();
            //$tickethistory[] = array("Status" => $ticketInfo->ticketName, "Level" => "Level " . $ticketInfo->LevelId, "Date" => $ticketInfo->ComplainDate, "user" => $ticketInfo->first_name . " " . $ticketInfo->last_name,"TicketAssignTo" => $ticketInfo->TicketAssignTo);
             if ($ticketInfo->closelevel != '')
                $ticketInfo->LevelId = $ticketInfo->closelevel ?? $ticketInfo->LevelId;
            else
                $ticketInfo->LevelId = $ticketInfo->openLevel ?? $ticketInfo->LevelId;
            $tickethistory[] = array("Status" => 'Complaint Register', "Level" => "Level " . $ticketInfo->LevelId, "Date" => $ticketInfo->oldStatusDatetime, "user" => $ticketInfo->first_name . " " . $ticketInfo->last_name,"TicketAssignTo" => $ticketInfo->TicketAssignTo);
            $tickethistory[] = array("Status" => $ticketInfo->startStatus, "Level" => "Level " . $ticketInfo->LevelId, "Date" => $ticketInfo->ComplainDate, "user" => $ticketInfo->first_name . " " . $ticketInfo->last_name,"TicketAssignTo" => $ticketInfo->TicketAssignTo);

            $ticketLogs = TicketLog::select('ticketlog.*', 'ticketName', 'callattendent.iExecutiveLevel', 'resolutioncategory.strResolutionCategory', 'issuetype.strIssueType', 'callattendent.strFirstName', 'callattendent.strLastName', 'users.first_name', 'users.last_name')
                ->join('ticketstatus', "ticketstatus.istatusId", "=", "ticketlog.iStatus", 'left outer')
                ->join('callattendent', "callattendent.iCallAttendentId", "=", "ticketlog.iCallAttendentId", ' left outer')
                ->join('users', "users.id", "=", "ticketlog.iEntryBy")
                ->join('resolutioncategory', "resolutioncategory.iResolutionCategoryId", "=", "ticketlog.iResolutionCategoryId", ' left outer')
                ->join('issuetype', "issuetype.iSSueTypeId", "=", "ticketlog.iIssueTypeId", ' left outer')
                ->where("iticketId", "=", $id)->get();

            foreach ($ticketLogs as $log) {
                $ticketlogDetail = TicketDetail::where(["iTicketId" => $id, "iTicketLogId" => $log->iTicketLogId])->get();
                $log['gallery'] = $ticketlogDetail;
                if ($log->oldStatus != $log->iStatus){
                    if($log->oldStatus != 3){
                        $tickethistory[] = array("Status" => $log->oldstatus, "Level" => "Level " . $log->LevelId, "Date" => $log->oldStatusDatetime, "user" => $log->first_name . " " . $log->last_name,"TicketAssignTo" => "");
                    }
                }
                $tickethistory[] = array("Status" => $log->ticketName, "Level" => "Level " . $log->LevelId, "Date" => $log->strEntryDate, "user" => $log->first_name . " " . $log->last_name,"TicketAssignTo" => $ticketInfo->TicketAssignTo);
            }
            return view('wladmin.companySummary.callinfo', compact("ticketInfo", "ticketDetail", "ticketLogs", "tickethistory"));
        } else {
            return redirect()->route('home');
        }
    }
}
