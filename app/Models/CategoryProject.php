<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryProject extends Model
{
    protected $table = 'category_project';

    protected $fillable = ['project_id', 'category_id', 'created_at', 'updated_at'];

    public $timestamps = true;
}
