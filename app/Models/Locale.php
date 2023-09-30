<?php

//namespace Michielvaneerd\CountryInfo;
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Locale extends Model
{
    protected $table = 'mve_locales';

    protected $fillable = [
        'country_id',
        'code',
        'title'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
