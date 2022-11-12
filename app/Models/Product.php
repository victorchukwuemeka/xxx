<?php 

namespace App\Models;

use Framework\Database\Model;

class Product extends Model 
{
   protected string $table = 'products';

   public function setDescriptionAttribute(string $value)
   {  
      $limit = 50;
      $cont = "...";

      if (mb_strwidth($value, 'UTF-8') <= $limit) {
         return $value;
      }
      return rtrim(mb_strimwidth($value,0,$limit, '', "UTF-8")).$cont;
   }
}