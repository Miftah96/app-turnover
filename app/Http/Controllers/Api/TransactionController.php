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
use Illuminate\Database\Eloquent\Builder;
use App\Http\Resources\TransactionResource;
use App\Http\Resources\TransactionCollection;

class TransactionController extends Controller
{
    public function index(Request $request)
    {

        $month      = $request->month;
        $start_date = $request->start_date;
        $end_date   = $request->end_date;
        $merchant_name  = $request->merchant_name;

        // $data  = DB::table('transactions')->selectRaw('transactions, SUM(bill_total) AS omzet')
        $data  = DB::table('transactions')
                ->select( 'transactions.created_at AS date', DB::raw('SUM(transactions.bill_total) as omzet'))
                ->addSelect('merchants.merchant_name', 'outlets.outlet_name')
                ->when($month, function ($query, $month) {
                    return $query->whereMonth('transactions.created_at', $month);
                })
                ->when($start_date, function ($query, $start_date) {
                    return $query->whereDay('transactions.created_at', '>=', $start_date);
                })
                ->when($end_date, function ($query, $end_date) {
                    return $query->whereDay('transactions.created_at', '<=', $end_date);
                })
                ->join('merchants', 'transactions.merchant_id', '=', 'merchants.id')
                ->join('outlets', 'transactions.outlet_id', '=', 'outlets.id')
                ->groupBy(DB::raw('DATE_FORMAT(transactions.created_at, "%d")'))
                ->where('transactions.updated_by', Auth::id())
                ->where('transactions.created_by', Auth::id())
                ->where('merchants.updated_by', Auth::id())
                ->where('merchants.updated_by', Auth::id())
                ->where('outlets.created_by', Auth::id())
                ->where('outlets.created_by', Auth::id())
                ->paginate();
                
        return ResponseHelper::successResponse($data);
    }
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