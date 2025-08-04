<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Position extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function departments()
    {
        return $this->belongsToMany(Department::class, 'department_position');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
