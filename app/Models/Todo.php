<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    protected $fillable = ['user_id', 'activity', 'status', 'progress', 'target', 'frequency', 'coins'];

}

