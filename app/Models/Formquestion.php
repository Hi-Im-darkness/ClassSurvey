<?php

/**
 * Created by Reliese Model.
 * Date: Sat, 29 Dec 2018 17:51:22 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Formquestion
 * 
 * @property int $id
 * @property int $form_id
 * @property int $question_id
 * 
 * @property \App\Models\Form $form
 * @property \App\Models\Question $question
 *
 * @package App\Models
 */
class Formquestion extends Eloquent
{
	protected $table = 'formquestion';
	public $timestamps = false;

	protected $casts = [
		'form_id' => 'int',
		'question_id' => 'int'
	];

	protected $fillable = [
		'form_id',
		'question_id'
	];

	public function form()
	{
		return $this->belongsTo(\App\Models\Form::class);
	}

	public function question()
	{
		return $this->belongsTo(\App\Models\Question::class);
	}
}
