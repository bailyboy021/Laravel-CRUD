<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Cats;
use App\Models\Encrypt;
use DataTables;

class CatsController extends Controller
{
    public function getCats(Request $request)
    {
        $encrypt = new Encrypt;

        $gender = $encrypt->encrypt_decrypt($request->gender, 'decrypt');

        if($request->ajax()) {				
			$model = Cats::orderBy('name', 'asc');
			
			if($request->gender)
			{
				$model->where('gender', $gender);
			}

			$model = $model->get();
		
            return Datatables::of($model)
			->editColumn('id', function ($model) use ($encrypt){
				return $encrypt->encrypt_decrypt($model->id, 'encrypt');
            })
			->editColumn('gender', function($model)
			{
				if($model->gender == 1){
					return 'male';
				}else{
					return 'female';
				}
			})
			
			->addIndexColumn()
			->addColumn('action', function($row){

			})
			->rawColumns(['action'])
			->make(true);
        }
    }

    public function addCats()
    {
        $model = new Cats();

        return view('addCats', compact('model'));
    }

    public function storeCats(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required|string|max:255",
            "breed" => "required|string|max:255",
            "gender" => "required",
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 400);
        }

        $data = Cats::addCat($validator->validated());
        return response()->json($data, 201);
    }

    public function viewCats(Request $request)
    {
		$encrypt = new Encrypt;
		$idCat = $encrypt->encrypt_decrypt($request->idCat, 'decrypt');
		
        $model = Cats::where('id',$idCat)->first();         

        $data['model'] = $model;
        $data['idCat'] = $request->idCat;
        $result = array(
            'body' =>  view('addCats', $data)->render()
            ,'title' => "Edit Cats - ".$model->name
        );

        echo json_encode($result);
    }

    public function updateCats(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "catId" => "required|string",
            "name" => "required|string|max:255",
            "breed" => "required|string|max:255",
            "gender" => "required",
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 400);
        }

        $result = Cats::updateCat($validator->validated());

        if (!$result['success']) {
            return response()->json([
                'error' => $result['message'],
            ], $result['status']);
        }

        return response()->json([
            'message' => $result['message'],
            'data' => $result['data'],
        ], 200);
    }

    public function deleteCats(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:cats,id,deleted_at,NULL',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 400);
        }

        $result = Cats::deleteCat($request->id);

        return response()->json([
            'message' => $result['message'],
        ], 200);
    }
}
