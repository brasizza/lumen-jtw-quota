<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponser;
use App\Traits\CheckQuotas;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ApiConsumer extends Controller
{

    use ApiResponser,CheckQuotas;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }


    public function validateToken(){
        try {

            auth()->userOrFail();
            $quota = $this->checkQuota() ;
            if($quota !== true){
                return $quota;
            };
            return $this->successResponse('VALID');
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
            // do something
        }catch(Exception $e){
            return $this->errorResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);

        }


    }


    public function successTransacion(Request $request){

        TransactionController::incrementTransactionUser($request->all());




    }




    //
}
