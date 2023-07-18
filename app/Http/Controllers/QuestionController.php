<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\soal;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;


class QuestionController extends Controller
{
    public function getQuestionDataTable()
    {
        $soal = soal::all();
        return DataTables::of($soal)
            ->addColumn('actions', function ($row) {
                return '<div class="btn-group" role="group">
            <button id="detailBtn" type="button" class="btn btn-primary btn-block" data-id="' . $row->id . '">
            <i class="fa fa-eye"></i>
            </button> 
            </div>';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }


    public function addQuestion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string',
            'jenis' => 'required|string',
            'deskripsi' => 'required|string',
            'jawaban' => 'required|string',
            'soal' => 'required|mimes:pdf|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {

            $path = 'dml_soal/';
            $file = $request->file('soal');
            $file_name = $file->getClientOriginalName();

            $upload = $file->storeAs($path, $file_name, 'public');

            if ($upload) {
                soal::insert([
                    'judul' => $request->judul,
                    'jenis' => $request->jenis,
                    'deskripsi' => $request->deskripsi,
                    'jawaban' => $request->jawaban,
                    'soal' => $file_name,
                ]);
                return response()->json(['code' => 1, 'msg' => 'BERHASIL menambahkan soal baru.']);
            } else {
                return response()->json(['code' => 0, 'msg' => 'GAGAL menambahkan soal baru.']);
            }
        }
    }

    public function getQuestionDetail(Request $request)
    {
        $detailSoal = soal::find($request->soal_id);
        return response()->json(['code' => 1, 'details' => $detailSoal]);
    }


    public function updateQuestion(Request $request)
    {
        $question_id = $request->qid;
        $task = soal::find($question_id);
        $path = 'dml_soal/';

        $validator = Validator::make($request->all(), [
            'judul' => 'required|string',
            'jenis' => 'required|string',
            'deskripsi' => 'required|string',
            'jawaban' => 'required|string',
            'soal_update' => 'mimes:pdf|max:2048|unique:questions,guide',
        ]);

        if ($validator->fails()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {
            if ($request->hasFile('soal_update')) {
                $file_path = $path . $task->soal;
                if ($task->soal != null && Storage::disk('public')->exists($file_path)) {
                    Storage::disk('public')->delete($file_path);
                }
                $file = $request->file('soal_update');
                $file_name = $file->getClientOriginalName();
                $upload = $file->storeAs($path, $file_name, 'public');

                if ($upload) {
                    $task->update([
                        'judul' => $request->judul,
                        'jenis' => $request->jenis,
                        'deskripsi' => $request->deskripsi,
                        'jawaban' => $request->jawaban,
                        'soal' => $file_name,
                    ]);
                    return response()->json(['code' => 1, 'msg' => 'BERHASIL memperbarui data soal.']);
                }
            } else {
                $task->update([
                    'judul' => $request->judul,
                    'jenis' => $request->jenis,
                    'deskripsi' => $request->deskripsi,
                    'jawaban' => $request->jawaban,
                ]);
                return response()->json(['code' => 1, 'msg' => 'BERHASIL memperbarui data soal.']);
            }
        }
    }
}
