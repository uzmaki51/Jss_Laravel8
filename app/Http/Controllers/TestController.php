<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function gitPull() {
        $output = null; 
        $retval = null;
        exec('cd ' . base_path(), $output, $retval);
        exec('git pull', $output, $retval);
        print_r('Status: ' . $retval . "<br>");
        foreach($output as $key => $item)
            print_r($item . "<br>");
    }
}
