<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use Illuminate\Support\Facades\Session;

class RmaDownloadReportController extends Controller
{
    public function index()
    {
        return view('wladmin.rmaDownloadReport.index');
    }

    public function download(Request $request)
    {
        // dd($request);
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
            
            $rmsDatas = DB::table('rma')
                    ->select(
                        'rma.*',
                        'ticketmaster.CustomerName',
                    DB::raw("(SELECT `system`.strSystem FROM `system` WHERE `system`.iSystemId = rma.strSelectSystem) as strSystem")
                    )
                    ->leftJoin('ticketmaster', 'rma.iComplaintId', '=', 'ticketmaster.iTicketId')
                    ->where("rma.OemCompannyId","=", Session::get('CompanyId'))
                    ->orderBy('rma.rma_id', 'desc');
                
                    $rmsData = $rmsDatas->get();
                    
                $groupedRmadetailList = [];
                foreach ($rmsData as $rma) {
                    $rmaId = $rma->rma_id;
                    $rmadetail = DB::table('rma_detail')
                        ->select(
                            'rma_detail.*',
                            DB::raw("(SELECT `system`.strSystem FROM `system` WHERE `system`.iSystemId = rma_detail.strSelectSystem) as strSystem")
                        )
                        ->where('rma_id', $rmaId)
                        ->get()->toArray();

                    $groupedRmadetailList[$rmaId] = (array)$rma;
                    $groupedRmadetailList[$rmaId]['rma_details'] = !empty($rmadetail) ? (array)$rmadetail : [];
                }
                

            return view('wladmin.rmaDownloadReport.download', compact('groupedRmadetailList', 'date_range'));
        } else {
            return redirect()->route('home');
        }
    }
}
