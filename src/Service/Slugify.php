<?php

namespace App\Service;

class Slugify
{
    public function generate(string $input):string
    {
       $translit = array('Á'=>'A','À'=>'A','Â'=>'A','Ä'=>'A','Ã'=>'A','Å'=>'A','Ç'=>'C','É'=>'E','È'=>'E','Ê'=>'E',
                            'Ë'=>'E','Í'=>'I','Ï'=>'I','Î'=>'I','Ì'=>'I','Ñ'=>'N','Ó'=>'O','Ò'=>'O','Ô'=>'O','Ö'=>'O',
                            'Õ'=>'O','Ú'=>'U','Ù'=>'U','Û'=>'U','Ü'=>'U','Ý'=>'Y','á'=>'a','à'=>'a','â'=>'a','ä'=>'a',
                            'ã'=>'a','å'=>'a','ç'=>'c','é'=>'e','è'=>'e','ê'=>'e','ë'=>'e','í'=>'i','ì'=>'i','î'=>'i',
                            'ï'=>'i','ñ'=>'n','ó'=>'o','ò'=>'o','ô'=>'o','ö'=>'o','õ'=>'o','ú'=>'u','ù'=>'u','û'=>'u',
                            'ü'=>'u','ý'=>'y','ÿ'=>'y');


        $slug = strtr($input, $translit);

        /*$slug = mb_strtolower(preg_replace( '/[^a-zA-Z0-9\-\s]/', '', $slug ));
        $slug = str_replace(' ','-',trim($slug));
        $slug = preg_replace('/([-])\\1+/', '$1', $slug);*/

        $input = str_replace(array_keys($translit), array_values($translit), $input);
        $input = preg_replace('/[^A-Za-z0-9-]/', '', $input);
        $input = preg_replace('/-+/', '-', $input);
        $input = trim($input, '-');

        return $slug;
    }
}