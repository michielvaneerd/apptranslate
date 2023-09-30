<?php

//namespace Michielvaneerd\CountryInfo;
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table = 'mve_countries';

    protected $fillable = [
        'code',
        'title'
    ];

    public function locales()
    {
        return $this->hasMany(Locale::class);
    }

    public function timezones()
    {
        return $this->hasMany(Timezone::class);
    }
}
