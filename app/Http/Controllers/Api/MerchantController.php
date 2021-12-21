<?php

namespace App\Http\Controllers\Api;

use DB;
use Auth;
use App\Merchant;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper as ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\MerchantResource;
use App\Http\Resources\MerchantCollection;

class MerchantController extends Controller
{
    public function index(Request $request)
    {
        $data =  Merchant::paginate();

        return ResponseHelper::successResponse($data);
    }

    public function getList(Request $request)
    {
        
        $query  = Merchant::query();

        if ($request->filled('search')){
            $query->where(function ($qr) use ($request){
                $qr->where('merchant_name', 'like', '%'.$request->search.'%');
            });
        }

        // Paginate
        if ($request->pagenumber) {
            $totalperpage   = $request->totalperpage ? $request->totalperpage : 10;
            $query          = $query->paginate($totalperpage, ['*'], 'paginatedata', $request->pagenumber);

            $data['total']          = $query->total();
            $data['totalperpage']   = $totalperpage;
            $data['countperpage']   = count($query);
            $data['currentpage']    = $query->currentPage();
            $data['lastpage']       = $query->lastPage();
        } else {
            $query          = $query->get();
            $data['total']  = $query->count();
        }

        $data['data'] = new MerchantCollection($query);

        return ResponseHelper::successResponse($data);
    }   

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

            return  response()->json($th->getMessage(), 500);
        }
    }
}
