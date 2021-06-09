<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Token extends Model {
    use HasFactory;
    protected $table = 'token';
    protected $fillable = [
        'token'
    ];
}
