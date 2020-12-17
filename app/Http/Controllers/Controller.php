<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponser;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use ApiResponser;
    protected function respondWithToken($token)
    {
        return $this->successResponse([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL()
        ], 200);
    }


    public function successTransaction($service)
    {
        $transaction = new TransactionController();
        $transaction->incrementTransactionUser($service);
    }
}
