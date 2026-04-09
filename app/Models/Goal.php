<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'name', 'current_amount', 'target_amount', 'due_date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}