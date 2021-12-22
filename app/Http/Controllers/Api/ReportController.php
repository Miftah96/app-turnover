<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Merchant;
use App\Outlet;
use App\User;
use App\Transaction;
use DB;
use Auth;
use App\Helpers\ResponseHelper as ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;
use App\Http\Resources\TransactionCollection;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserCollection;
use App\Http\Resources\OutletResource;
use App\Http\Resources\OutletCollection;
use App\Http\Resources\MerchantResource;
use App\Http\Resources\MerchantCollection;

class ReportController extends Controller
{
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
}
