<?php

namespace App\Models;

// use Illuminate\Cache\HasCacheLock;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function budgets(){
        return $this->hasMany(Budget::class);
    }

     public function Transactions(){
        return $this->hasMany(Transaction::class);
    }

}
