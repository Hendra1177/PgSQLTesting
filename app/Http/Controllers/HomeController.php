<?php

namespace App\Http\Controllers;

use App\Models\soal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class HomeController extends Controller
{
    public function index()
    {
        $soal = DB::table('soal')->get();
        
        return view('student.dashboard', compact('soal'));

    }
    public function test (){

        // $dbconn = pg_connect("host=localhost port=5432 dbname=Pgsqltest user=postgres password=hendra11");
        // if (!$dbconn) {
        //     die("koneksi gagal");
        // }

        // $result = pg_query($dbconn, );
    }
}
