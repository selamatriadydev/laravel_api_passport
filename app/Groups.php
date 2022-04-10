<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Groups extends Model
{
    protected $table = 'par_group';
    protected $fillable = ['name', 'is_published'];
    protected $hidden = [
        'created_at', 'updated_at','is_published'
    ];
    protected $appends = ['published'];

    public function getPublishedAttribute()
    {
        return ($this->is_published) ? 'Yes' : 'No';
    }

    public function menus()
    {
        return $this->belongsToMany(Menus::class)->withTimestamps();
    }

}
