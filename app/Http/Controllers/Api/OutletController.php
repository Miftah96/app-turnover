<?php

namespace App\Http\Controllers\Api;

use DB;
use Auth;
use App\Outlet;
use App\Merchant;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper as ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\OutletResource;
use App\Http\Resources\OutletCollection;

class OutletController extends Controller
{
    public function index(Request $request)
    {
        $data =  Outlet::with('merchant', 'merchant.user')->get();

        return ResponseHelper::successResponse($data);
    }

    public function getList(Request $request)
    {
        
        $query  = Outlet::query();

        if ($request->filled('search')){
            $query->where(function ($qr) use ($request){
                $qr->where('outlet_name', 'like', '%'.$request->search.'%');
            });
        }

        // Paginate
        if ($request->pagenumber) {
            $totalperpage = $request->totalperpage ? $request->totalperpage : 10;
            $query = $query->paginate($totalperpage, ['*'], 'paginatedata', $request->pagenumber);

            $data['total'] = $query->total();
            $data['totalperpage'] = $totalperpage;
            $data['countperpage'] = count($query);
            $data['currentpage'] = $query->currentPage();
            $data['lastpage'] = $query->lastPage();
        } else {
            $query = $query->get();
            $data['total'] = $query->count();
        }

        $data['data'] = new OutletCollection($query);

        return ResponseHelper::successResponse($data);
    }   

    public function store(Request $request)
    {
        DB::beginTransaction();
        // $this->validateRequest($request);
        
        $merchant = DB::table('merchants')->where('id', '=', $request->merchant_id)->first();
        
        if (empty($merchant)) {
            return response()->json(['message' => 'Data Not Found !'], 404);
        }

        try {
            $outlet = new Outlet;
            $outlet->merchant_id  = $merchant->id;
            $outlet->outlet_name  = $request->outlet_name;
            $outlet->created_by   = Auth::id();
            $outlet->updated_by   = Auth::id();
            $outlet->save();
            DB::commit();

            return ResponseHelper::successResponse($outlet);
        }

        catch (\Throwable $th){
            DB::rollBack();

            return  response()->json($th->getMessage(), 500);
        }
    }

    function validateRequest($request)
    {
        $this->validate($request, [
            'outlet_name' => 'required|unique:outlets|min:3',
        ]);
    }
}
