<?php

namespace App\Http\Controllers;

use App\Models\ExtraQuotas;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class QuotaController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */


    public function __construct()
    {
    }

    public function isQuotaValid(Request $request)
    {
        return $this->validate($request, [
            'email' => 'required|email|exists:users,email',
            'quantity' =>  'required|integer'
        ]);
    }


    public static function getCurrentQuota()
    {
        $user = auth()->user();
        $maxQuota = ExtraQuotas::where('user_id', $user->id)->sum('quantity');
        $maxQuota += $user->quota;
        return $maxQuota;
    }

    public static function getRemainingQuota()
    {
        $quota = self::getCurrentQuota();
        $transaction = TransactionController::getTotalTransactions();
        return ($quota - $transaction);
    }


    public function incrementQuota(Request $request)
    {
        $user = auth()->user();
        if ($user->is_admin !== 1) {
            return $this->errorResponse('Method not allowed', Response::HTTP_METHOD_NOT_ALLOWED);
        }
        if ($this->isQuotaValid($request)) {
            $user = User::where('email', $request->email)->first();
            $quota = ExtraQuotas::create([
                'user_id' => $user->id,
                'quantity' => $request->quantity

            ]);
            return $this->successResponse($quota);
        }
    }
}
