<?php

namespace App\Models\User;

trait Relation
{
    public function posts()
    {
        return $this->hasMany(\App\Models\Post::class);
    }

    public function profile()
    {
        return $this->hasOne(\App\Models\Profile::class);
    }
}
