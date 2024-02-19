<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Ruangan;
use App\Models\Prasarana;
use App\Models\Barang;
use App\Models\Barang_Masuk;
use App\Models\Barang_Keluar;
use App\Models\PinjamRuangan;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\PengembalianRuangan;
use App\Models\Denda;
use App\Models\Laporan;
use App\Models\Roles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use PDF;
use Session;
use Carbon\Carbon;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $prasarana = Prasarana::Count();
        $sarana = Barang::Count();
        $peminjaman = Peminjaman::where('peminjam',auth()->user()->name)->Count();
        $pengembalian = Pengembalian::where('peminjam',auth()->user()->name)->Count();
        $user = Auth::user();
        return view('user.home', compact( 'user','prasarana','sarana','peminjaman','pengembalian'));
    }
    public function prasarana()
    {
        $user = Auth::user();
        $ruangan = Ruangan::all();
        return view('user.ruangan', compact( 'user','ruangan'));
    }
    public function sarana()
    {
        $user = Auth::user();
        $sarana = Barang::all();
        return view('user.sarana', compact( 'user','sarana'));
    }
    public function submit_peminjaman_ruangan(Request $req){
        $id = IdGenerator::generate(['table' => 'pinjam_ruangans','field'=>'kode', 'length' => 7, 'prefix' =>'PR']);
            { $validate = $req->validate([
                'tanggal_peminjaman'=> 'required',
                'tanggal_pengembalian'=> 'required',
                'keterangan'=> 'required|max:150',

            ]);
            
            $ruangan_nama = Ruangan::where('id',$req->get('id'))->value('nama');
            $ruangan_kode = Ruangan::where('nama',$ruangan_nama)->value('kode');
          if($ruangan_nama == Null){
            Session::flash('status', 'Kode Ruangan Tidak Ditemukan!!!');
            return redirect()->route('user.prasarana');
          }else{
            $PRuangan = new PinjamRuangan;
            $PRuangan->kode = $id;
            $PRuangan->dari_tanggal = $req->get('tanggal_peminjaman');
            $PRuangan->sampai_tanggal = $req->get('tanggal_pengembalian');
            $PRuangan->kode_ruangan =  $ruangan_kode;
            $PRuangan->nama_ruangan =  $ruangan_nama;
            $PRuangan->peminjam = auth()->user()->name;
            $PRuangan->keterangan =  $req->get('keterangan');
            $PRuangan->status =  Null;
            $PRuangan->save();
            Session::flash('status', 'Tambah data Peminjaman Ruangan berhasil!!!');
            return redirect()->route('user.prasarana');
        }}}

    public function peminjaman()
    {
        $user = Auth::user();
        $peminjaman = Peminjaman::where('peminjam',auth()->user()->name)->get();
        $barang = Barang::all();
        return view('user.peminjaman', compact( 'user','barang','peminjaman'));
    } 
    public function peminjaman_submit(Request $req){
        $id = IdGenerator::generate(['table' => 'peminjamen','field'=>'kode', 'length' => 7, 'prefix' =>'PJ']);
            { $validate = $req->validate([
                'tanggal_peminjaman'=> 'required',
                'tanggal_pengembalian'=> 'required',
                'keterangan'=> 'required',
                'jumlah'=> 'required',

            ]);
            $barang_nama = Barang::where('kode',$req->get('kode'))->value('nama_barang');
          
            $peminjaman = new Peminjaman;
            $peminjaman->kode = $id;
            $peminjaman->tanggal_peminjaman = $req->get('tanggal_peminjaman');
            $peminjaman->tanggal_pengembalian = $req->get('tanggal_pengembalian');
            $peminjaman->kode_barang =  $req->get('kode');
            $peminjaman->nama_barang =  $barang_nama;
            $peminjaman->peminjam = auth()->user()->name;
            $peminjaman->keterangan =  $req->get('keterangan');
            $peminjaman->jumlah =  $req->get('jumlah');
            $peminjaman->status =  Null;
            $peminjaman->save();
            Session::flash('status', 'Pengajuan Peminjaman berhasil!!!');
            return redirect()->route('user.peminjaman');
        }}
        public function delete_peminjaman($id)
        {
            $peminjaman = Peminjaman::find($id);
            $peminjaman->delete();
    
            $success = true;
            $message = "Data Peminjaman Berhasil Dihapus";
            return response()->json([
                'success' => $success,
                'message' => $message,
            ]);
        }
    
    public function pengembalian()
    {
        $user = Auth::user();
        $pengembalian = Pengembalian::where('peminjam',auth()->user()->name)->get();
        $barang = Barang::all();
        return view('user.pengembalian', compact( 'user','barang','pengembalian'));
    }
    public function riwayat_denda()
    {
        $user = Auth::user();
        $denda = Denda::where('nama',auth()->user()->name)->get();
      
        return view('user.denda', compact( 'user','denda'));
    }
    public function pinjam_prasarana()
    {
        $user = Auth::user();
        $PRuangan = PinjamRuangan::where('peminjam',auth()->user()->name)->get();
        $barang = Barang::all();
        return view('user.pinjam_prasarana', compact( 'user','barang','PRuangan'));
    } 
    public function pengembalian_prasarana()
    {
        $user = Auth::user();
        $PRuangan = PengembalianRuangan::where('peminjam',auth()->user()->name)->get();
        $barang = Barang::all();
        return view('user.pengembalian_prasarana', compact( 'user','barang','PRuangan'));
    }
    public function delete_pinjam_prasarana($id)
    {
        $peminjaman = PinjamRuangan::find($id);
        $peminjaman->delete();

        $success = true;
        $message = "Data Peminjaman Berhasil Dihapus";
        return response()->json([
            'success' => $success,
            'message' => $message,
        ]);
    }
}
