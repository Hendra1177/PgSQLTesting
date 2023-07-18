<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\tahun_akademik;
use App\Models\kelas;
use App\Models\latihan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TeacherController extends Controller
{
    public function dashboard()
    {
        return view('Teacher.dashboard');
    }
    public function class()
    {
        
        $class = kelas::with('teacher.user')->where('dosen_id', 1)->paginate(2);
        // dd($class);
        return view('Teacher.class.index', compact('class'));
    }
    public function getClassStudent(Request $request)
    {
        $class_id = $request->class_id;
        
    }
    public function getClass()
    {
        $class = kelas::where('dosen_id', 1)->get();
        return response()->json(['code' => 1, 'details' => $class]);
    }
    public function question()
    {
        return view('Teacher.question.index');
    }
    public function exercise()
    {
        $exercise = latihan::with('year')->paginate(3);
        $year = tahun_akademik::where('status', 'Aktif')->get();
        return view('Teacher.exercise.index', compact('exercise', 'year'));
    }
    public function exerciseResult(Request $request)
    {
        // $class = Classes::where('teacher_id', Auth::user()->id)->get();
        $kelas = DB::table('dosen')
            ->join('users', 'dosen.user_id', 'users.id')
            ->join('kelas', 'dosen.id', 'kelas.dosen_id')
            ->where('users.id', Auth::user()->id)
            ->select('kelas.id', 'kelas.nama')
            ->get();
        return view('Teacher.exerciseResult.index', compact('kelas'));
    }
}


