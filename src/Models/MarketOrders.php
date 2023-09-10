<?php

namespace Helious\SeatBusaHr\Models;

use Illuminate\Database\Eloquent\Model;

class HrNote extends Model
{

    protected $table = 'seat_busa_hr_notes';

    protected $fillable = [
        'director_only',
        'note',
        'created_by',
        'note_for',
    ];

    protected $casts = [
        'director_only' => 'boolean',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function creator()
    {
        return $this->hasOne('Seat\Web\Models\User', 'id', 'created_by');
    }

    public function noteFor()
    {
        return $this->hasOne('Seat\Web\Models\User', 'main_character_id', 'note_for');
    }
}