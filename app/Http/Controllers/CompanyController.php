<?php

namespace App\Http\Controllers;

use App\Helpers\GuzzleHelper;
use App\Traits\ApiResponser;
use Exception;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CompanyController extends Controller
{
    use ApiResponser;
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct(){}

    public function cep($cep)
    {
        $url = "https://viacep.com.br/ws/{$cep}/json/";
        try {
            $requestCEP = json_decode(file_get_contents($url));
            if (!$requestCEP) {
                return $this->errorResponse('Fail to complete', Response::HTTP_BAD_REQUEST);
            }
            $response = (TransactionController::incrementTransaction(__METHOD__, $requestCEP));
            if($response){
                return $this->successResponse($response);
            }else{
                return $this->errorResponse('Fail to complete', Response::HTTP_BAD_REQUEST);
            }
        } catch (Exception $e) {
            return $this->errorResponse('Fail to complete', Response::HTTP_BAD_REQUEST);
        }
    }
}
