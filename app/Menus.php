<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menus extends Model
{
    protected $table = 'menus';
    protected $fillable = ['name','link', 'route','parrent','sort', 'is_published'];
    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function getPublishedAttribute()
    {
        return ($this->is_published) ? 'Yes' : 'No';
    }
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
    public function scopeParrent($query)
    {
        return $query->where('parrent', 0);
    }
    
    public function group()
    {
        return $this->belongsToMany(Groups::class)->withTimestamps();
    }

    public function sub(){
        return $this->hasMany(Menus::class, 'parrent', 'id');
    }

    public function sub_publish(){
        return $this->sub()->where(['is_published' => true]);
    }

}
