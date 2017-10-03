<?php namespace Sukohi\Evaluation;

use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    protected $fillable = ['parent', 'parent_id', 'type_id', 'user_id'];
}