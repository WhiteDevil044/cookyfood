<?php


namespace App\Controllers;

use Framework\Controller;

class BaseController extends Controller
{
    public function renderMenu(): string
    {
        return view()->renderPartial('incs/menu');
    }
}
