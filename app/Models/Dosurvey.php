<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 31 Dec 2018 16:49:02 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Dosurvey
 * 
 * @property int $id
 * @property int $student_id
 * @property int $survey_id
 * @property int $question_id
 * @property int $answer
 * 
 * @property \App\Models\Student $student
 * @property \App\Models\Survey $survey
 * @property \App\Models\Question $question
 *
 * @package App\Models
 */
class Dosurvey extends Eloquent
{
	protected $table = 'dosurvey';
	public $timestamps = false;

	protected $casts = [
		'student_id' => 'int',
		'survey_id' => 'int',
		'question_id' => 'int',
		'answer' => 'int'
	];

	protected $fillable = [
		'student_id',
		'survey_id',
		'question_id',
		'answer'
	];

	public function student()
	{
		return $this->belongsTo(\App\Models\Student::class);
	}

	public function survey()
	{
		return $this->belongsTo(\App\Models\Survey::class);
	}

	public function question()
	{
		return $this->belongsTo(\App\Models\Question::class);
	}
}
