<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NavigateUser extends Model
{
    use HasFactory;
    protected $table = 'navigate_users';
    protected $fillable = ['name'];
} 