<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    public function Users()
    {
        return $this->belongsTo(UserTransaction::class, 'parentEmail', 'email');
    }
}
