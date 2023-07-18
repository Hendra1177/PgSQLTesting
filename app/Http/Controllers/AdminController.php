<?php

namespace App\Http\Controllers;

use App\Models\tahun_akademik;
use App\Models\kelas;
use App\Models\dosen;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $tahun_ajaran = tahun_akademik::where('status', '=', 'Aktif')->get();
        return view('admin.dashboard', compact('tahun_ajaran'));
    }

    public function class()
    {
        $classes = kelas::with('teacher', 'year')->paginate(3);
        // $classes = db::table('kelas')
        // ->join('dosen', 'dosen.id', 'kelas.dosen_id')
        // ->join ('users', 'users.id', 'dosen.user_id')
        // ->join ('tahun_akademik', 'tahun_akademik.id', 'kelas.tahun_akademik_id')
        // ->get();
        // dd ($classes);
        $teacher = dosen::all();
        $year = tahun_akademik::where('status', 'Aktif')->get();
        return view('admin.class.index', compact('classes', 'teacher', 'year'));
    }

    public function academic_year()
    {
        $tahun_ajaran = tahun_akademik::where('status', '=', 'Aktif')->get();
        return view('admin.academic_year.index', compact('tahun_ajaran'));
    }

    public function teacher()
    {
        $teacher = dosen::with('user')->paginate(3);
        return view('admin.teacher.index', compact('teacher'));
    }

    public function student()
    {
        $year = tahun_akademik::where('status', 'Aktif')->get();
        $kelas = kelas::all();
        return view('admin.student.index', compact('year', 'kelas'));
    }
}
