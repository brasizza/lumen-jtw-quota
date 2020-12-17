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

    public function __construct(Request $request)
    {
    }


    public function findCep(Request $request, $cep)
    {
        $url = "https://viacep.com.br/ws/{$cep}/json/";
        try {
            $requestCEP = json_decode(file_get_contents($url));
            if (!$requestCEP) {
                return $this->errorResponse('Fail to complete', Response::HTTP_BAD_REQUEST);
            }
            TransactionController::incrementTransactionUser(__METHOD__);
            return $this->successResponse(TransactionController::buildResponse($requestCEP));
        } catch (Exception $e) {
            return $this->errorResponse('Fail to complete', Response::HTTP_BAD_REQUEST);
        }
        // if($cep)
    }
    //
}
