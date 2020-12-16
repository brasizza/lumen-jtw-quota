<?php
namespace App\Traits;

use App\Http\Controllers\QuotaController;
use Illuminate\Http\Response;

trait CheckQuotas{
    protected $user;
    public function checkQuota(){
        $quotaController = new QuotaController();
        $remaining = $quotaController->getRemainingQuota();
        if($remaining <= 0){
            return $this->errorResponse('Quota Exceeded' , Response::HTTP_FORBIDDEN);
        }
        return true;
    }

}
