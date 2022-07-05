<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Baucua extends Model
{
    use HasFactory;

    public $table = 'baucua';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'image',
        'position',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'position' => 'int',
        'name' => 'string',
        'image' => 'string',
    ];

    public function bet() {
        return $this->hasMany(Bet::class, 'baucua_id');
    }
}
