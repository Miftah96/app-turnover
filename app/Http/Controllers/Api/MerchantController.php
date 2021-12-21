<?php

namespace App\Http\Controllers\Api;

use DB;
use App\Merchant;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\MerchantResource;
use App\Http\Resources\MerchantCollection;

class MerchantController extends Controller
{
    public function store(Request $request)
    {
        DB::beginTransaction();
        $data = $request->all();

        try {
            $merchant = new Merchant;
            $merchant->fill($data);
            $merchant->user_id = Auth::id();
            $merchant->created_by = Auth::id();
            $merchant->updated_by = Auth::id();
            $merchant->save();
            DB::commit();

            return ResponseHelper::successResponse($merchant);
        }

        catch (\Throwable $th){
            DB::rollBack();

            return ResponseHelper::failedResponse($th->getMessage(), 500);
        }
    }
}
