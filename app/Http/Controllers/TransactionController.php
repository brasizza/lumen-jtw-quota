<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;

class TransactionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){}

    public function getTransactionsMonth(){
        $user = auth()->user();
        $firstDayUTS = mktime(0, 0, 0, date("m"), 1, date("Y"));
        $lastDayUTS = mktime(0, 0, 0, date("m"), date('t'), date("Y"));
        $firstDay = date("Y-m-d 00:00:00", $firstDayUTS);
        $lastDay = date("Y-m-d 23:59:59", $lastDayUTS);
        $monthUsage = Transaction::where('user_id', $user->id)->where('created_at', '>=', $firstDay)->where('created_at', '<=', $lastDay)->count();
        return $monthUsage ?? 0;
    }


    public function getTransactionsMonthDetail(){
        $user = auth()->user();
        $firstDayUTS = mktime(0, 0, 0, date("m"), 1, date("Y"));
        $lastDayUTS = mktime(0, 0, 0, date("m"), date('t'), date("Y"));
        $firstDay = date("Y-m-d 00:00:00", $firstDayUTS);
        $lastDay = date("Y-m-d 23:59:59", $lastDayUTS);
        return  Transaction::where('user_id', $user->id)->where('created_at', '>=', $firstDay)->where('created_at', '<=', $lastDay)->get();

    }

    public static function incrementTransactionUser($data){


        $user = auth()->user();
        $transaction = new Transaction($data);
        $transaction->user_id = $user->id;
        $transaction->save();
    }


    //
}
