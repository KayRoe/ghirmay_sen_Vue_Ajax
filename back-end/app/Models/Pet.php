<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name','species','sex','age','image_url','owner_id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    // function to define relationship between pet and owner
    public function owner() {
        return $this->belongsTo('App\Models\Owner', 'owner_id', 'id');
    }
}
