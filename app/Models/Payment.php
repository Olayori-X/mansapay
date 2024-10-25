<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Payment extends Model
{
    use HasFactory, Notifiable;
    protected $table = 'paymentmade';

    protected $fillable = [
        'userid',
        'formid',
        'payer_name',
        'payer_email',
        'reference',
    ];
}
