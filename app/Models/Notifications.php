<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifications extends Model
{
	protected $guarded = array();
  const UPDATED_AT = null;

	public function user()
	{
		return $this->belongsTo('App\Models\User')->first();
	}

	public static function send($destination, $session_id, $type, $target, $title)
	{
		$noty = new Notifications;

		$noty->destination = $destination;
		$noty->author      = $session_id;
		$noty->type        = $type;
		$noty->target      = $target;
		$noty->title      = $title;
		$noty->save();
	}

}
