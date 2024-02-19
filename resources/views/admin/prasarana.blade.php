@extends('admin.layouts.master')

@section('title', 'Data Prasarana')
@section('judul', 'Data Prasarana')
@section('content')
    <div class="container-fluid">
        <div class="card card-default">
            <div class="card-header">{{ __('Pengelolaan Prasarana') }}</div>
            <div class="card-body">
            <button class="btn btn-primary" data-toggle="modal" data-target="#tambahMasukModal"><i class="fa fa-plus"></i>
                    Tambah Data</button>
                    <hr />
                <table id="table-data" class="table table-striped table-white ">
                    <thead>
                        <tr class="text-center">
                            <th>NO</th>
                            <th>Kode</th>
                            <th>Tanggal</th>
                            <th>Ruangan</th>
                            <th>Jumlah Ada</th>
                            <th>Kondisi</th>
                            <th>Jumlah Diperlukan</th>
                            <th>Keterangan</th>
                            <th>AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no=1; @endphp
                        @foreach ($prasarana as $row)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $row->kode}}</td>
                                <td>{{ $row->tanggal}}</td>
                                <td>{{ $row->ruangan->nama }}</td>
                                <td>{{ $row->jumlah_ada}}</td>
                                <td>{{ $row->kondisi}}</td>
                                <td>{{ $row->jumlah_diperlukan}}</td>
                                <td>{{ $row->keterangan}}</td>
                                
                                <td>
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <button type="button" id="btn-edit-jadwal" class="btn btn-success"
                                            data-toggle="modal" data-target="#editMasukModal"
                                            data-id="{{ $row->id }}"><i class="fa fa-edit"></i></button>
                                            <button class="btn btn-xs"></button>
                                            <button type="button" class="btn btn-danger"
                                            onclick="deleteConfirmation('{{ $row->id }}', '{{ $row->kode }}' )"><i class="fa fa-times"></i></button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tambah Jadwal -->
    <div class="modal fade" id="tambahMasukModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Data Prasarana</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route('admin.prasarana.submit') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="tanggal">Tanggal</label>
                            <input type="date" class="form-control" name="tanggal" id="tanggal" required />
                        </div>
                        <div class="form-group">
                        <label for="user">Ruangan</label>
                        <input type="text" name="ruangan" id="user" class="form-control" placeholder="Masukan Nama Ruangan" required/>
                        <div id="namalist"></div>
                        </div>
                        <div class="form-group">
                            <label for="ada">Jumlah Ada</label>
                            <input type="number" class="form-control" name="ada" id="ada" required />
                        </div>
                        <div class="form-group">
                            <label for="kondisi">Kondisi</label>
                            <select name="kondisi" id="kondisi" class="form-control" required>
                                <option value="">Pilih Kondisi</option>
                                <option value="Baik">Baik </option>
                                <option value="Rusak Sedang">Rusak Sedang</option>
                                <option value="Rusak Berat">Rusak Berat</option>
                                
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="diperlukan">Jumlah Diperlukan</label>
                            <input type="number" class="form-control" name="diperlukan" id="diperlukan" required />
                        </div>
                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <textarea class="form-control" id="keterangan" name="keterangan" required>  </textarea>
                        </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!--Ubah Data-->
     <!-- UBAH DATA -->
     <div class="modal fade" id="editMasukModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ubah Data Ruangan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route('admin.prasarana.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <div class="form-group">
                            <label for="edit-tanggal">Tanggal</label>
                            <input type="date" class="form-control" name="tanggal" id="edit-tanggal" required />
                        </div>
                        <div class="form-group">
                            <label for="edit-ruangan">Ruangan</label>
                            <select name="ruangan" id="edit-ruangan" class="form-control" readonly>
                                <option value="">Pilih Ruangan</option required>
                                @foreach($ruangan as $rg)
                            <option value="{{$rg->id}}">{{$rg->nama}}</option>
                            @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit-ada">Jumlah Ada</label>
                            <input type="number" class="form-control" name="ada" id="edit-ada" required />
                        </div>
                        <div class="form-group">
                            <label for="edit-kondisi">Kondisi</label>
                            <select name="kondisi" id="edit-kondisi" class="form-control" required>
                                <option value="">Pilih Kondisi</option>
                                <option value="Baik">Baik </option>
                                <option value="Rusak Sedang">Rusak Sedang</option>
                                <option value="Rusak Berat">Rusak Berat</option>
                                
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit-diperlukan">Jumlah Diperlukan</label>
                            <input type="number" class="form-control" name="diperlukan" id="edit-diperlukan" required />
                        </div>
                        <div class="form-group">
                            <label for="edit-keterangan">Keterangan</label>
                            <textarea class="form-control" id="edit-keterangan" name="keterangan" required>  </textarea>
                        </div>
                        </div>

                <div class="modal-footer">
                <input type="hidden" name="kode" id="edit-kode" />
                <input type="hidden" name="id" id="edit-id" />
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-success">Ubah</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @stop

    @section('js')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
$(function() {
            $(document).on('click', '#btn-edit-jadwal', function() {
                let id = $(this).data('id');
                $.ajax({
                    type: "get",
                    url: "{{ url('admin/prasarana/ajaxadmin/dataPrasarana') }}/" + id,
                    dataType: 'json',
                    success: function(res) {
                        $('#edit-tanggal').val(res.tanggal);
                        $('#edit-ada').val(res.jumlah_ada);
                        $('#edit-kondisi').val(res.kondisi);
                        $('#edit-diperlukan').val(res.jumlah_diperlukan);
                        $('#edit-keterangan').val(res.keterangan);
                        $('#edit-ruangan').val(res.ruangan_id);
                        $('#edit-kode').val(res.kode);
                        $('#edit-id').val(res.id);
                    },
                });
            });
        });

        @if(session('status'))
            Swal.fire({
                title: 'Congratulations!',
                text: "{{ session('status') }}",
                icon: 'Success',
                timer: 3000
            })
        @endif
        @if($errors->any())
            @php
                $message = '';
                foreach($errors->all() as $error)
                {
                    $message .= $error."<br/>";
                }
            @endphp
            Swal.fire({
                title: 'Error',
                html: "{!! $message !!}",
                icon: 'error',
            })
        @endif
        
        function deleteConfirmation(npm, judul) {
            swal.fire({
                title: "Hapus?",
                type: 'warning',
                text: "Apakah anda yakin akan menghapus data dengan kode " + judul + "?!",

                showCancelButton: !0,
                confirmButtonText: "Ya, lakukan!",
                cancelButtonText: "Tidak, batalkan!",
                reverseButtons: !0
            }).then(function(e) {

                if (e.value === true) {
                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

                    $.ajax({
                        type: 'POST',
                        url: "prasarana/delete/" + npm,
                        data: {
                            _token: CSRF_TOKEN
                        },
                        dataType: 'JSON',
                        success: function(results) {
                            if (results.success === true) {
                                swal.fire("Done!", results.message, "success");
                                // refresh page after 2 seconds
                                setTimeout(function() {
                                    location.reload();
                                }, 1000);
                            } else {
                                swal.fire("Error!", results.message, "error");
                            }
                        }
                    });

                } else {
                    e.dismiss;
                }

            }, function(dismiss) {
                return false;
            })
        }
        
        $(document).ready(function() {
            $('#edit-nik').prop('disabled',true);
$('#user').keyup(function() {
    var query = $(this).val();
    if (query != '') {
        var _token = $('input[name="csrf-token"]').val();
        $.ajax({
            url: "{{ url('admin/prasarana/fetch') }}",
            method: "GET",
            data: {
                query: query,
                _token: _token
            },
            success: function(data) {
                $('#namalist').fadeIn();
                $('#namalist').html(data);
            }
        });
    }
});

$(document).on('click', 'li', function() {
    $('#user').val($(this).text());
    $('#namalist').fadeOut();
});
  
});

        </script>
    @stop