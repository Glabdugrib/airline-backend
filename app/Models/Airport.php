<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * Class Airport
 *
 * @property int            $id
 * @property string         $code
 * @property string         $name
 * @property string         $city
 * @property string         $country
 * @property Carbon         $created_at
 * @property Carbon         $updated_at
 * @property Carbon|null    $deleted_at
 */
class Airport extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['code', 'name', 'city', 'country'];

    /**
     * Define a relationship for flights departing from this airport.
     *
     * @return HasMany
     */
    public function departingFlights(): HasMany
    {
        return $this->hasMany(Flight::class, 'departure_airport_id');
    }

    /**
     * Define a relationship for flights arriving at this airport.
     *
     * @return HasMany
     */
    public function arrivingFlights(): HasMany
    {
        return $this->hasMany(Flight::class, 'arrival_airport_id');
    }
}
