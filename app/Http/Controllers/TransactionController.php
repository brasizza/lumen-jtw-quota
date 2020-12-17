<?php

namespace App\Http\Controllers;
use App\Models\Transaction;

class TransactionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public static function getTotalTransactions(){
        $user = auth()->user();
        $monthUsage = Transaction::where('user_id', $user->id)->count();
        return $monthUsage ?? 0;
    }


    public static  function getTransactionsMonthDetail()
    {
        $user = auth()->user();
        $firstDayUTS = mktime(0, 0, 0, date("m"), 1, date("Y"));
        $lastDayUTS = mktime(0, 0, 0, date("m"), date('t'), date("Y"));
        $firstDay = date("Y-m-d 00:00:00", $firstDayUTS);
        $lastDay = date("Y-m-d 23:59:59", $lastDayUTS);
        return  Transaction::where('user_id', $user->id)->where('created_at', '>=', $firstDay)->where('created_at', '<=', $lastDay)->get();
    }

    public static function incrementTransaction($service , $response )
    {
        $user = auth()->user();
        $transaction = new Transaction([
            'service' => $service
        ]);
        $transaction->user_id = $user->id;
        $transaction->save();
        return self::buildResponse($response);
    }

    public static function buildResponse($response){
        $jsonResponse['data'] = $response;
        $jsonResponse['remaining'] = QuotaController::getRemainingQuota();
        return $jsonResponse;
    }
}
