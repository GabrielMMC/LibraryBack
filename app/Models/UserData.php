<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserData extends Model
{
    use HasFactory;
    // protected $keyType = 'string';
    protected $table = 'user_data';

    protected $fillable = [
        'user_id',
        'document',
        'phone',
        'zip_code',
        'file',
    ];
}
