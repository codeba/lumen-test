<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Unit;

class Team extends Model
{
    protected $fillable = [
        'name','is_active'
    ];
    public $timestamps = true;
    
    public function user() {
        return $this->hasMany('App\TeamUser', 'team_id')->leftJoin('users', 'user_id', 'users.id');
    }
}
