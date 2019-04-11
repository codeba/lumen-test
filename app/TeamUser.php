<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Unit;

class TeamUser extends Model
{
    protected $fillable = [
        'user_id','team_id'
    ];
    public $timestamps = true;
}
