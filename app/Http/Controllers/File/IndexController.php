<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-04-10
 * Time: 15:40
 */
namespace App\Http\Controllers\File;

use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function index()
    {
        return view('file.index');
    }
}
