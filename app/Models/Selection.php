<?php

namespace App\Models;

use Framework\Model;

class Selection extends Model
{
    public function getCategories($locale)
    {
        return db()->query('
            SELECT category_id, name 
            FROM category_translations 
            WHERE locale = ?
        ', [$locale])->get();
    }
}
