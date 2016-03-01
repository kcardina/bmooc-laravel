<?php

namespace App\Helpers;

use URL;

class Version{

    /*
     * Return the hash stored in the .revision file in the server root.
     * This assumes the project is using PHPloy for deployment.
    */
    static function get(){
        $v = 'default';
        $file = base_path() . '/../.revision';
        if(file_exists($file)){
            $v = file_get_contents($file);
        }
        return $v;
    }

}
