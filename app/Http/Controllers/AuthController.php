<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

/** @package App\Http\Controllers */
class AuthController extends Controller
{

    use ApiResponser;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }


    /**
     * @param Request $request
     * @return array
     * @throws ValidationException
     */
    public function isRegisterValid(Request $request)
    {
        return  $this->validate(
            $request,
            [
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:5'
            ]
        );
    }

    /**
     * @param Request $request
     * @return array
     * @throws ValidationException
     */
    public function isLoginEmailValid(Request $request)
    {

        return $this->validate($request, [
            'email' => 'required|email',
            'password' =>  'required|string'
        ]);
    }




    public function isLoginCredentiallValid(Request $request)
    {
        return $this->validate($request, [
            'client_id' => 'required|string',
            'client_secret' =>  'required|string'
        ]);
    }

    /**
     * @param Request $request
     * @return App\Traits\Iluminate\Http\Response|void
     * @throws ValidationException
     */
    public function login(Request $request)
    {
        if (isset($request->grant_type)) {
            if ($request->grant_type == 'credential') {
                $token = $this->loginWithCredential($request);
            } else {
                $token = $this->loginWithEmail($request);
            }
        } else {
            $token = $this->loginWithEmail($request);
        }

        if ($token) {
            return $this->respondWithToken($token);
        } else {
            return $this->errorResponse('User not found', Response::HTTP_NOT_FOUND);
        }
    }

    public function loginWithEmail(Request $request)
    {

        if ($this->isLoginEmailValid($request)) {
            $credentials = $request->only(['email', 'password']);
            $token = auth()->setTTL(env('JWT_TTL', '60'))->attempt($credentials);
            return $token;
        }
    }

    public function loginWithCredential(Request $request)
    {
        if ($this->isLoginCredentiallValid($request)) {
            $credentials = $request->only(['client_id', 'client_secret']);
            $user =  User::where('client_id', $request->client_id)->where('client_secret', $request->client_secret)->first();
            if ($user) {
                $token = auth()->setTTL(env('JWT_TTL', '60'))->login($user);
                return $token;
            } else {
                return null;
            }
        }
    }

    /**
     * @param Request $request
     * @return App\Traits\Iluminate\Http\Response|App\Traits\Iluminate\Http\JsonResponse|void
     * @throws ValidationException
     */
    public function register(Request $request)
    {
        if ($this->isRegisterValid($request)) {
            try {
                $user = new User();
                $user->password = $request->password;
                $user->email = $request->email;
                $user->name = $request->name;

                $user->save();
                return $this->successResponse($user);
            } catch (\Exception $e) {
                return $this->errorResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
            }
        }
    }



    public function me($detail = false)
    {
        $user = auth()->user();
        $transactions = new TransactionController();
        $userArray = $user->toArray();
        $total_transactions = $transactions->getTotalTransactions();
        $userArray['quota'] = QuotaController::getCurrentQuota();
        $userArray['transactions'] =   $total_transactions;
       ($detail) ?  $userArray['month_transactions'] =   $transactions->getTransactionsMonthDetail() : null;
        $userArray['remaining'] = QuotaController::getCurrentQuota() - $total_transactions;
        return $this->successResponse($userArray);
    }

    public function meDetailed()
    {
        return $this->me(true);
    }


}
