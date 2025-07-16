<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class NewsCategory
 * 
 * @property int $id
 * @property string|null $name
 * @property string|null $slug
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|News[] $news
 *
 * @package App\Models
 */
class NewsCategory extends Model
{
	protected $table = 'news_categories';

	protected $fillable = [
		'name',
		'slug',
		'description'
	];

	public function news()
	{
		return $this->hasMany(News::class, 'category_id');
	}
}
