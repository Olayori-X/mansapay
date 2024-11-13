<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Funds extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'transactions';
    protected $fillable = [
        'userid',
        'amount',
        'reference',
        'status',
        'addition'
    ];
}
