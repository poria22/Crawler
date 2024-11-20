<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Crawler extends Model
{
    protected $fillable = ['url', 'title', 'description', 'content', 'create_date'];
}
