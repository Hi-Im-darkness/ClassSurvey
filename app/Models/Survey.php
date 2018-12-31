<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 31 Dec 2018 16:49:01 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Survey
 * 
 * @property int $id
 * @property string $name
 * @property int $course_id
 * @property int $form_id
 * 
 * @property \App\Models\Course $course
 * @property \App\Models\Form $form
 * @property \Illuminate\Database\Eloquent\Collection $dosurveys
 *
 * @package App\Models
 */
class Survey extends Eloquent
{
	protected $table = 'survey';
	public $timestamps = false;

	protected $casts = [
		'course_id' => 'int',
		'form_id' => 'int'
	];

	protected $fillable = [
		'name',
		'course_id',
		'form_id'
	];

	public function course()
	{
		return $this->belongsTo(\App\Models\Course::class);
	}

	public function form()
	{
		return $this->belongsTo(\App\Models\Form::class);
	}

	public function dosurveys()
	{
		return $this->hasMany(\App\Models\Dosurvey::class);
	}
}
