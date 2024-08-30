<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
   protected $guarded = [];
   protected $table = 'tbl_modules';
   public $timestamps = false;
}