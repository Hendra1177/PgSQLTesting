<?php

namespace App\Http\Controllers;

use App\Models\latihan;
use App\Models\soal_latihan;
use App\Models\soal;
use Illuminate\Http\Request;
use App\Models\submission;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class StudentController extends Controller
{
    public function dashboard()
    {
        return view('Student.dashboard');
    }

    public function latihan()
    {
        $latihan = latihan::get();
        return view('Student.exercise.index', compact('latihan'));
    }

    public function soalLatihan(Request $request, $id)
    {
        // $latihan_id = $request->latihan_id;
        $soal_latihan = DB::table('soal_latihan')
            ->join('latihan', 'latihan.id', '=', 'soal_latihan.latihan_id')
            ->join('soal', 'soal.id', '=', 'soal_latihan.soal_id')
            // ->where('soal.id', '=', $id)
            ->where('soal_latihan.latihan_id', $request->latihan_id)
            // ->where('soal_latihan.id', $request->soal_id)
            ->select('soal.id', 'soal.no', 'soal.judul', 'soal.jenis', 'soal.deskripsi', 'soal_latihan.latihan_id', 'soal_latihan.id')
            ->get();
        // dd($soal_latihan);
        return view('Student.SoalLatihan.index', compact('soal_latihan'));
    }
    public function soal($id, Request $request)
    {

        // $conn = pg_connect("host = localhost port = 5432 dbname=pgtest user=postgres  password=hendra11");

        // $code = "INSERT INTO MAHASISWA VALUES ('hendra', '123','123'asdfad); ";

        // $query = "BEGIN; ";
        // $query .= $code;
        // $query .= " ROLLBACK;";

        // try {
        //     pg_query($conn, $query);
        //     return response()->json(['result' => 'sukses']);
        // } catch (\Exception $e) {
        //     dd($e->getMessage());
        // }
        // $code = "SELECT * FROM MAHASISWA";
        // $check = "SELECT";

        // dd(str_contains($code, $check));






        $id_soal = soal_latihan::find($id);
        $soal = DB::table('soal_latihan')
            ->join('latihan', 'latihan.id', '=', 'soal_latihan.latihan_id')
            ->join('soal', 'soal.id', '=', 'soal_latihan.soal_id')
            ->where('soal_latihan.latihan_id', $request->latihan_id)
            ->where('soal_latihan.id', $request->soal_id)
            ->select('soal.id', 'soal.soal', 'soal_latihan.no', 'soal.judul', 'soal_latihan.latihan_id')
            ->get();
        $jumlah_soal = soal_latihan::where('latihan_id', '=', $id)->get()->count();
        return view('Student.Soal.index', compact('soal', 'id_soal', 'jumlah_soal'));
        // dd($soal);
    }
    public function hasil()
    {
        $latihan = latihan::get();
        return view('Student.result.index', compact('latihan'));
    }

    public function hasilLatihan(Request $request)
    {
        $latihan_id = $request->latihan_id;
        $passed = DB::table('soal_latihan')
            ->join('submission', 'soal_latihan.soal_id', 'submission.soal_id')
            ->join('soal', 'soal_latihan.soal_id', 'soal.id')
            ->where('submission.status','passed')
            ->where('soal_latihan.latihan_id', $request->latihan_id)
            ->where('submission.mahasiswa_id', Auth::user()->id)->get()->count();

        $question = DB::table('soal_latihan')->where('latihan_id', $latihan_id)->get()->count();
        $result = floor(($passed / $question) * 100);
        return view('Student.result.resultByExercise', compact('latihan_id', 'passed', 'question', 'result'));
    }

    public function getResultByExerciseDataTable(Request $request)
    {
        $nilai = DB::table('soal_latihan')
            ->join('submission', 'soal_latihan.soal_id', 'submission.soal_id')
            ->join('soal', 'soal_latihan.soal_id', 'soal.id')
            ->where('soal_latihan.latihan_id', $request->latihan_id)
            ->where('submission.mahasiswa_id', Auth::user()->id)
            ->select('submission.id', 'soal_latihan.no', 'soal.judul', 'submission.status', 'submission.created_at', 'submission.updated_at');

        return DataTables::of($nilai)
            ->addColumn('actions', function ($row) {
                return '<div class="btn-group" role="group">            
                <button id="jawaban" type="button" class="btn btn-primary btn-block" data-id=' . $row->id . '></i>Jawaban</button>
                </div>';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function getSubmissionResultDetail(Request $request)
    {
        $nilai = submission::with('soal')->where('id', $request->submission_id)->get();
        return response()
            ->json(['details' => $nilai]);
    }
}
