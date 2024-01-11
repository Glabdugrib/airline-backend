<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * Class Flight
 *
 * @property int $id
 * @property int $departure_airport_id
 * @property int $arrival_airport_id
 * @property Carbon $departure_at
 * @property Carbon $arrival_at
 * @property float $price
 * @property int $stopovers
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Airport $departureAirport
 * @property-read Airport $arrivalAirport
 */
class Flight extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['departure_airport_id', 'arrival_airport_id', 'departure_at', 'arrival_at', 'price', 'stopovers'];

    /**
     * Define a relationship for the departure airport of the flight.
     */
    public function departureAirport(): BelongsTo
    {
        return $this->belongsTo(Airport::class, 'departure_airport_id');
    }

    /**
     * Define a relationship for the arrival airport of the flight.
     */
    public function arrivalAirport(): BelongsTo
    {
        return $this->belongsTo(Airport::class, 'arrival_airport_id');
    }
}
