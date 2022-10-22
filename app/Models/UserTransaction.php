<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTransaction extends Model
{
    public function Transactions()
    {
        return $this->hasMany(Transaction::class, 'parentEmail', 'email');
    }
}
