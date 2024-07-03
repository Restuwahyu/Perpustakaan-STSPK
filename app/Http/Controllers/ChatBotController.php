<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\BukuService;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Incoming\Answer;
use Illuminate\Support\Facades\Session;

class ChatBotController extends Controller
{
    protected $bukuService;
    protected $pengarangService;
    protected $kategoriService;
    protected $peranService;
    protected $penerbitService;
    protected $klasifikasiService;
    protected $subyekService;
    protected $eksemplarService;
    protected $peminjamanBukuService;
    protected $bahasaService;
    protected $bukuPengarangPeranService;
    protected $bukuSubyekService;

    public function __construct(
        BukuService $bukuService,
        // PengarangService $pengarangService,
        // KategoriService $kategoriService,
        // PeranService $peranService,
        // PenerbitService $penerbitService,
        // KlasifikasiService $klasifikasiService,
        // SubyekService $subyekService,
        // EksemplarService $eksemplarService,
        // PeminjamanBukuService $peminjamanBukuService,
        // BahasaService $bahasaService,
        // BukuPengarangPeranService $bukuPengarangPeranService,
        // BukuSubyekService $bukuSubyekService,
    ) {
        $this->bukuService = $bukuService;
        // $this->kategoriService = $kategoriService;
        // $this->peranService = $peranService;
        // $this->pengarangService = $pengarangService;
        // $this->penerbitService = $penerbitService;
        // $this->klasifikasiService = $klasifikasiService;
        // $this->subyekService = $subyekService;
        // $this->eksemplarService = $eksemplarService;
        // $this->peminjamanBukuService = $peminjamanBukuService;
        // $this->bahasaService = $bahasaService;
        // $this->bukuPengarangPeranService = $bukuPengarangPeranService;
        // $this->bukuSubyekService = $bukuSubyekService;
    }

    public function handle()
    {
        $botman = app('botman');

        $botman->hears('{message}', function (BotMan $bot, $message) {
            $greetingRegex = '/(hi|hai|halo|hello)/i';
            $openingHoursRegex = '/(pukul|buka|jadwal|operasional|\bjam buka\b|\bjam operasional\b|\bjam layanan\b|\bjam pelayanan\b)/i';
            $registrationRegex = '/(daftar|mendaftar|\bdaftar anggota\b|\bdaftar member\b)/i';
            $costRegistrationRegex = '/(biaya|biayanya|tarif|harga|ongkos|bayar|\biaya pendaftaran\b)/i';
            $borrowBooksRegex = '/(pinjam|meminjam|\bpinjam buku\b|\bmeminjam buku\b)/i';
            $lengthLoanRegex = '/(durasi|periode|\brentang waktu\b|\blama pinjam\b|\blama peminjaman\b)/i';
            $extensionLoanRegex = '/(\bperpanjangan peminjaman\b|\bperpanjangan masa peminjaman\b|\bperpanjangan masa pinjam\b|\bperpanjangan buku\b)/i';
            $chargeLoanRegex = '/(keterlambatan|denda|telat|hukuman|\bdenda keterlambatan\b|\bdenda keterlambatan peminjaman\b|\bdenda keterlambatan peminjaman buku\b)/i';
            $catalogueRegex = '/(katalog|inventarisasi|\bjenis buku\b|\bkoleksi buku\b|\bkoleksi buku digital\b|\bakses koleksi buku digital\b)/i';
            $computerServiceRegex = '/(komputer|\blayanan komputer\b)/i';
            $recomendationsRegex = '/(rekomendasi|\brekomendasi buku\b)/i';
            $studyRoomRegex = '/(ruang|\btempat belajar\b|\bruang studi\b)/i';
            $donationBooksRegex = '/(donasi|sumbangan|\bsumbangan buku\b|\bmenyumbangan buku\b)/i';
            $printServiceRegex = '/(fotokopi|cetak|print|\blayanan cetak\b|\blayanan fotokopi\b|\blayanan fotocopy\b|\blayanan photocopy\b)/i';
            $bookLanguageRegex = '/(bahasa|asing|\bahasa buku\b|\bbahasa dalam buku\b|\bbahasa asing\b)/i';
            $journalAccessRegex = '/(jurnal|\bakses jurnal\b|\bakses jurnal online\b)/i';
            $researchAssistanceServicesRegex = '/(penelitian|\blayanan bantuan penelitan\b)/i';
            $disabledAccessRegex = '/(disabilitas|difabel|cacat|buta|tuli|\bakses untuk difabel\b)/i';
            $serviceRegex = '/(layanan|pelayanan|\bjenis layanan\b|\bjenis pelayanan\b)/i';
            $searchRegex = '/(pencarian|cari|\bcari buku\b|\bpencarian buku\b)/i';
            $suggestionRegex = '/(saran|permintaan|pembelian|pengadaan|\bpembelian buku\b|\bpengadaan buku\b)/i';
            $deliveryServiceRegex = '/(dikirim|di kirim|pengiriman|\blayanan pengiriman\b|\bbuku ke rumah\b)/i';
            $kidsBooksRegex = '/(anak-anak|\bbuku anak\b)/i';
            $firstAidServiceRegex = '/(pertolongan|\bpertolongan pertama\b)/i';
            $rareCollectionRegex = '/(langka|sejarah|bersejarah|\bkoleksi buku langka\b|\bkoleksi buku bersejarah\b)/i';
            $feedbackRegex = '/(masukan|keluhan|feedback|\bumpan balik\b)/i';

            if (session()->has('member')) {
                $name = session()->get('member.member_nama');
            } else {
                $name = Session::get('name');
            }

            if (preg_match($greetingRegex, $message)) {
                $bot->ask('Halo! Selamat datang di perpustakaan kami. Siapa nama Anda?', function (Answer $answer) use ($bot) {
                    $name = $answer->getText();
                    $this->say('Senang bertemu denganmu Kak ' . $name . ', Apa yang bisa saya bantu hari ini?');
                    Session::put('name', $name);
                });
            } elseif (preg_match($openingHoursRegex, $message)) {
                $bot->reply('Perpustakaan kami buka dari pukul 08.00 WIB - 16.00 WIB, Senin hingga Jumat. Kami juga mungkin memiliki jadwal operasional yang berbeda pada hari libur.');
            } else if (preg_match($registrationRegex, $message)) {
                if (!empty($name)) {
                    $bot->reply('Kak ' . $name . ' dapat mendaftar sebagai anggota perpustakaan dengan mengisi formulir pendaftaran.');
                } else {
                    $bot->reply('Anda dapat mendaftar sebagai anggota perpustakaan dengan mengisi formulir pendaftaran.');
                }
            } else if (preg_match($costRegistrationRegex, $message)) {
                $bot->reply('Untuk mendaftar sebagai anggota perpustakaan, tidak ada biaya yang perlu dikeluarkan.');
            } else if (preg_match($borrowBooksRegex, $message)) {
                $bot->ask('Apakah Kak ' . $name . ' sudah memiliki kartu anggota perpustakaan?', function (Answer $answer) use ($name) {
                    $answer = $answer->getText();
                    $answer = strtolower($answer);

                    if ($answer == 'ya' || $answer == 'sudah' || $answer == 'iya') {
                        if (!empty($name)) {
                            $this->say('Kak ' . $name . ' dapat melakukan pemesanan buku dan mengambilnya di perpustakaan dengan syarat buku tidak sedang dipinjam oleh anggota lain. Anda juga bisa melakukan peminjaman buku dengan bantuan petugas perpustakaan di meja layanan dengan membawa kartu anggota.');
                        } else {
                            $this->say('Anda dapat melakukan pemesanan buku dan mengambilnya di perpustakaan dengan syarat buku tidak sedang dipinjam oleh anggota lain. Anda juga bisa melakukan peminjaman buku dengan bantuan petugas perpustakaan di meja layanan dengan membawa kartu anggota.');
                        }
                    } elseif ($answer == 'tidak' || $answer == 'belum' || $answer == 'belum punya') {
                        $this->say('Untuk meminjam buku, Anda perlu memiliki kartu anggota. Anda dapat mendaftar menjadi anggota perpustakaan secara online dengan menekan tombol login di bagian kanan atas.');
                    } else {
                        $this->say('Maaf, saya tidak mengerti. Silakan jawab dengan "ya" atau "tidak".');
                    }
                });
            } else if (preg_match($lengthLoanRegex, $message)) {
                if (!empty($name)) {
                    $bot->reply('Kak ' . $name . ' dapat meminjam buku selama satu bulan. Namun, ada juga buku-buku tertentu yang memiliki masa pinjam yang berbeda.');
                } else {
                    $bot->reply('Anda dapat meminjam buku selama satu bulan. Namun, ada juga buku-buku tertentu yang memiliki masa pinjam yang berbeda.');
                }
            } else if (preg_match($extensionLoanRegex, $message)) {
                if (!empty($name)) {
                    $bot->reply('Kak ' . $name . ' dapat memperpanjang masa pinjam buku asalkan buku tersebut belum dipesan oleh anggota lain. <br><b>Perlu diingat bahwa perpanjangan masa pinjam hanya bisa dilakukan dengan datang ke perpustakaan dan menuju ke meja layanan dengan membawa kartu anggota.</b>');
                } else {
                    $bot->reply('Anda dapat memperpanjang masa pinjam buku asalkan buku tersebut belum dipesan oleh anggota lain. <br><b>Perlu diingat bahwa perpanjangan masa pinjam hanya bisa dilakukan dengan datang ke perpustakaan dan menuju ke meja layanan dengan membawa kartu anggota.</b>');
                }
            } else if (preg_match($chargeLoanRegex, $message)) {
                $bot->reply('Tidak, kami tidak menerapkan denda untuk keterlambatan pengembalian buku.');
            } else if (preg_match($catalogueRegex, $message)) {
                if (!empty($name)) {
                    $bot->reply('Kak ' . $name . ' dapat mengakses katalog online perpustakaan melalui situs web kami dan mencari buku berdasarkan judul, penulis, atau subjek tertentu.');
                } else {
                    $bot->reply('Anda dapat mengakses katalog online perpustakaan melalui situs web kami dan mencari buku berdasarkan judul, penulis, atau subjek tertentu.');
                }
            } else if (preg_match($computerServiceRegex, $message)) {
                $bot->reply('Kami memiliki komputer publik dan koneksi internet yang dapat digunakan oleh anggota perpustakaan.');
            } else if (preg_match($studyRoomRegex, $message)) {
                $bot->reply('Kami memiliki ruang studi yang dapat digunakan oleh anggota perpustakaan untuk belajar dan membaca.');
            } else if (preg_match($donationBooksRegex, $message)) {
                $bot->reply('Kami menerima sumbangan buku asalkan buku tersebut masih dalam kondisi baik dan sesuai dengan kebijakan koleksi perpustakaan.');
            } else if (preg_match($printServiceRegex, $message)) {
                $bot->reply('Kami tidak menyediakan layanan cetak atau fotokopi.');
            } else if (preg_match($bookLanguageRegex, $message)) {
                $bot->reply('Kami memiliki koleksi buku dalam berbagai bahasa, termasuk bahasa asing.');
            } else if (preg_match($recomendationsRegex, $message)) {
                if (!empty($name)) {
                    $bot->reply('Kami belum bisa memberikan rekomendasi buku untuk Kak ' . $name . '.');
                } else {
                    $bot->reply('Kami belum bisa memberikan rekomendasi buku untuk Anda.');
                }
            } else if (preg_match($journalAccessRegex, $message)) {
                $bot->reply('Untuk mengakses jurnal hanya bisa datang ke perpustakaan dengan membawa kartu anggota.');
            } else if (preg_match($researchAssistanceServicesRegex, $message)) {
                $bot->reply('Kami tidak memiliki petugas perpustakaan yang dapat membantu Anda dalam melakukan penelitian, menemukan sumber informasi, dan lain sebagainya.');
            } else if (preg_match($disabledAccessRegex, $message)) {
                $bot->reply('Kami sedang berusaha untuk menyediakan aksesibilitas bagi semua anggota, termasuk fasilitas untuk difabel seperti lift, akses tanpa tangga, dan buku dalam format braille atau audio.');
            } else if (preg_match($serviceRegex, $message)) {
                $bot->reply('Kami menyediakan beragam layanan, mulai dari peminjaman buku, akses internet, layanan referensi, hingga program-program budaya dan pendidikan.');
            } else if (preg_match($searchRegex, $message)) {
                if (!empty($name)) {
                    $bot->reply('Kak ' . $name . ', dapat menggunakan katalog online kami untuk mencari buku berdasarkan judul buku.');
                    $bot->reply('Kak ' . $name . ', juga dapat menggunakan fitur pencarian spesifik berdasarkan judul, pengarang, penerbit, atau subjek tertentu. Jika Anda tidak menemukan apa yang Anda cari, Anda juga bisa meminta bantuan petugas perpustakaan dengan cara langsung datang ke perpustakaan.');
                } else {
                    $bot->reply('Anda dapat menggunakan katalog online kami untuk mencari buku berdasarkan judul buku.');
                    $bot->reply('Anda juga dapat menggunakan fitur pencarian spesifik berdasarkan judul, pengarang, penerbit, atau subjek tertentu. Jika Anda tidak menemukan apa yang Anda cari, Anda juga bisa meminta bantuan petugas perpustakaan dengan cara langsung datang ke perpustakaan.');
                }
            } else if (preg_match($suggestionRegex, $message)) {
                $bot->reply('Kami sedang berusaha untuk pengadaan fitur mengajukan saran atau permintaan pembelian buku baru di perpustakaan.');
            } else if (preg_match($deliveryServiceRegex, $message)) {
                $bot->reply('Kami menyediakan layanan pengiriman buku ke rumah untuk anggota yang membutuhkan dengan syarat dan ketentuan tertentu.');
            } else if (preg_match($kidsBooksRegex, $message)) {
                $bot->reply('Kami tidak memiliki buku untuk edisi anak-anak.');
            } else if (preg_match($firstAidServiceRegex, $message)) {
                $bot->reply('Kami memiliki petugas yang dilatih untuk memberikan pertolongan pertama dalam keadaan darurat di dalam perpustakaan.');
            } else if (preg_match($rareCollectionRegex, $message)) {
                $bot->reply('Kami memiliki koleksi buku langka atau bersejarah yang dapat dilihat oleh publik dengan izin khusus dan hanya bisa dibaca di perpustakaan saja.');
            } else if (preg_match($feedbackRegex, $message)) {
                if (!empty($name)) {
                    $bot->reply('Kak ' . $name . ' dapat memberikan umpan balik atau keluhan tentang layanan perpustakaan melalui formulir umpan balik yang tersedia di perpustakaan atau melalui kontak yang tertera di situs web kami.');
                } else {
                    $bot->reply('Anda dapat memberikan umpan balik atau keluhan tentang layanan perpustakaan melalui formulir umpan balik yang tersedia di perpustakaan atau melalui kontak yang tertera di situs web kami.');
                }
            } else {
                $bot->ask('Apakah ingin lanjut bertanya? (Y/N)', function (Answer $answer) {
                    $answer = $answer->getText();
                    $answer = strtolower($answer);

                    if ($answer == 'y' || $answer == 'ya' || $answer == 'yes' || $answer == 'iya') {
                        $this->say('Maaf, saya masih belajar untuk memahami pertanyaan Anda. Silakan coba lagi dengan cara yang lebih spesifik.');
                    } elseif ($answer == 'n' || $answer == 'no' || $answer == 'tidak') {
                        Session::forget('name');
                        $this->say('Terima kasih sudah bertanya :)');
                    } else {
                        $this->say('Maaf, saya tidak mengerti. Silakan jawab dengan <b>"Ya"</b> atau <b>"Tidak"</b>.');
                    }
                });
            }
        });

        $botman->listen();
    }
}
