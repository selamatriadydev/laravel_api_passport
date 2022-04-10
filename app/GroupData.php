<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupData extends Model
{
    protected $table = 'group_data';
    protected $fillable = ['group_id', 'menu_id'];
    protected $hidden = [
        'created_at', 'updated_at'
    ];
}
