<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class RoomEquipment
 * 
 * @property int $room_id
 * @property int $equipment_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Equipment $equipment
 * @property Room $room
 *
 * @package App\Models
 */
class RoomEquipment extends Model
{
	protected $table = 'room_equipments';
	public $incrementing = false;

	protected $casts = [
		'room_id' => 'int',
		'equipment_id' => 'int'
	];

	public function equipment()
	{
		return $this->belongsTo(Equipment::class);
	}

	public function room()
	{
		return $this->belongsTo(Room::class);
	}
}
