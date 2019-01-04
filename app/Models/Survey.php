<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 04 Jan 2019 02:31:21 +0000.
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
 * @property \Carbon\Carbon $create_at
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

	protected $dates = [
		'create_at'
	];

	protected $fillable = [
		'name',
		'course_id',
		'form_id',
		'create_at'
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
