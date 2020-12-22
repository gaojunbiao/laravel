<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Index extends Model
{
    
    protected $table = 'user';

    protected $primarykey = 'id';

    public $timestamps = false;

    protected $fillable = ['id','name','password','email'];

}
