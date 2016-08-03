<?php namespace Sukohi\Evaluation;

use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    protected $fillable = ['model', 'parent_id', 'type_id', 'user_id'];
}