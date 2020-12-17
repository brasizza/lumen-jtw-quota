<?php

namespace App\Http\Controllers;

use App\Models\ExtraQuotas;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;

class QuotaController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */


    public function __construct(){}

    public function getCurrentQuota(){
        $user = auth()->user();
        $maxQuota = ExtraQuotas::where('user_id', $user->id)->sum('quantity');
        $maxQuota+=$user->quota;
        return $maxQuota;
    }

    public function getRemainingQuota(){
        $quota = $this->getCurrentQuota();

        $transactionController = new TransactionController();
        $transaction = $transactionController->getTotalTransactions();
        return ($quota-$transaction);
    }

    public function incrementQuota(Request $request){
       $user = User::where('email' , $request->email)->first();
       $quota = ExtraQuotas::create([
            'user_id' => $user->id,
            'quantity' => $request->quantity

        ]);
        return $this->successResponse($quota);
    }
}
