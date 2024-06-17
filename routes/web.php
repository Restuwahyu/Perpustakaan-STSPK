<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BahasaController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\BukuPengarangPeranController;
use App\Http\Controllers\ChatBotController;
use App\Http\Controllers\EksemplarController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\KlasifikasiController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PeminjamanBukuController;
use App\Http\Controllers\PenerbitController;
use App\Http\Controllers\PengarangController;
use App\Http\Controllers\PeranController;
use App\Http\Controllers\PesanBukuController;
use App\Http\Controllers\RiwayatBukuController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SubyekController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
 */

// Route::get('/', function () {
//     return view('welcome');
// });

// Halaman Member
Route::get('/', [BukuController::class, 'showListBuku'])->name('showListBuku');
Route::post('/detail', [BukuController::class, 'showListBukuDetail'])->name('showListBukuDetail');
Route::get('/search', [BukuController::class, 'searchBuku'])->name('searchBuku');
Route::match(['get', 'post'], '/chatbot', [ChatBotController::class, 'handle']);
Route::get('/email/verify/{token}', [EmailVerificationController::class, 'verify'])->name('verification.verify');
Route::get('/email/reset/{token}', [EmailVerificationController::class, 'resetPassword'])->name('reset.verify');

// Halaman Admin
// Route untuk menampilkan halaman login dan register
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::get('/forgot-password', [AuthController::class, 'showForgotForm'])->name('forgot');
Route::get('/ganti_passwords', [AuthController::class, 'showPasswordForm'])->name('password')->middleware('auth');

// Proses login dan register
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [MemberController::class, 'store'])->name('register');
Route::post('/forgot-password', [MemberController::class, 'forgotPasswordMember'])->name('forgot');
Route::put('/reset-password', [MemberController::class, 'gantiPasswordMember'])->name('ganti_password_member');
Route::put('/ganti-passwords', [AuthController::class, 'gantiPassword'])->name('gantiPasswords');

Route::post('/email-remainder', [MemberController::class, 'sentEmailRemainder'])->name('sentEmailRemainder');

Route::middleware(['memberSession'])->group(function () {
    // Route Dashboard Member
    Route::get('/dashboard_member', [MemberController::class, 'showDashboard'])->name('dashboardMember');
    Route::get('/ganti_password', [MemberController::class, 'showGantiPasswordMemberForm'])->name('showGantiPasswordMember');
    Route::get('/pemesanan', [PesanBukuController::class, 'index'])->name('showPemesananBuku');
    Route::put('/ganti_password', [MemberController::class, 'gantiPasswordMember'])->name('gantiPasswordMember');
    Route::post('/peminjaman', [MemberController::class, 'showPeminjaman'])->name('showPeminjamanDashboard');
    Route::post('/pemesanan', [PesanBukuController::class, 'store'])->name('pemesananBuku');
    Route::delete('/pemesanan', [PesanBukuController::class, 'delete'])->name('pemesananBuku.delete');

    Route::post('/logoutMember', [MemberController::class, 'logoutMember'])->name('logoutMember');
});

// Grup route yang memerlukan otentikasi
Route::middleware(['auth'])->group(function () {
    // Route Dashboard Admin
    Route::get('/dashboard', [HomeController::class, 'index'])->name('home');
    Route::post('/renewal', [HomeController::class, 'renewalMember'])->name('statusMemberDashboard');
    Route::post('/cetak', [HomeController::class, 'printData'])->name('printData');
    Route::get('/cetak', [HomeController::class, 'printData'])->name('printData');
    Route::post('/verifikasi', [PesanBukuController::class, 'verifikasi'])->name('verifikasi');
    Route::post('/selesai-transaksi', [PesanBukuController::class, 'selesaiTransaksi'])->name('pemesananBuku.selesai');

    // Route Ganti Password
    Route::get('/ganti-password', [UserController::class, 'showGantiPasswordForm'])->name('showGantiPassword');
    Route::put('/ganti-password', [UserController::class, 'gantiPassword'])->name('gantiPassword');

    // Menu Data User
    Route::prefix('user')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('showUser');
        Route::get('/tambah', [UserController::class, 'showStoreForm'])->name('tambahUser');
        Route::post('/tambah', [UserController::class, 'store'])->name('tambahUser');
        Route::post('/tambah-role', [UserController::class, 'storeRole'])->name('tambahRole');
        Route::post('/edit', [UserController::class, 'showUpdateForm'])->name('editUser');
        Route::put('/update', [UserController::class, 'update'])->name('updateUser');
        Route::put('/reset-password', [UserController::class, 'resetDefaultPasswordUser'])->name('resetDefaultPasswordUser');
        Route::post('/hapus-selected', [UserController::class, 'deleteSelectedUsers'])->name('deleteSelectedDataUsers');
    });

    // Menu Data Role User
    Route::prefix('user-role')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('showUserRole');
        Route::post('/tambah', [RoleController::class, 'store'])->name('tambahUserRole');
        Route::put('/update', [RoleController::class, 'update'])->name('updateUserRole');
        Route::post('/hapus-selected', [RoleController::class, 'deleteSelectedRoles'])->name('deleteSelectedUserRoles');
    });

    // Menu Data Member
    Route::prefix('member')->group(function () {
        Route::get('/', [MemberController::class, 'indexMember'])->name('showMember');
        Route::post('/peminjaman', [MemberController::class, 'showPeminjaman'])->name('showPeminjamanMember');
        Route::get('/kedaluwarsa', [MemberController::class, 'indexMemberNotActive'])->name('showExpMember');
        Route::get('/tambah', [MemberController::class, 'showStoreForm'])->name('tambahMember');
        Route::post('/tambah', [MemberController::class, 'store'])->name('tambahMember');
        Route::post('/renewal', [MemberController::class, 'renewalMember'])->name('statusMember');
        Route::post('/edit', [MemberController::class, 'showUpdateForm'])->name('editMember');
        Route::put('/update', [MemberController::class, 'update'])->name('updateMember');
        Route::put('/reset-password', [MemberController::class, 'resetDefaultPasswordMember'])->name('resetDefaultPasswordMember');
        Route::post('/hapus-selected', [MemberController::class, 'deleteSelectedMembers'])->name('deleteSelectedDataMembers');
        Route::post('/cetak-selected', [MemberController::class, 'printSelectedMembers'])->name('printSelectedDataMembers');
    });

    // Menu Data Penerbit
    Route::prefix('penerbit')->group(function () {
        Route::get('/json', [PenerbitController::class, 'json'])->name('json');
        Route::get('/', [PenerbitController::class, 'index'])->name('showPenerbit');
        Route::post('/tambah', [PenerbitController::class, 'store'])->name('tambahPenerbit');
        Route::put('/update', [PenerbitController::class, 'update'])->name('updatePenerbit');
        Route::post('/hapus-selected', [PenerbitController::class, 'deleteSelectedPenerbits'])->name('deleteSelectedPenerbits');
    });

    // Menu Data Pengarang
    Route::prefix('pengarang')->group(function () {
        Route::get('/json', [PengarangController::class, 'json'])->name('json');
        Route::get('/', [PengarangController::class, 'index'])->name('showPengarang');
        Route::post('/peran', [BukuPengarangPeranController::class, 'index'])->name('showPeranPengarang');
        Route::post('/tambah-peran-pengarang', [PengarangController::class, 'storePeranPengarang'])->name('storePeranPengarang');
        Route::post('/tambah', [PengarangController::class, 'store'])->name('tambahPengarang');
        Route::post('/tambah-kategori', [PengarangController::class, 'storeKategori'])->name('tambahKategoriPengarangg');
        Route::post('/tambah-peran', [PengarangController::class, 'storePeran'])->name('tambahPeranPengarangg');
        Route::put('/update', [PengarangController::class, 'update'])->name('updatePengarang');
        Route::put('/update-peran', [PengarangController::class, 'updatePeranPengarang'])->name('updatePeranPengarang');
        Route::post('/hapus-selected', [PengarangController::class, 'deleteSelectedPengarangs'])->name('deleteSelectedPengarangs');
        Route::post('/hapus-peran-selected', [PengarangController::class, 'deleteSelectedPeranPengarangs'])->name('deleteSelectedPeranPengarangs');
    });

    // Menu Data Kategori
    Route::prefix('pengarang-kategori')->group(function () {
        Route::get('/', [KategoriController::class, 'index'])->name('showKategori');
        Route::post('/tambah', [KategoriController::class, 'store'])->name('tambahKategori');
        Route::put('/update', [KategoriController::class, 'update'])->name('updateKategori');
        Route::post('/hapus-selected', [KategoriController::class, 'deleteSelectedKategoris'])->name('deleteSelectedKategoris');
    });

    // Menu Data Peran
    Route::prefix('pengarang-peran')->group(function () {
        Route::get('/', [PeranController::class, 'index'])->name('showPeran');
        Route::post('/tambah', [PeranController::class, 'store'])->name('tambahPeran');
        Route::put('/update', [PeranController::class, 'update'])->name('updatePeran');
        Route::post('/hapus-selected', [PeranController::class, 'deleteSelectedPerans'])->name('deleteSelectedPerans');
    });

    // Menu Data Buku
    Route::prefix('buku')->group(function () {
        Route::get('/json', [BukuController::class, 'json'])->name('json');
        Route::get('/', [BukuController::class, 'index'])->name('showBuku');
        Route::get('/tambah', [BukuController::class, 'showStoreForm'])->name('tambahBuku');
        Route::post('/tambah', [BukuController::class, 'store'])->name('tambahBuku');
        Route::post('/tambah-sementara', [BukuController::class, 'storeBukuSementara'])->name('tambahBukuSementara');
        Route::post('/tambah-pengarang', [BukuController::class, 'storePengarang'])->name('tambahPengarangBuku');
        Route::post('/tambah-penerbit', [BukuController::class, 'storePenerbit'])->name('tambahPenerbitBuku');
        Route::post('/tambah-klasifikasi', [BukuController::class, 'storeKlasifikasi'])->name('tambahKlasifikasiBuku');
        Route::post('/tambah-subyek', [BukuController::class, 'storeSubyek'])->name('tambahSubyekBuku');
        Route::post('/tambah-kategori', [BukuController::class, 'storeKategori'])->name('tambahKategoriPengarang');
        Route::post('/tambah-peran', [BukuController::class, 'storePeran'])->name('tambahPeranPengarang');
        Route::post('/tambah-bahasa', [BukuController::class, 'storeBahasa'])->name('tambahBahasaBuku');
        Route::get('/edit', [BukuController::class, 'showUpdateForm'])->name('editBuku.get');
        Route::post('/edit', [BukuController::class, 'showUpdateForm'])->name('editBuku');
        Route::put('/update', [BukuController::class, 'update'])->name('updateBuku');
        Route::post('/hapus-selected', [BukuController::class, 'deleteSelectedBukus'])->name('deleteSelectedBukus');
        Route::get('/pengarang', [BukuController::class, 'getPengarangData'])->name('buku.pengarang');
        Route::get('/penerbit', [BukuController::class, 'getPenerbitData'])->name('buku.penerbit');
    });

    // Menu Data Eksemplar
    Route::prefix('eksemplar')->group(function () {
        Route::get('/json', [EksemplarController::class, 'json'])->name('json');
        Route::get('/', [EksemplarController::class, 'index'])->name('showEksemplar');
        Route::post('/tambah', [EksemplarController::class, 'store'])->name('tambahEksemplar');
        Route::put('/update', [EksemplarController::class, 'update'])->name('updateEksemplar');
        Route::post('/cetak-selected', [EksemplarController::class, 'printSelectedEksemplars'])->name('printSelectedDataEksemplars');
    });

    // Menu Data Subyek
    Route::prefix('buku-subyek')->group(function () {
        Route::get('/', [SubyekController::class, 'index'])->name('showSubyek');
        Route::post('/tambah', [SubyekController::class, 'store'])->name('tambahSubyek');
        Route::put('/update', [SubyekController::class, 'update'])->name('updateSubyek');
        Route::post('/hapus-selected', [SubyekController::class, 'deleteSelectedSubyeks'])->name('deleteSelectedSubyeks');
    });

    // Menu Data Bahasa
    Route::prefix('buku-bahasa')->group(function () {
        Route::get('/', [BahasaController::class, 'index'])->name('showBahasa');
        Route::post('/tambah', [BahasaController::class, 'store'])->name('tambahBahasa');
        Route::put('/update', [BahasaController::class, 'update'])->name('updateBahasa');
        Route::post('/hapus-selected', [BahasaController::class, 'deleteSelectedBahasas'])->name('deleteSelectedBahasas');
    });

    // Menu Data Klasifikasi
    Route::prefix('buku-klasifikasi')->group(function () {
        Route::get('/', [KlasifikasiController::class, 'index'])->name('showKlasifikasi');
        Route::post('/tambah', [KlasifikasiController::class, 'store'])->name('tambahKlasifikasi');
        Route::put('/update', [KlasifikasiController::class, 'update'])->name('updateKlasifikasi');
        Route::post('/hapus-selected', [KlasifikasiController::class, 'deleteSelectedKlasifikasis'])->name('deleteSelectedKlasifikasis');
    });

    // Menu Data RiwayatBuku
    Route::get('riwayat-buku', [RiwayatBukuController::class, 'index'])->name('showRiwayatBuku');

    Route::get('pengembalian-buku', [RiwayatBukuController::class, 'show'])->name('showPengembalianBuku');
    Route::post('pengembalian-buku', [RiwayatBukuController::class, 'pengembalianBuku'])->name('pengembalianBuku');

    // Menu Data Pinjam Buku
    Route::prefix('peminjaman-buku')->group(function () {
        Route::get('/', [PeminjamanBukuController::class, 'show'])->name('showPinjamBuku');
        Route::post('/', [PeminjamanBukuController::class, 'store']);
        Route::get('/member', [PeminjamanBukuController::class, 'showStoreForm'])->name('tambahPinjamBuku');
        Route::post('/tambah-sementara', [PeminjamanBukuController::class, 'tambahPeminjamanSementara'])->name('tambahPeminjamanSementara');
        Route::delete('/hapus-sementara', [PeminjamanBukuController::class, 'deletePeminjamanSementara'])->name('deletePeminjamanSementara');
        Route::post('/selesai', [PeminjamanBukuController::class, 'selesaiTransaksi'])->name('selesaiTransaksi');
        Route::put('/edit', [PeminjamanBukuController::class, 'update'])->name('updatePinjamBuku');
        Route::post('/hapus', [PeminjamanBukuController::class, 'destroy'])->name('deletePinjamBuku');
    });

    // Route untuk logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
