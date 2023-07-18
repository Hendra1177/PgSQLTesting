<?php

namespace App\Http\Controllers;

use App\Models\tahun_akademik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
class AcademicYearController extends Controller
{
    public function getAcademicYearDataTable()
    {
        $tahun_ajaran = tahun_akademik::all();
        return DataTables::of($tahun_ajaran)
            ->addColumn('actions', function ($row) {
                if ($row['status'] == 'Selesai') {
                    return '<div class="btn-group" role="group">
                    <button id="detailTahunBtn" type="button" class="btn btn-primary" data-id="' . $row['id'] . '"
                    data-toggle="tooltip" data-placement="top" title="detail">
                    <i class="fa fa-info"></i>
                    </button> 
                    <button id="setAsActiveYearBtn" type="button" class="btn btn-success" data-id="' . $row['id'] . '"
                    data-toggle="tooltip" data-placement="top" title="Set As Aktif">
                    <i class="fa fa-check"></i>
                    </button> 
                    </div>';
                } else {
                    return '<div class="btn-group" role="group">
                    <button id="detailTahunBtn" type="button" class="btn btn-primary" data-id="' . $row['id'] . '"
                    data-toggle="tooltip" data-placement="top" title="detail">
                    <i class="fa fa-info"></i>
                    </button> 
                    <button id="setAsNonActiveYearBtn" type="button" class="btn btn-danger" data-id="' . $row['id'] . '"
                    data-toggle="tooltip" data-placement="top" title="Set As Non Aktif">
                    <i class="fa fa-times"></i>
                    </button> 
                    </div>';
                }

            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function addAcademicYear(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string',
            'semester' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_berakhir' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {
            $currentDate = date('Y-m-d');
            $currentDate = date('Y-m-d', strtotime($currentDate));

            $startDate = date('Y-m-d', strtotime($request->tanggal_berakhir));
            $endDate = date('Y-m-d', strtotime($request->tanggal_mulai));

            if (($currentDate >= $startDate) && ($currentDate <= $endDate)) {
                $status = "Aktif";
            } else {
                $status = "Selesai";
            }

            $year = new tahun_akademik();
            $year->nama = $request->nama;
            $year->semester = $request->semester;
            $year->tanggal_mulai = $request->tanggal_mulai;
            $year->tanggal_berakhir = $request->tanggal_berakhir;
            $year->status = $status;
            $query = $year->save();

            if (!$query) {
                return response()->json(['code' => 0, 'msg' => 'Terjadi kesalahan']);
            } else {
                return response()->json(['code' => 1, 'msg' => 'Tahun Ajaran baru berhasil ditambahkan']);
            }
        }
    }

    public function getAcademicYearDetail(Request $request)
    {
        $yearDetail = tahun_akademik::find($request->year_id);
        return response()->json(['code' => 1, 'details' => $yearDetail]);
    }

    public function updateAcademicYear(Request $request)
    {
        $year_id = $request->yid;
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string',
            'semester' => 'required|string',
            'tanggal_mulai' => 'required|string',
            'tanggal_berakhir' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {

            $currentDate = date('Y-m-d');
            $currentDate = date('Y-m-d', strtotime($currentDate));

            $startDate = date('Y-m-d', strtotime($request->tanggal_berakhir));
            $endDate = date('Y-m-d', strtotime($request->tanggal_mulai));

            if (($currentDate >= $startDate) && ($currentDate <= $endDate)) {
                $status = "Selesai";
            } else {
                $status = "Aktif";
            }

            $year = tahun_akademik::find($year_id);
            $year->nama = $request->nama;
            $year->status = $status;
            $year->semester = $request->semester;
            $year->tanggal_mulai = $request->tanggal_mulai;
            $year->tanggal_berakhir = $request->tanggal_berakhir;
            $query = $year->save();

            if (!$query) {
                return response()->json(['code' => 0, 'msg' => 'Terjadi kesalahan']);
            } else {
                return response()->json(['code' => 1, 'msg' => 'Tahun Ajaran berhasil diperbarui']);
            }
        }
    }


    public function setYearAsActive(Request $request)
    {
        $year = tahun_akademik::find($request->id);
        $year->status = "Aktif";
        $query = $year->save();

        if ($query) {
            return response()->json(['code' => 1, 'msg' => 'Item ditandai sebagai Aktif']);
        } else {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        }
    }

    public function setYearAsNonActive(Request $request)
    {
        $year = tahun_akademik::find($request->id);
        $year->status = "Selesai";
        $query = $year->save();

        if ($query) {
            return response()->json(['code' => 1, 'msg' => 'Item ditandai sebagai Selesai']);
        } else {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        }
    }
}
