<?php

namespace Database\Factories\Helpers;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class FactoryHelper
{
    /**
     * this function gets a random model id from the database
     * @param string 
     */
    public static function getRandomModelId(string $model)
    {
        $count = $model::query()->count();

        if ($count === 0) {
            return $model::factory()->create()->id;
        } else {
            return rand(1, $count);
        }
    }
}
