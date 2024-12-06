<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        $this->validate($request, [            
            'name' 	=> 'required',
            'breed' => 'required',
            'gender'=> 'required'
        ]);
		
		$encrypt = new Encrypt;
        $gender = $encrypt->encrypt_decrypt($request->gender, 'decrypt');
		
		$data = array(
            'name' 	    => $request->name,
            'breed' 	=> $request->breed,
            'gender'    => $gender
        );
		$model = Cats::create($data);
		return json_encode($data);
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
        $encrypt = new Encrypt;
		$catId = $request->catId;
        $gender = $encrypt->encrypt_decrypt($request->gender, 'decrypt');

		$this->validate($request, [            
            'name' 	=> 'required',
            'breed' => 'required',
            'gender'=> 'required'
        ]);
		
        $data['name'] = $request->name;
		$data['breed'] = $request->breed;
		$data['gender'] = $gender;

        Cats::where('id', $catId)->update($data);
    }

    public function deleteCats(Request $request)
    {
        $id = $request->id;        
        $data = Cats::find($id);		
		$data->delete();	
    }
}
