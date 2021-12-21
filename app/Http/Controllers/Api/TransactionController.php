<?php

namespace App\Http\Controllers\Api;

use DB;
use Auth;
use App\Outlet;
use App\Merchant;
use App\Transaction;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper as ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;
use App\Http\Resources\TransactionCollection;

class TransactionController extends Controller
{
    public function store(Request $request)
    {
        DB::beginTransaction();
        $this->validateRequest($request);
        
        $merchant   = DB::table('merchants')->where('id', '=', $request->merchant_id)->first();
        $outlet     = DB::table('outlets')->where('id', '=', $request->outlet_id)->first();
        
        if (empty($merchant) && empty($outlet)) {
            return response()->json(['message' => 'Data Not Found !'], 404);
        }

        try {
            $transaction = new Transaction;
            $transaction->bill_total    = $request->bill_total;
            $transaction->outlet_id     = $outlet->id;
            $transaction->merchant_id   = $merchant->id;
            $transaction->created_by    = Auth::id();
            $transaction->updated_by    = Auth::id();
            $transaction->save();
            DB::commit();

            return ResponseHelper::successResponse($transaction);
        }

        catch (\Throwable $th){
            DB::rollBack();

            return  response()->json($th->getMessage(), 500);
        }
    }

    function validateRequest($request)
    {
        $this->validate($request, [
            'bill_total' => 'required|numeric',
        ]);
    }
}