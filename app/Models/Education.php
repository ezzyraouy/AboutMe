<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Education extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'educations';
    protected $fillable = ['title', 'lieu', 'datedebut', 'datefin'];
}
