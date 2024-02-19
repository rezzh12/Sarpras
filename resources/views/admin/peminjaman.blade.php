@extends('admin.layouts.master')

@section('title', 'Data Peminjaman')
@section('judul', 'Data Peminjaman')
@section('content')
    <div class="container-fluid">
        <div class="card card-default">
            <div class="card-header">{{ __('Pengelolaan Peminjaman') }}</div>
            <div class="card-body">
            <button class="btn btn-primary" data-toggle="modal" data-target="#tambahMasukModal"><i class="fa fa-plus"></i>
                    Tambah Data</button>
                    <hr />
                <table id="table-data" class="table table-striped table-white ">
                    <thead>
                        <tr class="text-center">
                            <th>NO</th>
                            <th>Kode</th>
                            <th>Tanggal Peminjaman</th>
                            <th>Tanggal Pengembalian</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Peminjam</th>
                            <th>Jumlah</th>
                            <th>Keterangan</th>
                            <th>Status</th>
                            <th>AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no=1; @endphp
                        @foreach ($peminjaman as $row)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $row->kode}}</td>
                                <td>{{ $row->tanggal_peminjaman}}</td>
                                <td>{{ $row->tanggal_pengembalian}}</td>
                                <td>{{ $row->kode_barang}}</td>
                                <td>{{ $row->nama_barang}}</td>
                                <td>{{ $row->peminjam}}</td>
                                <td>{{ $row->jumlah }}</td>                       
                                <td>{{ $row->keterangan }}</td>                       
                                <td>@if ($row->status == 1)
                                    <span>Disetujui</span>
                                    @elseif ($row->status == 2)
                                    <span>Ditolak</span>
                                    @elseif ($row->status == Null)
                                    <span>Belum diverifikasi</span>
                                    
                                    
                                    @endif
                                </td>         
                                <td>
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                    <a class="btn  btn-success" href="peminjaman/terima/{{$row->id}}"><i class="fa fa-check"></i></a>
                                            <button class="btn btn-xs"></button>
                                            <a class="btn  btn-warning" href="peminjaman/tolak/{{$row->id}}"><i class="fa fa-times"></i></a>
                                            <button class="btn btn-xs"></button>
                                            <button type="button" class="btn btn-danger"
                                            onclick="deleteConfirmation('{{ $row->id }}', '{{ $row->kode }}' )"><i class="fa fa-trash"></i></button>
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
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Data Peminjaman</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route('admin.peminjaman.submit') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="tanggal_peminjaman">Tanggal Peminjaman</label>
                            <input type="date" class="form-control" name="tanggal_peminjaman" id="tanggal_peminjaman" required />
                        </div>
                        <div class="form-group">
                            <label for="tanggal_pengembalian">Tanggal Pengembalian</label>
                            <input type="date" class="form-control" name="tanggal_pengembalian" id="tanggal_pengembalian" required />
                        </div>
                        <div class="form-group">
                        <label for="barang">Barang</label>
                        <input type="text" name="barang" id="barang" class="form-control" placeholder="Masukan Nama Barang" />
                        <div id="listbarang"></div>
                        </div>
                      
                        <div class="form-group">
                            <label for="peminjam">Peminjam</label>
                            <input type="text" class="form-control" name="peminjam" id="peminjam" required />
                        </div>
                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <input type="text" class="form-control" name="keterangan" id="keterangan" required />
                        </div>
                        <div class="form-group">
                            <label for="jumlah">Jumlah</label>
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
                    <h5 class="modal-title" id="exampleModalLabel">Ubah Data Peminjaman</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route('admin.peminjaman.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <div class="form-group">
                            <label for="edit-tanggal_peminjaman">Tanggal Peminjaman</label>
                            <input type="date" class="form-control" name="tanggal_peminjaman" id="edit-tanggal_peminjaman" required />
                        </div>
                        <div class="form-group">
                            <label for="edit-tanggal_pengembalian">Tanggal Pengembalian</label>
                            <input type="date" class="form-control" name="tanggal_pengembalian" id="edit-tanggal_pengembalian" required />
                        </div>
                      
                        <div class="form-group">
                            <label for="edit-barang">Barang</label>
                            <input type="text" class="form-control" name="barang" id="edit-barang" required readonly />
                        </div>
                        <div class="form-group">
                            <label for="edit-peminjam">Peminjam</label>
                            <input type="text" class="form-control" name="peminjam" id="edit-peminjam" required />
                        </div>
                        <div class="form-group">
                            <label for="edit-keterangan">Keterangan</label>
                            <input type="text" class="form-control" name="keterangan" id="edit-keterangan" required />
                        </div>
                        <div class="form-group">
                            <label for="edit-jumlah">Jumlah</label>
                            <input type="number" class="form-control" name="jumlah" id="edit-jumlah" required />
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
                    url: "{{ url('admin/peminjaman/ajaxadmin/dataPeminjaman') }}/" + id,
                    dataType: 'json',
                    success: function(res) {
                        $('#edit-tanggal_peminjaman').val(res.tanggal_peminjaman);
                        $('#edit-tanggal_pengembalian').val(res.tanggal_pengembalian);
                        $('#edit-barang').val(res.nama_barang);
                        $('#edit-peminjam').val(res.peminjam);
                        $('#edit-keterangan').val(res.keterangan);
                        $('#edit-jumlah').val(res.jumlah);
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
            url: "{{ url('admin/barang_masuk/fetch') }}",
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