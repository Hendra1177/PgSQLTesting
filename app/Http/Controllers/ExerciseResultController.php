<?php

namespace App\Http\Controllers;

use App\Models\latihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;

class ExerciseResultController extends Controller
{


    public function exerciseResultByClass(Request $request, $id)
    {
        $exercise = latihan::all();
        $class_id = $request->kelas_id;
        // $latihan_id = $request->latihan_id;

        // $nilai = db::table('kelas_mahasiswa')
        // ->join ('mahasiswa', 'mahasiswa.id', 'kelas_mahasiswa.mahasiswa_id')
        // ->join ('kelas', 'kelas.id', 'kelas_mahasiswa.kelas_id')
        // ->join ('users', 'users.id', 'mahasiswa.user_id')
        // ->join ('submission', 'submission.mahasiswa_id', 'mahasiswa.id')
        // ->join ('soal', 'soal.id', 'submission.soal_id')
        // ->join ('soal_latihan', 'soal_latihan.soal_id', 'soal.id')
        // ->join ('latihan', 'latihan.id', 'soal_latihan.latihan_id')
        // ->groupBy('users.name', 'submission.mahasiswa_id', 'submission.status', 'kelas.nama')
        // ->select('users.name', 'submission.mahasiswa_id', 'submission.status', 'kelas.nama')
        // ->where('submission.status', '=', 'passed')
        // ->get();

        // $passed = DB::table('soal_latihan')
        //     ->join('submission', 'soal_latihan.soal_id', 'submission.soal_id')
        //     ->join('soal', 'soal_latihan.soal_id', 'soal.id')
        //     ->join('latihan','latihan.id', 'soal_latihan.latihan_id')
        //     // ->join ('mahasiswa', 'mahasiswa.id', 'submission.mahasiswa_id')
        //     // ->join ('users', 'users.id', 'mahasiswa.user_id')
        //     ->where('submission.status','passed')
        //     ->where('soal_latihan.latihan_id', $id)
        //     // ->where('submission.mahasiswa_id', $id)
        //     ->get()->count();
        // $question = DB::table('soal_latihan')
        // // ->join ('latihan', 'latihan.id', 'soal_latihan.latihan_id')
        // ->where('latihan_id', $id)
        // ->get()->count();
        // $result = floor(($passed / $question) * 100);
        // dd($result);
        // exit;
        return view('Teacher.exerciseResult.exerciseResultByClass', compact('exercise', 'class_id'));
    }

    public function getExerciseIDForDataTable(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'exercise_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {
            return response()->json(['code' => 1, 'msg' => $request->exercise_id]);
        }
    }

    public function exerciseResultByExerciseDataTable(Request $request)
    {
        $class_id = $request->exercise_id;


        $student = DB::table('kelas_mahasiswa')
            ->join('kelas', 'kelas_mahasiswa.kelas_id', 'kelas.id')
            ->join('users', 'kelas_mahasiswa.id', 'users.id')
            ->where('kelas_mahasiswa.kelas_id', $class_id)
            ->select('users.id', 'users.name as username', 'kelas.nama as classname')
            ->get();

        return DataTables::of($student)
            ->addColumn('passed', function ($row, Request $request) {
                $passed = DB::table('soal_latihan')
                    ->join('submission', 'soal_latihan.soal_id', 'submission.soal_id')
                    ->where('soal_latihan.latihan_id', $request->exercise_id)
                    ->where('submission.mahasiswa_id', $row->id)
                    ->where('submission.status', 'passed')
                    ->select('submission.id')
                    ->count();
                return $passed;
                //return floor(($passed / $jumlah_soal) * 100);
            })
            ->addColumn('jumlah_soal', function ($row, Request $request) {
                $jumlah_soal = DB::table('soal_latihan')
                    ->where('soal_latihan.latihan_id', $request->exercise_id)
                    ->select('soal_latihan.id')
                    ->count();
                return $jumlah_soal;
            })
            ->addColumn('result', function ($row, Request $request) {
                
                $passed = DB::table('soal_latihan')
                    ->join('submission', 'soal_latihan.soal_id', 'submission.soal_id')
                    ->join('soal', 'soal_latihan.soal_id', 'soal.id')
                    ->where('submission.status', 'passed')
                    ->where('soal_latihan.latihan_id', $request->exercise_id)
                    ->where('submission.mahasiswa_id', $row->id)
                    ->select('submission.id')
                    ->count();

                    $jumlah_soal = DB::table('soal_latihan')
                    ->where('soal_latihan.latihan_id', $request->exercise_id)
                    ->select('soal_latihan.id')
                    ->count();
                return floor(($passed / $jumlah_soal) * 100);

                // $question = DB::table('soal_latihan')->where('latihan_id', $latihan_id)->get()->count();
                // if($passed == 0){
                //     $sum = 0;
                // }else{
                //     $sum = ($passed / $question) * 100;
                // }
                // return floor($sum);
            })
            ->make(true);
    }
}
