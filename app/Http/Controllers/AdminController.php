<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Ruangan;
use App\Models\Prasarana;
use App\Models\Barang;
use App\Models\Barang_Masuk;
use App\Models\Barang_Keluar;
use App\Models\PinjamRuangan;
use App\Models\Pengembalian;
use App\Models\PengembalianRuangan;
use App\Models\Peminjaman;
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

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $prasarana = Prasarana::Count();
        $sarana = Barang::Count();
        $peminjaman = Peminjaman::Count();
        $pengembalian = Pengembalian::Count();
        $user = Auth::user();
        return view('admin.home', compact( 'user','prasarana','sarana','peminjaman','pengembalian'));
    }
    public function ruangan()
    {
        $user = Auth::user();
        $ruangan = Ruangan::all();
        return view('admin.ruangan', compact( 'user','ruangan'));
    }

    public function submit_ruangan(Request $req){
        $id = IdGenerator::generate(['table' => 'ruangans','field'=>'kode', 'length' => 7, 'prefix' =>'RA']);
            { $validate = $req->validate([
                'nama'=> 'required|max:50',
                'luas'=> 'required|max:11',
                'foto'=> 'required|max:100',
            ]);
            $ruangan = new Ruangan;
            $ruangan->kode = $id;
            $ruangan->nama = $req->get('nama');
            $ruangan->luas = $req->get('luas');
            if($req->hasFile('foto'))
            {
                $extension = $req->file('foto')->extension();
                $filename = 'foto'.time().'.'.$extension;
                $req->file('foto')->storeAS('public/ruangan', $filename);
                $ruangan->foto = $filename;
            }
            $ruangan->save();
            Session::flash('status', 'Tambah data Ruangan berhasil!!!');
            return redirect()->route('admin.ruangan');
        }}
        public function getDataRuangan($id)
        {
            $ruangan = Ruangan::find($id);
            return response()->json($ruangan);
        }
    public function update_ruangan(Request $req){
        $ruangan= Ruangan::find($req->get('id'));
        { $validate = $req->validate([
            'nama'=> 'required|max:50',
            'luas'=> 'required|max:11',
            'foto'=> 'required|max:100',
        ]);
        $ruangan->kode = $req->get('kode');
        $ruangan->nama = $req->get('nama');
        $ruangan->luas = $req->get('luas');
        if($req->hasFile('foto'))
        {
            $extension = $req->file('foto')->extension();
            $filename = 'foto'.time().'.'.$extension;
            $req->file('foto')->storeAS('public/ruangan', $filename);
            Storage::delete('public/ruangan/'.$req->get('old_foto'));
            $ruangan->foto = $filename;
        }
        $ruangan->save();
        Session::flash('status', 'Ubah data Ruangan berhasil!!!');
        return redirect()->route('admin.ruangan');
        }}

        public function delete_ruangan($id)
        {
            $ruangan = Ruangan::find($id);
            Storage::delete('public/ruangan/'.$ruangan->foto);
            $ruangan->delete();
    
            $success = true;
            $message = "Data Ruangan Berhasil Dihapus";
            return response()->json([
                'success' => $success,
                'message' => $message,
            ]);
        }

    public function prasarana()
    {
        $user = Auth::user();
        $prasarana = Prasarana::with('ruangan')->get();
        $ruangan = Ruangan::all();
        return view('admin.prasarana', compact( 'user','prasarana','ruangan'));
    }

    public function submit_prasarana(Request $req){
        $id = IdGenerator::generate(['table' => 'prasaranas','field'=>'kode', 'length' => 7, 'prefix' =>'PA']);
            { $validate = $req->validate([
                'tanggal'=> 'required',
                'ruangan'=> 'required',
                'ada'=> 'required|max:11',
                'kondisi'=> 'required|max:10',
                'diperlukan'=> 'required|max:11',
                'keterangan'=> 'required|max:150',
            ]);
            $ruangan = Ruangan::where('nama',$req->get('ruangan'))->value('id');
            if($ruangan == Null){
                Session::flash('status', 'Ruangan Tidak Ditemukan !!!');
                return redirect()->route('admin.prasarana');
            }else{
            $prasarana = new Prasarana;
            $prasarana->kode = $id;
            $prasarana->tanggal = $req->get('tanggal');
            $prasarana->ruangan_id = $ruangan;
            $prasarana->jumlah_ada =  $req->get('ada');
            $prasarana->kondisi =  $req->get('kondisi');
            $prasarana->jumlah_diperlukan =  $req->get('diperlukan');
            $prasarana->keterangan =  $req->get('keterangan');
            $prasarana->save();
            Session::flash('status', 'Tambah data Prasarana berhasil!!!');
            return redirect()->route('admin.prasarana');
        }}
        }
        public function getDataPrasarana($id)
        {
            $prasarana= Prasarana::find($id);
            return response()->json($prasarana);
        }
    public function update_prasarana(Request $req){
        $prasarana= Prasarana::find($req->get('id'));
        { $validate = $req->validate([
            'tanggal'=> 'required',
            'ruangan'=> 'required',
            'ada'=> 'required|max:11',
            'kondisi'=> 'required|max:10',
            'diperlukan'=> 'required|max:11',
            'keterangan'=> 'required|max:150',
        ]);
        $prasarana->kode = $req->get('kode');
        $prasarana->tanggal = $req->get('tanggal');
        $prasarana->ruangan_id = $req->get('ruangan');
        $prasarana->jumlah_ada =  $req->get('ada');
        $prasarana->kondisi =  $req->get('kondisi');
        $prasarana->jumlah_diperlukan =  $req->get('diperlukan');
        $prasarana->keterangan =  $req->get('keterangan');
        $prasarana->save();
        Session::flash('status', 'Ubah data Prasarana berhasil!!!');
        return redirect()->route('admin.prasarana');
        }}

        public function delete_prasarana($id)
        {
            $prasarana = Prasarana::find($id);
            $prasarana->delete();
    
            $success = true;
            $message = "Data Prasarana Berhasil Dihapus";
            return response()->json([
                'success' => $success,
                'message' => $message,
            ]);
        }

        function fetch_prasarana(Request $request)
        {
            if($request->get('query'))
            {
                $query = $request->get('query');
                $data = DB::table('ruangans')
                    ->where('nama', 'LIKE', "%{$query}%")
                    ->orwhere('kode', 'LIKE', "%{$query}%")
                    ->get();
                $output = '<ul class="dropdown-menu" style="display:block; position:relative;width:100%;">';
                foreach($data as $row)
                {
                    $output .= '
                    <li><a class="dropdown-item" href="#">'.$row->nama.'</a></li>
                    ';
                }
                $output .= '</ul>';
                echo $output;
            }
        }

        public function sarana()
        {
            $user = Auth::user();
            $sarana = Barang::all();
            return view('admin.sarana', compact( 'user','sarana'));
        }
    
        public function submit_sarana(Request $req){
            $id = IdGenerator::generate(['table' => 'barangs','field'=>'kode', 'length' => 7, 'prefix' =>'SA']);
                { $validate = $req->validate([
                    'tanggal'=> 'required',
                    'merek'=> 'required|max:10',
                    'kategori'=> 'required|max:20',
                    'nama'=> 'required|max:100',
                    'harga'=> 'required|max:11',
                    'keterangan'=> 'required|max:150',
                    'jumlah'=> 'required|max:11',
                    'foto'=> 'required|max:150',
                ]);
                $sarana = new Barang;
                $sarana->kode = $id;
                $sarana->tanggal = $req->get('tanggal');
                $sarana->merek = $req->get('merek');
                $sarana->kategori =  $req->get('kategori');
                $sarana->nama_barang =  $req->get('nama');
                $sarana->harga =  $req->get('harga');
                $sarana->keterangan =  $req->get('keterangan');
                $sarana->jumlah =  $req->get('jumlah');
                if($req->hasFile('foto'))
                {
                    $extension = $req->file('foto')->extension();
                    $filename = 'foto'.time().'.'.$extension;
                    $req->file('foto')->storeAS('public/sarana', $filename);
                    $sarana->foto = $filename;
                }
                $sarana->save();
                Session::flash('status', 'Tambah data Sarana berhasil!!!');
                return redirect()->route('admin.sarana');
            }}
            public function getDataSarana($id)
            {
                $sarana= Barang::find($id);
                return response()->json($sarana);
            }
        public function update_sarana(Request $req){
            $sarana= Barang::find($req->get('id'));
            { $validate = $req->validate([
                'tanggal'=> 'required',
                'merek'=> 'required|max:10',
                'kategori'=> 'required|max:20',
                'nama'=> 'required|max:100',
                'harga'=> 'required|max:11',
                'keterangan'=> 'required|max:150',
                'jumlah'=> 'required|max:11',
                'foto'=> 'required|max:150',
            ]);
            $sarana->kode = $req->get('kode');
            $sarana->tanggal = $req->get('tanggal');
            $sarana->merek = $req->get('merek');
            $sarana->kategori =  $req->get('kategori');
            $sarana->nama_barang =  $req->get('nama');
            $sarana->harga =  $req->get('harga');
            $sarana->keterangan =  $req->get('keterangan');
            $sarana->jumlah =  $req->get('jumlah');
            if($req->hasFile('foto'))
            {
                $extension = $req->file('foto')->extension();
                $filename = 'foto'.time().'.'.$extension;
                $req->file('foto')->storeAS('public/sarana', $filename);
                Storage::delete('public/sarana/'.$req->get('old_foto'));
                $sarana->foto = $filename;
            }
            $sarana->save();
            Session::flash('status', 'Ubah data Sarana berhasil!!!');
            return redirect()->route('admin.sarana');
            }}
    
            public function delete_sarana($id)
            {
                $sarana = Barang::find($id);
                Storage::delete('public/sarana/'.$sarana->foto);
                $sarana->delete();
        
                $success = true;
                $message = "Data Sarana Berhasil Dihapus";
                return response()->json([
                    'success' => $success,
                    'message' => $message,
                ]);
            }

        public function barang_masuk()
        {
            $user = Auth::user();
            $barang_masuk = Barang_Masuk::with('barang')->get();
            $barang = Barang::all();
            return view('admin.barang_masuk', compact( 'user','barang','barang_masuk'));
        }
    
        public function submit_barang_masuk(Request $req){
            $id = IdGenerator::generate(['table' => 'barang__masuks','field'=>'kode', 'length' => 7, 'prefix' =>'BM']);
                { $validate = $req->validate([
                    'tanggal'=> 'required',
                    'barang'=> 'required',
                    'keterangan'=> 'required|max:150',
                    'jumlah'=> 'required|max:11',

                ]);
                $barang = Barang::where('nama_barang',$req->get('barang'))->value('kode');
                if($barang==Null){
                    Session::flash('status', 'Nama Barang Tidak Ditemukan!!!');
                    return redirect()->route('admin.barangm');
                }else{
                $barang_masuk = new Barang_Masuk;
                $barang_masuk->kode = $id;
                $barang_masuk->tanggal = $req->get('tanggal');
                $barang_masuk->kode_barang =  $barang;
                $barang_masuk->keterangan =  $req->get('keterangan');
                $barang_masuk->jumlah =  $req->get('jumlah');
                $jumlahkan = 
                $barang_masuk->save();
                Session::flash('status', 'Tambah data Barang Masuk berhasil!!!');
                return redirect()->route('admin.barangm');
            }}}
            public function getDataBarangMasuk($id)
            {
                $barang_masuk= Barang_Masuk::find($id);
                return response()->json($barang_masuk);
            }
        public function update_barang_masuk(Request $req){
            $barang_masuk= Barang_Masuk::find($req->get('id'));
            { $validate = $req->validate([
                'tanggal'=> 'required',
                    'barang'=> 'required',
                    'keterangan'=> 'required|max:150',
                    'jumlah'=> 'required|max:11',

            ]);
            $barang_masuk->kode = $req->get('kode');
            $barang_masuk->tanggal = $req->get('tanggal');
            $barang_masuk->kode_barang =  $req->get('barang');
            $barang_masuk->keterangan =  $req->get('keterangan');
            $barang_masuk->jumlah =  $req->get('jumlah');
            $barang_masuk->save();
            Session::flash('status', 'Ubah data Sarana berhasil!!!');
            return redirect()->route('admin.barangm');
            }}
    
            public function delete_barang_masuk($id)
            {
                $barang_masuk = Barang_Masuk::find($id);
                $barang_masuk->delete();
        
                $success = true;
                $message = "Data Sarana Berhasil Dihapus";
                return response()->json([
                    'success' => $success,
                    'message' => $message,
                ]);
            }

            function fetch_barang_masuk(Request $request)
            {
                if($request->get('query'))
                {
                    $query = $request->get('query');
                    $data = DB::table('barangs')
                        ->where('nama_barang', 'LIKE', "%{$query}%")
                        ->orwhere('kode', 'LIKE', "%{$query}%")
                        ->get();
                    $output = '<ul class="dropdown-menu" style="display:block; position:relative;width:100%;">';
                    foreach($data as $row)
                    {
                        $output .= '
                        <li><a class="dropdown-item" href="#">'.$row->nama_barang.'</a></li>
                        ';
                    }
                    $output .= '</ul>';
                    echo $output;
                }
            }

            public function barang_keluar()
            {
                $user = Auth::user();
                $barang_keluar = Barang_Keluar::with('barang')->get();
                $barang = Barang::all();
                return view('admin.barang_keluar', compact( 'user','barang','barang_keluar'));
            }
        
            public function submit_barang_keluar(Request $req){
                $id = IdGenerator::generate(['table' => 'barang__keluars','field'=>'kode', 'length' => 7, 'prefix' =>'BK']);
                    { $validate = $req->validate([
                        'tanggal'=> 'required',
                        'barang'=> 'required',
                        'keterangan'=> 'required|max:150',
                        'jumlah'=> 'required|max:11',
    
                    ]);
                    $barang = Barang::where('nama_barang',$req->get('barang'))->value('kode');
                    if($barang == Null){
                        Session::flash('status', 'Nama Barang Tidak Ditemukan!!!');
                        return redirect()->route('admin.barangk');
                    }else{
                    $barang_keluar = new Barang_Keluar;
                    $barang_keluar->kode = $id;
                    $barang_keluar->tanggal = $req->get('tanggal');
                    $barang_keluar->kode_barang =  $barang;
                    $barang_keluar->keterangan =  $req->get('keterangan');
                    $barang_keluar->jumlah =  $req->get('jumlah');
                    $barang_keluar->save();
                    Session::flash('status', 'Tambah data Barang Keluar berhasil!!!');
                    return redirect()->route('admin.barangk');
                }}}
                public function getDataBarangKeluar($id)
                {
                    $barang_keluar= Barang_Keluar::find($id);
                    return response()->json($barang_keluar);
                }
            public function update_barang_keluar(Request $req){
                $barang_keluar= Barang_Keluar::find($req->get('id'));
                { $validate = $req->validate([
                    'tanggal'=> 'required',
                        'barang'=> 'required',
                        'keterangan'=> 'required|max:150',
                        'jumlah'=> 'required|max:11',
    
                ]);
                $barang_keluar->kode = $req->get('kode');
                $barang_keluar->tanggal = $req->get('tanggal');
                $barang_keluar->kode_barang =  $req->get('barang');
                $barang_keluar->keterangan =  $req->get('keterangan');
                $barang_keluar->jumlah =  $req->get('jumlah');
                $barang_keluar->save();
                Session::flash('status', 'Ubah data Barang Keluar berhasil!!!');
                return redirect()->route('admin.barangk');
                }}
        
                public function delete_barang_Keluar($id)
                {
                    $barang_keluar = Barang_Keluar::find($id);
                    $barang_keluar->delete();
            
                    $success = true;
                    $message = "Data Sarana Berhasil Dihapus";
                    return response()->json([
                        'success' => $success,
                        'message' => $message,
                    ]);
                }

            public function peminjaman()
            {
                $user = Auth::user();
                $peminjaman = Peminjaman::all();
                $barang = Barang::all();
                return view('admin.peminjaman', compact( 'user','barang','peminjaman'));
            }
        
            public function submit_peminjaman(Request $req){
                $id = IdGenerator::generate(['table' => 'peminjamen','field'=>'kode', 'length' => 7, 'prefix' =>'PJ']);
                    { $validate = $req->validate([
                        'tanggal_peminjaman'=> 'required',
                        'tanggal_pengembalian'=> 'required',
                        'barang'=> 'required',
                        'peminjam'=> 'required|max:100',
                        'keterangan'=> 'required|max:150',
                        'jumlah'=> 'required|max:11',
    
                    ]);
                    $barang_kode = Barang::where('nama_barang',$req->get('barang'))->value('kode');
                    $barang_nama = Barang::where('nama_barang',$req->get('barang'))->value('nama_barang');
                  if($barang_kode == Null){
                    Session::flash('status', 'Kode Barang Tidak Ditemukan!!!');
                    return redirect()->route('admin.peminjaman');
                  }else{
                    $peminjaman = new Peminjaman;
                    $peminjaman->kode = $id;
                    $peminjaman->tanggal_peminjaman = $req->get('tanggal_peminjaman');
                    $peminjaman->tanggal_pengembalian = $req->get('tanggal_pengembalian');
                    $peminjaman->kode_barang =  $barang_kode;
                    $peminjaman->nama_barang =  $barang_nama;
                    $peminjaman->peminjam = $req->get('peminjam');
                    $peminjaman->keterangan =  $req->get('keterangan');
                    $peminjaman->jumlah =  $req->get('jumlah');
                    $peminjaman->status =  Null;
                    $peminjaman->save();
                    Session::flash('status', 'Tambah data Peminjaman berhasil!!!');
                    return redirect()->route('admin.peminjaman');
                }}}
                public function getDataPeminjaman($id)
                {
                    $peminjaman= Peminjaman::find($id);
                    return response()->json($peminjaman);
                }
            public function update_peminjaman(Request $req){
                $peminjaman= Peminjaman::find($req->get('id'));
                { $validate = $req->validate([
                    'tanggal_peminjaman'=> 'required',
                    'tanggal_pengembalian'=> 'required',
                    'barang'=> 'required',
                    'peminjam'=> 'required|max:100',
                    'keterangan'=> 'required|max:150',
                    'jumlah'=> 'required|max:11',
    
                ]);
                $barang_kode = Barang::where('nama_barang',$req->get('barang'))->value('kode');
                $barang_nama = Barang::where('nama_barang',$req->get('barang'))->value('nama_barang');
                $peminjaman->kode = $req->get('kode');
                $peminjaman->tanggal_peminjaman = $req->get('tanggal_peminjaman');
                $peminjaman->tanggal_pengembalian = $req->get('tanggal_pengembalian');
                $peminjaman->kode_barang =  $barang_kode;
                $peminjaman->nama_barang =  $barang_nama;
                $peminjaman->peminjam = $req->get('peminjam');
                $peminjaman->keterangan =  $req->get('keterangan');
                $peminjaman->jumlah =  $req->get('jumlah');
                $peminjaman->status =  Null;
                $peminjaman->save();
                Session::flash('status', 'Ubah data Peminjaman berhasil!!!');
                return redirect()->route('admin.peminjaman');
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

                public function terima_peminjaman($id){
                    $terima = DB::table('peminjamen')->where('id', $id)->update(['status' => 1, ]);
                    Session::flash('status', 'Peminjaman Berhasil di Terima!!!');
                    return redirect()->back();
                }
                public function tolak_peminjaman($id){
                    $terima = DB::table('peminjamen')->where('id', $id)->update([ 'status' => 2,]);
                    Session::flash('status', 'Peminjaman Berhasil di Tolak!!!');
                    return redirect()->back();
                }

                public function pengembalian()
            {
                $user = Auth::user();
                $pengembalian = Pengembalian::all();
                $barang = Barang::all();
                return view('admin.pengembalian', compact( 'user','barang','pengembalian'));
            }
        
            public function submit_pengembalian(Request $req){
                $id = IdGenerator::generate(['table' => 'pengembalians','field'=>'kode', 'length' => 7, 'prefix' =>'PG']);
                    { $validate = $req->validate([
                        'tanggal_pengembalian'=> 'required',
                        'pengembalian'=> 'required',
                        'kondisi'=> 'required|max:10',
                        'jumlah'=> 'required|max:11',
                       
    
                    ]);
                    $barang = Peminjaman::where('kode',$req->get('pengembalian'))->value('nama_barang');
                    $peminjam = Peminjaman::where('kode',$req->get('pengembalian'))->value('peminjam');
                    $harga = Barang::where('nama_barang',$barang)->value('harga');
                    $denda = $harga * $req->get('jumlah');
                  if($barang == Null){
                    Session::flash('status', 'Kode Peminjaman Tidak Ditemukan!!!');
                    return redirect()->route('admin.peminjaman');
                  }else{
                    $pengembalian = new Pengembalian;
                    $pengembalian->kode = $id;
                    $pengembalian->tanggal_pengembalian = $req->get('tanggal_pengembalian');
                    $pengembalian->peminjam =  $peminjam;
                    $pengembalian->kode_peminjaman =  $req->get('pengembalian');
                    $pengembalian->kondisi =  $req->get('kondisi');
                    $pengembalian->denda = $denda;
                    $pengembalian->jumlah_rusak =  $req->get('jumlah');
                    $pengembalian->status =  0;
                    $pengembalian->save();
                    Session::flash('status', 'Tambah data Pengembalian berhasil!!!');
                    return redirect()->route('admin.peminjaman');
                }}}
                public function getDataPengembalian($id)
                {
                    $pengembalian= Pengembalian::find($id);
                    return response()->json($pengembalian);
                }
            public function update_pengembalian(Request $req){
                $pengembalian= Pengembalian::find($req->get('id'));
                { $validate = $req->validate([
                    'tanggal_pengembalian'=> 'required',
                    'pengembalian'=> 'required',
                    'kondisi'=> 'required|max:10',
                    'jumlah'=> 'required|max:11',
                    'status'=> 'required|max:1',
    
                ]);
                $barang = Peminjaman::where('kode',$req->get('pengembalian'))->value('nama_barang');
                $peminjam = Peminjaman::where('kode',$req->get('pengembalian'))->value('peminjam');
                $nama = Peminjaman::where('kode',$req->get('pengembalian'))->value('peminjam');
                $harga = Barang::where('nama_barang',$barang)->value('harga');
                $denda = $harga * $req->get('jumlah');
                $pengembalian->kode = $req->get('kode');
                $pengembalian->tanggal_pengembalian = $req->get('tanggal_pengembalian');
                $pengembalian->kode_peminjaman =  $req->get('pengembalian');
                $pengembalian->peminjam =  $peminjam;
                $pengembalian->kondisi =  $req->get('kondisi');
                $pengembalian->denda = $denda;
                $pengembalian->jumlah_rusak =  $req->get('jumlah');
                $pengembalian->status = $req->get('status');
                $pengembalian->save();
                if($req->get('status')==1){
                     $bayar = new Denda;
                    $bayar->tanggal = $req->get('tanggal_pengembalian');
                    $bayar->kode_pengembalian =  $req->get('pengembalian');
                    $bayar->nama =  $nama;
                    $bayar->denda = $denda;
                    $bayar->save();}
               
                Session::flash('status', 'Ubah data Pengembalian berhasil!!!');
                return redirect()->route('admin.pengembalian');
                }}
        
                public function delete_pengembalian($id)
                {
                    $pengembalian = Pengembalian::find($id);
                    $pengembalian->delete();
            
                    $success = true;
                    $message = "Data Pengembalian Berhasil Dihapus";
                    return response()->json([
                        'success' => $success,
                        'message' => $message,
                    ]);
                }

                function fetch_pengembalian(Request $request)
                {
                    if($request->get('query'))
                    {
                        $query = $request->get('query');
                        $data = DB::table('peminjamen')
                            ->where('kode', 'LIKE', "%{$query}%")
                            ->orwhere('peminjam', 'LIKE', "%{$query}%")
                            ->get();
                        $output = '<ul class="dropdown-menu" style="display:block; position:relative;width:100%;">';
                        foreach($data as $row)
                        {
                            $output .= '
                            <li><a class="dropdown-item" href="#">'.$row->kode.'</a></li>
                            ';
                        }
                        $output .= '</ul>';
                        echo $output;
                    }
                }
                public function riwayat_denda()
                {
                    $user = Auth::user();
                    $denda = Denda::all();
                  
                    return view('admin.denda', compact( 'user','denda'));
                }

                public function laporan()
                {
                    $user = Auth::user();
                    $laporan = Laporan::all();
                    return view('admin.laporan', compact( 'user','laporan'));
                }
                public function submit_laporan(Request $req){
                    $id = IdGenerator::generate(['table' => 'laporans','field'=>'kode_laporan', 'length' => 7, 'prefix' =>'LP']);
                        { $validate = $req->validate([
                            'dari_tanggal'=> 'required',
                            'sampai_tanggal'=> 'required',
                        ]);
                        $laporan = new Laporan;
                        $laporan->kode_laporan = $id;
                        $laporan->dari_tanggal = $req->get('dari_tanggal');
                        $laporan->sampai_tanggal = $req->get('sampai_tanggal');
                        $laporan->save();
                        Session::flash('status', 'Tambah data Laporan berhasil!!!');
                        return redirect()->route('admin.laporan');
                    }}
                    public function getDataLaporan($id)
                    {
                        $laporan = Laporan::find($id);
                        return response()->json($laporan);
                    }
                public function update_laporan(Request $req){
                    $laporan= Laporan::find($req->get('id'));
                        { $validate = $req->validate([
                            'kode'=> 'required',
                            'dari_tanggal'=> 'required',
                            'sampai_tanggal'=> 'required',
                        ]);
                        $laporan->kode_laporan = $req->get('kode');
                        $laporan->dari_tanggal = $req->get('dari_tanggal');
                        $laporan->sampai_tanggal = $req->get('sampai_tanggal');
                        $laporan->save();
                        Session::flash('status', 'Ubah data Laporan berhasil!!!');
                        return redirect()->route('admin.laporan');
                    }}
                    public function delete_laporan($id)
                    {
                        $laporan = Laporan::find($id);
                        $laporan->delete();
                
                        $success = true;
                        $message = "Data Laporan Berhasil Dihapus";
                        return response()->json([
                            'success' => $success,
                            'message' => $message,
                        ]);
                    }
                    public function print($dari,$sampai)
                    {
                        $user = Auth::user();
                        $prasarana = Prasarana::whereBetween('tanggal', [$dari, $sampai])->get();
                        $sarana = Barang::whereBetween('tanggal', [$dari, $sampai])->get();
                        $tanggal = Carbon::parse($sampai)->format('d M Y ');
                        $pdf = PDF::loadview('admin.print',['prasarana'=>$prasarana,'sarana'=>$sarana,'tanggal'=>$tanggal]);
                        return $pdf->stream('laporan.pdf');
                    }

            public function data_user(){
                $user = Auth::user();
                $pengguna = User::with('roles')->get();
                $roles = Roles::all();
                return view('admin.pengguna', compact('user','pengguna','roles'));
            }
        
            public function submit_user(Request $req){
                { $validate = $req->validate([
                    'nama'=> 'required',
                    'username'=> 'required',
                    'email'=> 'required',
                    'password'=> 'required',
                    'roles_id'=> 'required',
                ]);
                
                $user = new User;
                $user->name = $req->get('nama');
                $user->username = $req->get('username');
                $user->email = $req->get('email');
                $user->password = Hash::make($req->get('password'));
                $user->roles_id = $req->get('roles_id');
                $user->email_verified_at = null;
                $user->remember_token = null;
                $user->save();
                Session::flash('status', 'Tambah data User berhasil!!!');
                return redirect()->route('admin.pengguna');
            }}
            public function getDataUser($id)
            {
                $user = User::find($id);
                return response()->json($user);
            }
            public function update_user(Request $req)
            { 
                $user= User::find($req->get('id'));
                { $validate = $req->validate([
                    'nama'=> 'required',
                    'username'=> 'required',
                    'email'=> 'required',
                    'password'=> 'required',
                    'roles_id'=> 'required',
                ]);
                $user->name = $req->get('nama');
                $user->username = $req->get('username');
                $user->email = $req->get('email');
                $user->password = Hash::make($req->get('password'));
                $user->roles_id = $req->get('roles_id');
                $user->email_verified_at = null;
                $user->remember_token = null;
                $user->save();
                Session::flash('status', 'Ubah data User berhasil!!!');
                return redirect()->route('admin.pengguna');
            }
            }
            public function delete_user($id)
            {
                $user = User::find($id);
                $user->delete();
        
                $success = true;
                $message = "Data Pengguna Berhasil Dihapus";
                return response()->json([
                    'success' => $success,
                    'message' => $message,
                ]);
            }

            public function peminjaman_ruangan()
            {
                $user = Auth::user();
                $PinjamRuangan = PinjamRuangan::all();
                $ruangan = Ruangan::all();
                return view('admin.peminjaman_ruangan', compact( 'user','ruangan','PinjamRuangan'));
            }
        
            public function submit_peminjaman_ruangan(Request $req){
                $id = IdGenerator::generate(['table' => 'pinjam_ruangans','field'=>'kode', 'length' => 7, 'prefix' =>'PR']);
                    { $validate = $req->validate([
                        'tanggal_peminjaman'=> 'required',
                        'tanggal_pengembalian'=> 'required',
                        'ruangan'=> 'required',
                        'peminjam'=> 'required|max:100',
                        'keterangan'=> 'required|max:150',
    
                    ]);
                    
                    $ruangan_nama = Ruangan::where('nama',$req->get('ruangan'))->value('nama');
                    $ruangan_kode = Ruangan::where('nama',$ruangan_nama)->value('kode');
                  if($ruangan_nama == Null){
                    Session::flash('status', 'Kode Ruangan Tidak Ditemukan!!!');
                    return redirect()->route('admin.peminjaman.ruangan');
                  }else{
                    $PRuangan = new PinjamRuangan;
                    $PRuangan->kode = $id;
                    $PRuangan->dari_tanggal = $req->get('tanggal_peminjaman');
                    $PRuangan->sampai_tanggal = $req->get('tanggal_pengembalian');
                    $PRuangan->kode_ruangan =  $ruangan_kode;
                    $PRuangan->nama_ruangan =  $ruangan_nama;
                    $PRuangan->peminjam = $req->get('peminjam');
                    $PRuangan->keterangan =  $req->get('keterangan');
                    $PRuangan->status =  Null;
                    $PRuangan->save();
                    Session::flash('status', 'Tambah data Peminjaman Ruangan berhasil!!!');
                    return redirect()->route('admin.peminjaman.ruangan');
                }}}
                public function getDataPeminjamanRuangan($id)
                {
                    $PRuangan= PinjamRuangan::find($id);
                    return response()->json($PRuangan);
                }
            public function update_peminjaman_ruangan(Request $req){
                $peminjaman= Peminjaman::find($req->get('id'));
                { $validate = $req->validate([
                    'tanggal_peminjaman'=> 'required',
                    'tanggal_pengembalian'=> 'required',
                    'ruangan'=> 'required',
                    'peminjam'=> 'required|max:100',
                    'keterangan'=> 'required|max:150',
                    'jumlah'=> 'required|max:11',
    
                ]);
                $ruangan_kode = Ruangan::where('kode',$req->get('ruangan'))->value('kode');
                $ruangan_nama = Barang::where('nama',$req->get('ruangan'))->value('nama');
                $PRuangan->kode = $req->get('kode');
                $PRuangan->dari_tanggal = $req->get('tanggal_peminjaman');
                $PRuangan->sampai_tanggal = $req->get('tanggal_pengembalian');
                $PRuangan->kode_ruangan =  $ruangan_kode;
                $PRuangan->nama_ruangan =  $ruangan_nama;
                $PRuangan->peminjam = $req->get('peminjam');
                $PRuangan->keterangan =  $req->get('keterangan');
                $PRuangan->status =  Null;
                $PRuangan->save();
                Session::flash('status', 'Ubah data Peminjaman Ruangan berhasil!!!');
                return redirect()->route('admin.peminjaman.ruangan');
                }}
        
                public function delete_peminjaman_ruangan($id)
                {
                    $PRuangan = PinjamRuangan::find($id);
                    $PRuangan->delete();
            
                    $success = true;
                    $message = "Data Peminjaman Ruangan Berhasil Dihapus";
                    return response()->json([
                        'success' => $success,
                        'message' => $message,
                    ]);
                }

                public function terima_peminjaman_ruangan($id){
                    $terima = DB::table('pinjam_ruangans')->where('id', $id)->update(['status' => 1, ]);
                    Session::flash('status', 'Peminjaman Ruangan Berhasil di Terima!!!');
                    return redirect()->back();
                }
                public function tolak_peminjaman_ruangan($id){
                    $terima = DB::table('pinjam_ruangans')->where('id', $id)->update([ 'status' => 2,]);
                    Session::flash('status', 'Peminjaman Ruangan Berhasil di Tolak!!!');
                    return redirect()->back();
                }

                public function pengembalian_ruangan()
            {
                $user = Auth::user();
                $PRuangan = PengembalianRuangan::with('ruangan')->get();
                $ruangan = Ruangan::all();
                return view('admin.pengembalian_ruangan', compact( 'user','ruangan','PRuangan'));
            }
        
            public function submit_pengembalian_ruangan(Request $req){
                $id = IdGenerator::generate(['table' => 'pengembalian_ruangans','field'=>'kode', 'length' => 7, 'prefix' =>'RP']);
                    { $validate = $req->validate([
                        'tanggal_pengembalian'=> 'required',
                        'pengembalian'=> 'required',
                        'kondisi'=> 'required|max:10',
                       
    
                    ]);
                    $barang = PinjamRuangan::where('kode',$req->get('pengembalian'))->value('kode');
                    $peminjam = PinjamRuangan::where('kode',$req->get('pengembalian'))->value('peminjam');
                  if($barang == Null){
                    Session::flash('status', 'Kode Peminjaman Tidak Ditemukan!!!');
                    return redirect()->route('admin.pengembalian.ruangan');
                  }else{
                    $PRuangan = new PengembalianRuangan;
                    $PRuangan->kode = $id;
                    $PRuangan->tanggal_kembali = $req->get('tanggal_pengembalian');
                    $PRuangan->peminjam =  $peminjam;
                    $PRuangan->kode_peminjaman_ruangan = $barang;
                    $PRuangan->kondisi =  $req->get('kondisi');
                    $PRuangan->save();
                    Session::flash('status', 'Tambah data Pengembalian Ruangan berhasil!!!');
                    return redirect()->route('admin.pengembalian.ruangan');
                }}}
                public function getDataPengembalianRuangan($id)
                {
                    $PRuangan= PengembalianRuangan::find($id);
                    return response()->json($PRuangan);
                }
            public function update_pengembalian_ruangan(Request $req){
                $PRuangan= PengembalianRuangan::find($req->get('id'));
                { $validate = $req->validate([
                    'tanggal_pengembalian'=> 'required',
                    'pengembalian'=> 'required',
                    'kondisi'=> 'required|max:10',
    
                ]);
                $barang = PinjamRuangan::where('kode',$req->get('pengembalian'))->value('kode');
                $peminjam = PinjamRuangan::where('kode',$req->get('pengembalian'))->value('peminjam');

                $PRuangan->kode = $req->get('kode');
                $PRuangan->tanggal_kembali = $req->get('tanggal_pengembalian');
                $PRuangan->peminjam =  $peminjam;
                $PRuangan->kode_peminjaman_ruangan =  $req->get('pengembalian');
                $PRuangan->kondisi =  $req->get('kondisi');
                $PRuangan->save();
               
                Session::flash('status', 'Ubah data Pengembalian berhasil!!!');
                return redirect()->route('admin.pengembalian.ruangan');
                }}
        
                public function delete_pengembalian_ruangan($id)
                {
                    $pengembalian = PengembalianRuangan::find($id);
                    $pengembalian->delete();
            
                    $success = true;
                    $message = "Data Pengembalian Ruangan Berhasil Dihapus";
                    return response()->json([
                        'success' => $success,
                        'message' => $message,
                    ]);
                }

                function fetch_pengembalian_ruangan(Request $request)
                {
                    if($request->get('query'))
                    {
                        $query = $request->get('query');
                        $data = DB::table('pinjam_ruangans')
                            ->where('kode', 'LIKE', "%{$query}%")
                            ->orwhere('peminjam', 'LIKE', "%{$query}%")
                            ->get();
                        $output = '<ul class="dropdown-menu" style="display:block; position:relative;width:100%;">';
                        foreach($data as $row)
                        {
                            $output .= '
                            <li><a class="dropdown-item" href="#">'.$row->kode.'</a></li>
                            ';
                        }
                        $output .= '</ul>';
                        echo $output;
                    }
                }
                function fetch_ruangan(Request $request)
                {
                    if($request->get('query'))
                    {
                        $query = $request->get('query');
                        $data = DB::table('ruangans')
                            ->where('kode', 'LIKE', "%{$query}%")
                            ->orwhere('nama', 'LIKE', "%{$query}%")
                            ->get();
                        $output = '<ul class="dropdown-menu" style="display:block; position:relative;width:100%;">';
                        foreach($data as $row)
                        {
                            $output .= '
                            <li><a class="dropdown-item" href="#">'.$row->nama.'</a></li>
                            ';
                        }
                        $output .= '</ul>';
                        echo $output;
                    }
                }

}
