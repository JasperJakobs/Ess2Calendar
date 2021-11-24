<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Softbrick extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function parent() {
        return $this->belongsTo('App\Models\User', 'user');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'user',
        'email',
        'password',
    ];
}
