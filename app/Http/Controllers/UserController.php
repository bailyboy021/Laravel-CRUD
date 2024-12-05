<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Encrypt;
use DataTables;

class UserController extends Controller
{
    public function getUsers(Request $request)
    {
        $encrypt = new Encrypt;

        $status = $encrypt->encrypt_decrypt($request->status, 'decrypt');

        if($request->ajax()) {				
			$model = User::orderBy('name', 'asc');
			
			if($request->status)
			{
				$model->where('status', $status);
			}

			$model = $model->get();
		
            return Datatables::of($model)
			->editColumn('id', function ($model) use ($encrypt){
				return $encrypt->encrypt_decrypt($model->id, 'encrypt');
            })
			->editColumn('status', function($model)
			{
				if($model->status == 0){
					return 'lalala';
				}else{
					return 'kakaka';
				}
			})
			
			->addIndexColumn()
			->addColumn('action', function($row){

			})
			->rawColumns(['action'])
			->make(true);
        }
    }
}
