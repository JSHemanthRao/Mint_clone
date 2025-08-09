<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['account_id','description','amount','date','category_id'];

    public function account(){
        return $this->belongsTo(Account::class);
    }
    public function category(){
        return $this->belongsTo(Category::class);
    }
}
