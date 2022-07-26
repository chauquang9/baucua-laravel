<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bet extends Model
{
    use HasFactory;

    public $table = 'bet';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'baucua_id',
        'game_id',
        'user_id',
        'money_bet',
        'x',
        'y',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'baucua_id'  => 'int',
        'game_id'    => 'int',
        'user_id'    => 'int',
        'money_bet'  => 'float',
        'x'          => 'string',
        'y'          => 'string',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * @return string
     */
    public function getCreatedAtAttribute()
    {
        if (!empty($this->attributes['created_at'])) {
            return Carbon::createFromFormat('Y-m-d H:i:s', $this->attributes['created_at'])->timezone('Asia/Bangkok')->format('Y-m-d H:i:s');
        }

        return '';
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function baucua()
    {
        return $this->belongsTo(Baucua::class, 'baucua_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
