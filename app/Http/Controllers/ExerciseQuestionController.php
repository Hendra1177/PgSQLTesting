<?php

namespace App\Http\Controllers;

use App\Models\latihan;
use App\Models\soal_latihan;
use App\Models\soal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ExerciseQuestionController extends Controller
{
    public function getExerciseQuestionDataTable()
    {
        $question = soal::all();

        return DataTables::of($question)
            ->addColumn('actions', function ($row) {
                return '<div class="btn-group" role="group">
            <button id="assignQuestionToExerciseBtn" type="button" class="btn btn-info btn-block" data-id="' . $row['id'] . '">
            <i class="fa fa-arrow-left"></i>
            </button> 
            </div>';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function getExerciseQuestionDataTableByExercise(Request $request)
    {
        // $question = Question::all();
        $question = DB::table('soal_latihan')
            ->join('latihan', 'soal_latihan.latihan_id', 'latihan.id')
            ->join('soal', 'soal_latihan.soal_id', 'soal.id')
            ->where('soal_latihan.latihan_id', $request->latihan_id)
            ->where('soal_latihan.isRemoved', '=', 0)
            ->select('soal_latihan.id', 'soal.judul', 'soal.jenis', 'soal_latihan.no', 'soal.id as soal_id')
            ->orderBy('soal_latihan.no')
            ->get();

        return DataTables::of($question)
            ->addColumn('actions', function ($row) {
                return '<div class="btn-group" role="group">
            <button id="removeQuestionFromExerciseBtn" type="button" class="btn btn-danger btn-block" data-id="' . $row->question_id . '">
            <i class="fa fa-arrow-right"></i>
            </button> 
            </div>';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function getExerciseIDForDataTable(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'class_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {
            return response()->json(['code' => 1, 'msg' => $request->class_id]);
        }
    }

    public function getExerciseQuestionList(Request $request)
    {
        // $latihan_id = $request->latihan_id;
        // $exercise = DB::table('soal_latihan')
        //     ->join('latihan', 'soal_latihan.latihan_id', 'latihan.id')
        //     ->join('soal', 'soal_latihan.soal_id', 'soal.id')
        //     ->where('soal_latihan.id','=', $latihan_id)
        //     ->select('soal.judul', 'soal.jenis', 'soal.no', 'soal.deskripsi','soal.soal', 'latihan.deskripsi', 'soal_latihan.id')
        //     ->get();
            // $soal = DB::table('soal')
            // ->select('soal.id', 'soal.soal')
            // ->get();
            // $latihan = DB::table('latihan')
            // ->select('latihan.id', 'latihan.nama')
            // ->get(); 
            // $soalLatihan = soal_latihan::all();
            // return view('student.SoalLatihan.index', compact('exercise'));
        // return DataTables::of($exercise)
        //         ->addColumn('actions', function ($row) {
        //             $route = "soal/" . $row->latihan_id . "/" . $row->no;
        //             return '<div class="btn-group" role="group">
        //         <a href="' . $route . '" class="btn btn-primary btn-block"><i class="fa fa-pen"></i></a> 
        //         </div>';
        //         })
        //     ->rawColumns(['actions'])
        //     ->make(true);
    }

    public function getExerciseQuestionItem(Request $request)
    {
        $soal = DB::table('soal_latihan')
            ->join('soal', 'soal_latihan.soal_id', 'soal.id')
            ->where('soal_latihan.latihan_id', '=', $request->latihan_id)
            ->where('soal_latihan.no', '=', $request->soal_no)
            ->select('soal_latihan.no', 'soal.id', 'soal.judul', 'soal.jenis', 'soal.dbname', 'soal.deskripsi', 'question.test_code', 'soal_latihan.latihan_id')
            ->get();
        $jumlah_soal = soal_latihan::where('latihan_id', '=', $request->latihan_id)->get()->count();
        return view('student.question.index', compact('soal', 'jumlah_soal'));
    }

    public function getExerciseQuestion(Request $request)
    {

        $questionDetails = soal::where('id', $request->soal_id)->get();
        $exerciseDetails = latihan::where('id', $request->latihan_id)->get();
        return response()->json(['code' => 1, 'questionDetails' => $questionDetails, 'exerciseDetails' => $exerciseDetails]);
    }

    public function addExerciseQuestion(Request $request)
    {
        $result = soal_latihan::updateOrCreate(
            ['latihan_id' => $request->eid, 'soal_id' => $request->qid],
            ['latihan_id' => $request->eid, 'soal_id' => $request->qid, 'no' => $request->no, 'isRemoved' => 0]
        );

        if ($result) {
            return response()->json(['code' => 1, 'msg' => 'Soal berhasil ditambahkan!']);
        } else {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        }
    }

    public function removeExerciseQuestion(Request $request)
    {

        $question = soal_latihan::updateOrCreate(
            ['latihan_id' => $request->latihan_id, 'soal_id' => $request->soal_id],
            ['isRemoved' => 1]
        );

        if (!$question) {
            return response()->json(['code' => 0, 'msg' => 'Terjadi kesalahan']);
        } else {
            return response()->json(['code' => 1, 'msg' => 'Soal berhasil dihapus']);
        }
    }
}
