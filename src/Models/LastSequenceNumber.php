<?php

namespace WahyuDwiKrisnanto\SequenceNumberGenerator\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LastSequenceNumber extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $primaryKey = 'type';
}
