<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberPhoto extends Model
{
    use HasFactory;

    public $timestamps = false;
    public $tables = 'membersphotos';
}
