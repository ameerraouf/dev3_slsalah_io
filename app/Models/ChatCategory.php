<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatCategory extends Model
{
    use HasFactory;
    protected $table = 'chat_category';
    protected $guarded = [];
}
