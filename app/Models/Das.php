<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Traits\Resizable;


class Das extends Model
{
use HasFactory,Resizable;
protected $table =  "das";

}