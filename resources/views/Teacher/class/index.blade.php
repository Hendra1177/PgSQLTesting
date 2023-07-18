@extends('Teacher.master')

@section('title', 'iCLOP | Kelas')

@section('content-header')
    <div class="content-header">
        <div class="container">
            <div class="row">
                <div class="col">
                    <p>Kelas</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <form action="{{ route('admin.class.add') }}" method="POST" id="form_tambah_data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="text" name="name" class="form-control form-control"
                                            placeholder="Nama">
                                    </div>
                                    <span class="text-danger error-text name_error"></span>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <p class="float-right">Tabel Mahasiswa</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <select name="academic_year_id" class="form-control">
                                        <option value="" disabled selected>Tahun Ajaran</option>
                                    </select>
                                    <span class="text-danger error-text year_academic_id_error"></span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Cari</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-12">
                    <div class="row">
                        @forelse ($class as $item)
                            <div class="col-lg-6">
                                <div class="card card-primary card-outline">
                                    <div class="card-body box-profile">
                                        <div class="text-center">
                                            <i class="fa-solid fa-building-columns"></i>
                                        </div>
                                        <hr>
                                        <h3 class="profile-username text-center">{{ $item->{'nama'} }}</h3>
                                        <p class="text-center text-muted">
                                            <br>
                                            {{ $item->year->{'nama'} }}
                                        </p>
                                        <button class="btn btn-primary btn-block" id="classDetailBtn"
                                            data-id={{ $item->{'id'} }}><b>Detail</b></button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-lg-4">
                                <code>No Data</code>
                            </div>
                        @endforelse
                        {{ $class->links() }}
                    </div>
                </div>
                <div class="col-lg-8 col-12">
                    <div class="col-12">
                        <table id="class_student_table" class="table table-hover table-head-fixed text-nowrap"
                            style="width: 100%">
                            <thead>
                                <th>Nama</th>
                                <th>Kelas</th>
                                <th>Aksi</th>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $("#class_student_table").DataTable({
            processing: true,
            info: true,
            serverSide: true,
            ajax: "{{ route('teacher.class.getDatatable', ['class_id => -1']) }}",
            columns: [{
                    data: "name",
                    name: "name",
                },
                {
                    data: "kelas_nama",
                    name: "kelas_nama",
                },
                {
                    data: "actions",
                    name: "actions",
                },
            ],
        });


        $(document).on("click", "#classDetailBtn", function() {
            const class_id = $(this).data("id");
            var url = "{{ route('admin.student.getDatatable', ':id') }}";
            url = url.replace(':id', class_id);
            $("#class_student_table").DataTable().ajax.url(url).load();
        });
    </script>

@endsection
