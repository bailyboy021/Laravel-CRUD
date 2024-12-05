<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Cats extends Model
{
    use SoftDeletes;

    protected $table = "cats";
    protected $primarykey = "id";

    protected $guarded = [
        'id'
    ];
}
