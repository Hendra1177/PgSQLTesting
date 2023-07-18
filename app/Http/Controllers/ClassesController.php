<?php

namespace App\Http\Controllers;

use App\Models\kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use app\Models\latihan;

class ClassesController extends Controller
{
    public function addClass(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string',
            'tahun_akademik_id' => 'required|numeric',
            'dosen_id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {
            $class = new kelas();
            $class->nama = $request->nama;
            $class->tahun_akademik_id = $request->tahun_akademik_id;
            $class->dosen_id = $request->dosen_id;
            $query = $class->save();

            if (!$query) {
                return response()->json(['code' => 0, 'msg' => 'Terjadi kesalahan']);
            } else {
                return response()->json(['code' => 1, 'msg' => 'Kelas baru berhasil ditambahkan']);
            }
        }
    }
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
            $exercise->name = $request->name;
            $exercise->academic_year_id = $request->year_id;
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
            $exercise->name = $request->name;
            $exercise->academic_year_id = $request->academic_year_id;
            $exercise->description = $request->description;
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
        $data['exercise'] = Latihan::where('academic_year_id', $request->yid)->get();
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

    public function getClassDetail(Request $request)
    {
        $classDetail = kelas::with('teacher', 'year')->where('id', $request->class_id)->get();
        return response()->json(['code' => 1, 'details' => $classDetail]);
    }

    public function updateClass(Request $request)
    {
        $class_id = $request->cid;
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'academic_year_id' => 'required|numeric',
            'teacher_id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {
            $class = kelas::find($class_id);
            $class->name = $request->nama;
            $class->tahun_akademik_id = $request->tahun_akademik_id;
            $class->dosen_id = $request->dosen_id;
            $query = $class->save();

            if (!$query) {
                return response()->json(['code' => 0, 'msg' => 'Terjadi kesalahan']);
            } else {
                return response()->json(['code' => 1, 'msg' => 'Kelas berhasil diperbarui']);
            }
        }
    }

}
