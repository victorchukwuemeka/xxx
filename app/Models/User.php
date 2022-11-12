<?php 

namespace   Framework\Database;

use Framework\database\Model;

class User extends Model 
{
    protected string $table = 'users';

    public function profile(): mixed 
    {
        return $this->hasOne(Profile::class, 'user_id');
    }

    public function order(): mixed 
    {
        return $this->hasMany(Order::class, 'user_id');
    }

}