<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    use HasFactory;

    static function getOption($option_name){
        $data = Settings::where('name',$option_name)->first();
        if(empty($data)){
            Settings::insert(['name' => $option_name,'value' => $option_name]);
            $data = Settings::where('name',$option_name)->first();
        }
        return $data->value;
    }
    
}
