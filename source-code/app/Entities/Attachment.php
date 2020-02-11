<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attachment extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    protected $fillable = ['filename', 'type', 'foreign_id', 'path', 'deleted_at', 'user_id'];

}
