<?php

namespace App\Http\Controllers;

use App\Models\soal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    private $dbname;
    public function connectDatabase ($dbname){
        if (!pg_connect("host = localhost port = 5432 dbname=$dbname user=postgres password=hendra11")) {
            throw new \Exception('SYSTEM_ERROR: Cant connect to database!');
        }
        return pg_connect("host = localhost port = 5432 dbname=$dbname user=postgres password=hendra11");
        
}
public function testing($id)
{
    $test = soal::find($id)->get();
    echo $test;

    return view('Student.Soal.index',compact('test'));
}
}

?>