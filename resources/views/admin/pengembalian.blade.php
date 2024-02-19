@extends('admin.layouts.master')

@section('title', 'Data Pengembalian')
@section('judul', 'Data Pengembalian')
@section('content')
    <div class="container-fluid">
        <div class="card card-default">
            <div class="card-header">{{ __('Pengelolaan Pengembalian') }}</div>
            <div class="card-body">
            <button class="btn btn-primary" data-toggle="modal" data-target="#tambahMasukModal"><i class="fa fa-plus"></i>
                    Tambah Data</button>
                    <hr />
                <table id="table-data" class="table table-striped table-white ">
                    <thead>
                        <tr class="text-center">
                            <th>NO</th>
                            <th>Kode</th>
                            <th>Tanggal Pengembalian</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Peminjam</th>
                            <th>Kondisi</th>
                            <th>Jumlah Rusak</th>
                            <th>Denda</th>
                            <th>Status</th>
                            <th>AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no=1; @endphp
                        @foreach ($pengembalian as $row)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $row->kode}}</td>
                                <td>{{ $row->tanggal_pengembalian}}</td>
                                <td>{{ $row->barang->kode_barang}}</td>
                                <td>{{ $row->barang->nama_barang}}</td>
                                <td>{{ $row->barang->peminjam}}</td>
                                <td>{{ $row->kondisi }}</td>                       
                                <td>{{ $row->jumlah_rusak }}</td>                                             
                                <td>{{ $row->denda }}</td>                                             
                                <td>@if ($row->status == 1)
                                    <span>Dibayar</span>
                                    @elseif ($row->status != 1)
                                    <span>Belum dibayar</span>
                                    
                                    
                                    @endif
                                </td>         
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
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Data Pengembalian</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route('admin.pengembalian.submit') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="tanggal_pengembalian">Tanggal Pengembalian</label>
                            <input type="date" class="form-control" name="tanggal_pengembalian" id="tanggal_pengembalian" required />
                        </div>
                        <div class="form-group">
                        <label for="barang">Barang</label>
                        <input type="text" name="pengembalian" id="barang" class="form-control" placeholder="Masukan Kode Peminjaman" required/>
                        <div id="listbarang"></div>
                        </div>
                        <div class="form-group">
                            <label for="kondisi">Kondisi</label>
                            <select name="kondisi" id="kondisi" class="form-control" required>
                                <option value="">Pilih Kondisi</option>
                            <option value="Baik">Baik</option>
                            <option value="Rusak">Rusak</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="jumlah">Jumlah Rusak</label>
                            <input type="number" class="form-control" name="jumlah" id="jumlah" required />
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
                    <h5 class="modal-title" id="exampleModalLabel">Ubah Data Pengembalian</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route('admin.pengembalian.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <div class="form-group">
                            <label for="edit-tanggal_pengembalian">Tanggal Pengembalian</label>
                            <input type="date" class="form-control" name="tanggal_pengembalian" id="edit-tanggal_pengembalian" required />
                        </div>
                        <div class="form-group">
                        <label for="edit-kode_peminjaman">Kode Pengembalian</label>
                        <input type="text" name="pengembalian" id="edit-kode_peminjaman" class="form-control" placeholder="Masukan Kode Peminjaman" readonly/>
                        </div>
                        <div class="form-group">
                            <label for="edit-kondisi">Kondisi</label>
                            <select name="kondisi" id="edit-kondisi" class="form-control" required>
                                <option value="">Pilih Kondisi</option required>
                            <option value="Baik">Baik</option>
                            <option value="Rusak">Rusak</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit-jumlah">Jumlah Rusak</label>
                            <input type="number" class="form-control" name="jumlah" id="edit-jumlah" required />
                        </div>
                        <div class="form-group">
                            <label for="edit-status">Status</label>
                            <select name="status" id="edit-status" class="form-control" required>
                                <option value="">Pilih Status</option >
                            <option value="1">Dibayar</option>
                            <option value="0">Belum Dibayar</option>
                            </select>
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
                    url: "{{ url('admin/pengembalian/ajaxadmin/dataPengembalian') }}/" + id,
                    dataType: 'json',
                    success: function(res) {
                        $('#edit-tanggal_pengembalian').val(res.tanggal_pengembalian);
                        $('#edit-kode_peminjaman').val(res.kode_peminjaman);
                        $('#edit-kondisi').val(res.kondisi);
                        $('#edit-jumlah').val(res.jumlah_rusak);
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
                        url: "peminjaman/delete/" + npm,
                        data: {
                            _token: CSRF_TOKEN
                        },
                        dataType: 'JSON',
                        success: function(results) {
                            if (results.success === true) {
                                swal.fire("Selamat", results.message, "success");
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
$('#barang').keyup(function() {
    var query = $(this).val();
    if (query != '') {
        var _token = $('input[name="csrf-token"]').val();
        $.ajax({
            url: "{{ url('admin/pengembalian/fetch') }}",
            method: "GET",
            data: {
                query: query,
                _token: _token
            },
            success: function(data) {
                $('#listbarang').fadeIn();
                $('#listbarang').html(data);
            }
        });
    }
});
});

$(document).on('click', 'li', function() {
    $('#barang').val($(this).text());
    $('#listbarang').fadeOut();

  
});

        </script>
    @stop