<?php

namespace App\Models\User;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    // 基本設定のみをここに記述
    protected $fillable = [
        'name',
        'email',
        'password'
    ];

    // 他のファイルの機能を取り込む
    use Scope;
    use Relation;
}
