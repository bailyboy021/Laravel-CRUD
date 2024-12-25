<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Models\Encrypt;
use Illuminate\Support\Facades\DB;

class Cats extends Model
{
    use SoftDeletes;

    protected $table = "cats";
    protected $primarykey = "id";

    protected $guarded = [
        'id'
    ];

    public static function addCat(array $params = [])
    {
        try {
            return DB::transaction(function () use ($params) {
                $encrypt = new Encrypt;
                $gender = $encrypt->encrypt_decrypt($params['gender'], 'decrypt');

                $data = array(
                    'name' 	    => $params['name'],
                    'breed' 	=> $params['breed'],
                    'gender'    => $gender
                );
                $cats = self::create($data);

                if (!$cats) {
                    abort(400, 'Failed to add cats');
                }
            
                return $cats;
            });
        } catch (\Exception $e) {
            Log::error('Failed to add cats: ' . $e->getMessage());    
            return false;
        }
    }

    public static function updateCat($params)
    {
        try {
            $encrypt = new Encrypt;
            $id = $encrypt->encrypt_decrypt($params['catId'], 'decrypt'); // Decrypt ID

            $cat = self::find($params['catId']);

            if (!$cat) {
                return [
                    'success' => false,
                    'message' => 'Cat not found',
                    'status' => 400,
                ];
            }

            $cat->update([
                'name' => $params['name'],
                'breed' => $params['breed'],
                'gender' => $encrypt->encrypt_decrypt($params['gender'], 'decrypt'), // Decrypt gender
            ]);

            return [
                'success' => true,
                'message' => 'Cat successfully updated',
                'data' => $cat,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to update cat: ' . $e->getMessage(),
                'status' => 500,
            ];
        }
    }

    public static function deleteCat($idCat)
    {
        try {
            $cat = self::find($idCat);

            if (!$cat) {
                return [
                    'success' => false,
                    'message' => 'Cat not found',
                    'status' => 404,
                ];
            }

            $cat->delete();

            return [
                'success' => true,
                'message' => 'Cat successfully deleted',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to delete cat: ' . $e->getMessage(),
                'status' => 500,
            ];
        }
    }
}
