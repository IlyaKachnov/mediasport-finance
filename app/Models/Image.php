<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Image extends Model
{
   public $timestamps = false;
   protected $fillable = ['name'];
   public static function translitName($fileName, $code = 'utf-8') {
        $fileName = mb_strtolower($fileName, $code);
        $fileName = str_replace([
            '?', '!', '.', ',', ':', ';', '*', '(', ')', '{', '}', '%', '#', '№', '@', '$', '^', '-', '+', '/', '\\', '=', '|', '"', '\'',
            'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'з', 'и', 'й', 'к',
            'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х',
            'ъ', 'ы', 'э', ' ', 'ж', 'ц', 'ч', 'ш', 'щ', 'ь', 'ю', 'я'
                ], [
            '', '', '.', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', /* remove bad chars */
            'a', 'b', 'v', 'g', 'd', 'e', 'e', 'z', 'i', 'y', 'k',
            'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h',
            'j', 'i', 'e', '_', 'zh', 'ts', 'ch', 'sh', 'shch',
            '', 'yu', 'ya'
                ], $fileName);

        return $fileName;
    }
    /*public function setNameAttribute($fileName)
    {
       $this->attributes['name'] = self::translitName($fileName); 
    }*/
}
