<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    /**
     * Lista de atributos que pueden ser asignados masivamente
     *
     * @var array $fillable
     */
    protected $fillable = [
        'user_id',
        'annuled',
        'goa',
        'service',
        'enterprise',
        'date_Time',
        'timeservices',
        'base',
        'destination_lat',
        'destination_long',
        'name',
        'phone',
        'email',
        'file',
        'flair',
        'destination',
        'essence'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
