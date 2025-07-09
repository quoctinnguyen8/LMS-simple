<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Equipment
 * 
 * @property int $id
 * @property string $name
 * @property float|null $price
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Room[] $rooms
 *
 * @package App\Models
 */
class Equipment extends Model
{
	protected $table = 'equipments';

	protected $casts = [
		'price' => 'float'
	];

	protected $fillable = [
		'name',
		'price'
	];

	public function rooms()
	{
		return $this->belongsToMany(Room::class, 'room_equipments')
					->withTimestamps();
	}
}
