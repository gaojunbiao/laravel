<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Model;

class Login extends Model
{
    protected $table = 'user';

    protected $primarykey = 'id';

    public $timestamps = false;

    //protected $fillable = [''];

    protected $guarded = [];
}
