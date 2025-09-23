<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = ['user_id', 'name', 'type', 'balance'];

    public function user()
    {
        // Corrected this to point to the User model
        return $this->belongsTo(User::class, 'user_id');
    }

    public function transactions()
    {
        // Corrected this to point to the Transaction model
        return $this->hasMany(Transaction::class);
    }

    public function getBalanceAttribute()
    {
        $income = $this->transactions()
            ->where('type', 'income')
            ->sum('amount');

        $expense = $this->transactions()
            ->where('type', 'expense')
            ->sum('amount');

        return $income - $expense;
    }
}
