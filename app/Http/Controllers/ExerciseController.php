<?php

namespace App\Http\Controllers;

use App\Models\latihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class ExerciseController extends Controller
{
    public function addExercise(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'year_id' => 'required|numeric',
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {


            $exercise = new Latihan();
            $exercise->nama = $request->name;
            $exercise->tahun_akademik_id = $request->year_id;
            $exercise->description = $request->description;
            $query = $exercise->save();

            if (!$query) {
                return response()->json(['code' => 0, 'msg' => 'Terjadi kesalahan']);
            } else {
                return response()->json(['code' => 1, 'msg' => 'Latihan baru berhasil ditambahkan']);
            }
        }
    }

    public function getExerciseDetail(Request $request)
    {
        $detail = Latihan::with('year')->where('id', $request->eid)->get();
        return response()->json(['code' => 1, 'details' => $detail]);
    }

    public function updateExercise(Request $request)
    {
        $eid = $request->eid;
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'academic_year_id' => 'required',
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {
            $exercise = Latihan::find($eid);
            $exercise->nama = $request->name;
            $exercise->tahun_akademik_id = $request->tahun_akademik_id;
            $exercise->deskripsi = $request->deskripsi;
            $query = $exercise->save();

            if (!$query) {
                return response()->json(['code' => 0, 'msg' => 'Terjadi kesalahan']);
            } else {
                return response()->json(['code' => 1, 'msg' => 'Tahun Ajaran berhasil diperbarui']);
            }
        }
    }

    public function getExerciseAsOption(Request $request)
    {
        $data['exercise'] = Latihan::where('tahun_akademik_id', $request->yid)->get();
        return response()->json($data);
    }

    public function getExerciseIDForDataTable(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'exercise_id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {
            return response()->json(['code' => 1, 'msg' => $request->exercise_id]);
        }
    }
}
