<?php

//namespace Michielvaneerd\CountryInfo;
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Timezone extends Model
{
    protected $table = 'mve_timezones';

    protected $fillable = [
        'country_id',
        'name'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
