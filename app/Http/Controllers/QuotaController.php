<?php

namespace App\Http\Controllers;

use App\Models\ExtraQuotas;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Contracts\Container\BindingResolutionException;

class QuotaController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */


    public function __construct(){}

    public function getQuotaCurrentMonth(){

        $user = auth()->user();
        $firstDayUTS = mktime(0, 0, 0, date("m"), 1, date("Y"));
        $lastDayUTS = mktime(0, 0, 0, date("m"), date('t'), date("Y"));
        $firstDay = date("Y-m-d 00:00:00", $firstDayUTS);
        $lastDay = date("Y-m-d 23:59:59", $lastDayUTS);
        $maxQuotaMonth = ExtraQuotas::where('user_id', $user->id)->where('expiration', '>=', $firstDay)->where('expiration', '<=', $lastDay)->sum('quantity');
        $maxQuotaMonth+=$user->quota;
        return $maxQuotaMonth;
    }

    public function getRemainingQuota(){
        $quota = $this->getQuotaCurrentMonth();
        $transactionController = new TransactionController();
        $transaction = $transactionController->getTransactionsMonth();
        return ($quota-$transaction);
    }
    //
}
