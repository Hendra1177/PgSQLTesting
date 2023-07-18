@extends('student.master') @section('title')
iCLOP | Daftar Soal
@endsection
@section('content-header')
<div class="content-header">
    <div class="container">
        <div class="row">
            <div class="col">
                <p>Daftar Soal</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="content">
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-12"> 
                    <table id="tabel_soal" class="table table-hover table-head-fixed text-nowrap" style="width: 100%">
                        <thead>
                            <th>No</th>
                            <th>Judul</th>
                            <th>Jenis</th>
                            <th>deskripsi</th>
                            <th>Aksi</th>
                        </thead>
                        <tbody>
                            <tr>
                            @foreach ($soal_latihan as $item)
                            <td>{{$item -> no}}</td>
                            <td>{{$item -> judul}}</td>
                            <td>{{$item -> jenis}}</td>
                            <td>{{$item -> deskripsi}}</td>
                            <td>
                            <a href="{{ route('student.exerciseQuestion.question', ['latihan_id' => $item->{'latihan_id'}, 'soal_id' => $item->{'id'}]) }}"
                                class="btn btn-primary" role="button"> KERJAKAN <i class="fas fa-arrow-circle-right"></i></a>
                                
                            </td>
                            </tr>
                            @endforeach
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')

@endsection