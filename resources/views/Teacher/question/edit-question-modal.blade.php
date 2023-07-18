<div class="modal fade editQuestionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
    data-keyboard="false" data-backdrop="static" id="updateModal">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><b>Update Data Pembelajaran<b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-header"><b>Update Data Tugas<b></div>
                    <div class="card-body">
                        <form action="{{route('teacher.question.update')}}" enctype="multipart/form-data" method="POST"
                            id="update_question">
                            @csrf
                            <input type="hidden" name="qid">
                            <div class="form-row">
                                <div class="form-group col-sm-6">
                                    <label for="title">Judul</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="judul" placeholder="Nama tugas">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-book"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="text-danger error-text title_error"></span>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="topic">Jenis</label>
                                    <div class="input-group">
                                        <select class="form-control" name="jenis">
                                        <option selected disabled>- Pilih Topik -</option>
                                        <option value="INSERT">INSERT</option>
                                         <option value="UPDATE">UPDATE</option>
                                        <option value="DELETE">DELETE</option>
                                        </select>
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-list"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="text-danger error-text topic_error"></span>
                                </div>
                            </div>
                            <div class="form-row">
                                <!-- <div class="form-group col-sm-6">
                                    <label for="dbname">Nama Database</label>
                                    <span class="fas fa-question" data-toggle="tooltip_dbname" data-placement="right"
                                        title="Nama Database yang akan digunakan untuk pembelajaran."></span>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="dbname" placeholder="Nama tugas">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-database"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="text-danger error-text dbname_error"></span>
                                </div> -->
                                <!-- <div class="form-group col-sm-6">
                                    <label for="score">Skor</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="score"
                                            placeholder="Nilai tugas">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-star-half"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="text-danger error-text score_error"></span>
                                </div> -->
                            </div>
                            <div class="form-row">
                                <div class="form-group col-sm-12">
                                    <label for="description">Deskripsi</label>
                                    <div class="input-group">
                                        <textarea rows="3" type="text" class="form-control" name="deskripsi"
                                            placeholder="Deskripsi soal"></textarea>
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-sticky-note"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="text-danger error-text description_error"></span>
                                </div>
                            </div>
                            <!-- <div class="form-row">
                                <div class="form-group col-sm-12">
                                    <label for="required_table">Required Table</label>
                                    <span class="fas fa-question" data-toggle="tooltip_requiredTable"
                                        data-placement="right"
                                        title="TIDAK WAJIB DIISI. Digunakan untuk membuat tabel yang dibutuhkan untuk pemebelajaran."></span>
                                    <div class="input-group">
                                        <textarea rows="5" type="text" class="form-control" name="required_table"
                                            placeholder="Required table"></textarea>
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-code"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="text-danger error-text required_table_error"></span>
                                </div>
                            </div> -->
                            <div class="form-row">
                                <div class="form-group col">
                                    <label for="test_code">Test Code</label>
                                    <span class="fas fa-question" data-toggle="tooltip_requiredTable"
                                        data-placement="right" title="Contoh tersedia pada button dibawah."></span>
                                    <div>
                                        <button type="button" class="toggleShowTestCodeBox btn btn-primary btn-sm mb-3"
                                            style="">Tampilkan
                                            Contoh</button>
                                    </div>
                                    <div class="testCodeBox" style="display: none;">
                                        <code style="display:block; white-space:pre-wrap">
                                        insert into pegawai (id, nama, alamat, jabatan) values (1, 'ridwan', 'jl. ikan piranha No.8', 'supervisor');
                                            </code>
                                            <p>
                                            untuk test code menggunakan lowercase semua
                                            </p>
                                    </div>
                                    <div class="input-group">
                                        <textarea rows="5" type="text" class="form-control" name="jawaban"
                                            placeholder="Test code soal"></textarea>
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-code"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="text-danger error-text test_code_error"></span>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-sm-12">
                                    <label for="guidance_update">File Soal</label>
                                    <span class="fas fa-question" data-toggle="tooltip_requiredTable"
                                        data-placement="right" title="Kosongi jika tidak mengubah file panduan."></span>
                                    <div class="input-group">
                                        <input type="file" class="form-control" name="soal_update" data-value="">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-file-pdf"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="text-danger error-text guidance_update_error"></span>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-warning btn-block">Simpan Perubahan</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>