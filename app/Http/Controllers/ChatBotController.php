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
                // $bot->reply('Kak ' . $name . ' dapat mendaftar sebagai anggota perpustakaan dengan mengisi formulir pendaftaran.');
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
    // $botman->hears('biaya pendaftaran', function (BotMan $bot) {
    //     $bot->reply('Untuk mendaftar sebagai anggota perpustakaan, tidak ada biaya yang perlu dikeluarkan.');
    // });

    // $botman->hears('meminjam buku', function (BotMan $bot) {
    //     $bot->reply('Anda dapat meminjam buku dengan bantuan petugas perpustakaan di meja layanan dengan membawa kartu anggota.');
    // });
    // $botman->hears('meminjam buku', function (BotMan $bot) {
    //     $bot->ask('Apakah Anda sudah memiliki kartu anggota perpustakaan?', function (Answer $answer) {
    //         $answer = $answer->getText();
    //         $answer = strtolower($answer);
    //         if ($answer == 'ya') {
    //             $this->say('Anda dapat melakukan pemesanan buku dan mengambilnya di perpustakaan dengan syarat buku tidak sedang dipinjam oleh anggota lain. Anda juga bisa melakukan peminjaman buku dengan bantuan petugas perpustakaan di meja layanan dengan membawa kartu anggota.');
    //         } elseif ($answer == 'tidak') {
    //             $this->say('Untuk meminjam buku, Anda perlu memiliki kartu anggota. Anda dapat mendaftar menjadi anggota perpustakaan secara online dengan menekan tombol login di bagian kanan atas.');
    //             // $message = 'Untuk meminjam buku, Anda perlu memiliki kartu anggota. Anda dapat mendaftar menjadi anggota perpustakaan secara online dengan menekan tombol login di bawah ini.';

    //             // $this->say($message, [
    //             //     Button::create('Of course')->value('yes'),
    //             //     Button::create('Hell no!')->value('no'),
    //             // ]);
    //         } else {
    //             $this->say('Maaf, saya tidak mengerti. Silakan jawab dengan "ya" atau "tidak".');
    //         }
    //     });
    // });

    // $botman->hears('masa pinjam buku', function (BotMan $bot) {
    //     $bot->reply('Biasanya, Anda dapat meminjam buku selama satu bulan. Namun, ada juga buku-buku tertentu yang memiliki masa pinjam yang berbeda.');
    // });

    // $botman->hears('perpanjang masa pinjam', function (BotMan $bot) {
    //     $bot->reply('Anda dapat memperpanjang masa pinjam buku asalkan buku tersebut belum dipesan oleh anggota lain.');
    // });

    // $botman->hears('denda keterlambatan', function (BotMan $bot) {
    //     $bot->reply('Tidak, kami tidak menerapkan denda untuk keterlambatan pengembalian buku.');
    // });

    // $botman->hears('katalog online', function (BotMan $bot) {
    //     $bot->reply('Anda dapat mengakses katalog online perpustakaan melalui situs web kami dan mencari buku berdasarkan judul, penulis, atau subjek tertentu.');
    // });

    // $botman->hears('layanan komputer', function (BotMan $bot) {
    //     $bot->reply('Kami memiliki komputer publik dan koneksi internet yang dapat digunakan oleh anggota perpustakaan.');
    // });

    // $botman->hears('ruang studi', function (BotMan $bot) {
    //     $bot->reply('Kami memiliki ruang studi dan ruang baca yang dapat digunakan oleh anggota perpustakaan untuk belajar dan membaca.');
    // });

    // $botman->hears('acara atau program', function (BotMan $bot) {
    //     $bot->reply('Kami secara rutin mengadakan berbagai acara dan program, mulai dari diskusi buku hingga lokakarya kreatif. Anda dapat melihat jadwal acara kami di situs web atau papan pengumuman perpustakaan.');
    // });

    // $botman->hears('sumbangan buku', function (BotMan $bot) {
    //     $bot->reply('Kami menerima sumbangan buku asalkan buku tersebut masih dalam kondisi baik dan sesuai dengan kebijakan koleksi perpustakaan.');
    // });

    // $botman->hears('rekomendasi buku', function (BotMan $bot) {
    //     $bot->reply('Anda dapat meminta rekomendasi buku dari petugas perpustakaan atau menggunakan layanan katalog online untuk menemukan buku berdasarkan minat Anda.');
    // });

    // $botman->hears('koleksi buku digital', function (BotMan $bot) {
    //     $bot->reply('Kami memiliki koleksi buku digital yang dapat diakses oleh anggota perpustakaan melalui platform online kami.');
    // });

    // $botman->hears('layanan cetak atau fotokopi', function (BotMan $bot) {
    //     $bot->reply('Kami menyediakan layanan cetak dan fotokopi dengan biaya tertentu.');
    // });

    // $botman->hears('buku dalam bahasa asing', function (BotMan $bot) {
    //     $bot->reply('Kami memiliki koleksi buku dalam berbagai bahasa, termasuk bahasa asing.');
    // });

    // $botman->hears('akses jurnal ilmiah', function (BotMan $bot) {
    //     $bot->reply('Anggota perpustakaan kami dapat mengakses jurnal ilmiah dan database melalui komputer di perpustakaan atau melalui akses jarak jauh menggunakan kartu anggota.');
    // });

    // $botman->hears('koleksi buku langka', function (BotMan $bot) {
    //     $bot->reply('Kami memiliki koleksi buku langka dan khusus yang dapat diakses oleh anggota perpustakaan dengan izin khusus.');
    // });

    // $botman->hears('layanan bantuan penelitian', function (BotMan $bot) {
    //     $bot->reply('Kami memiliki petugas perpustakaan yang siap membantu anggota dalam melakukan penelitian, menemukan sumber informasi, dan lain sebagainya.');
    // });

    // $botman->hears('akses untuk difabel', function (BotMan $bot) {
    //     $bot->reply('Kami berusaha untuk menyediakan aksesibilitas bagi semua anggota, termasuk fasilitas untuk difabel seperti lift, akses tanpa tangga, dan buku dalam format braille atau audio.');
    // });

    // $botman->hears('jenis layanan', function (BotMan $bot) {
    //     $bot->reply('Kami menyediakan beragam layanan, mulai dari peminjaman buku, akses internet, layanan referensi, hingga program-program budaya dan pendidikan.');
    // });

    // $botman->hears('cara mencari buku', function (BotMan $bot) {
    //     $bot->reply('Anda dapat menggunakan katalog online kami untuk mencari buku berdasarkan judul, penulis, atau subjek tertentu. Jika Anda tidak menemukan apa yang Anda cari, Anda juga bisa meminta bantuan petugas perpustakaan.');
    // });

    // $botman->hears('layanan peminjaman buku antar perpustakaan', function (BotMan $bot) {
    //     $bot->reply('Kami menyediakan layanan peminjaman buku antar perpustakaan jika buku yang Anda butuhkan tidak tersedia di perpustakaan kami.');
    // });

    // $botman->hears('saran atau permintaan pembelian buku baru', function (BotMan $bot) {
    //     $bot->reply('Anda dapat mengajukan saran atau permintaan pembelian buku baru dengan mengisi formulir yang tersedia di perpustakaan atau melalui situs web kami.');
    // });

    // $botman->hears('ruang pertemuan atau auditorium', function (BotMan $bot) {
    //     $bot->reply('Kami memiliki ruang pertemuan atau auditorium yang dapat disewa untuk acara-acara besar atau pertemuan komunitas.');
    // });

    // $botman->hears('akses koleksi buku digital dari rumah', function (BotMan $bot) {
    //     $bot->reply('Anda dapat mengakses koleksi buku digital perpustakaan melalui situs web kami menggunakan akun anggota Anda.');
    // });

    // $botman->hears('layanan pengiriman buku ke rumah', function (BotMan $bot) {
    //     $bot->reply('Kami menyediakan layanan pengiriman buku ke rumah untuk anggota yang membutuhkan dengan syarat dan ketentuan tertentu.');
    // });

    // $botman->hears('koleksi majalah atau koran', function (BotMan $bot) {
    //     $bot->reply('Kami memiliki koleksi majalah dan koran yang dapat dibaca di perpustakaan atau dipinjam untuk dibawa pulang.');
    // });

    // $botman->hears('koleksi e-book dan audiobook', function (BotMan $bot) {
    //     $bot->reply('Kami memiliki koleksi e-book dan audiobook yang dapat diunduh melalui platform digital perpustakaan.');
    // });

    // $botman->hears('bantuan teknis untuk layanan digital', function (BotMan $bot) {
    //     $bot->reply('Anda dapat mendapatkan bantuan teknis dengan menghubungi petugas perpustakaan di meja layanan atau melalui layanan dukungan teknis yang tersedia di situs web kami.');
    // });

    // $botman->hears('ruang khusus untuk diskusi kelompok', function (BotMan $bot) {
    //     $bot->reply('Kami memiliki ruang khusus yang dapat dipesan untuk diskusi kelompok, penelitian, atau proyek kolaboratif.');
    // });

    // $botman->hears('program untuk anak-anak', function (BotMan $bot) {
    //     $bot->reply('Kami memiliki program-program khusus untuk anak-anak seperti cerita waktu, klub buku anak, dan kegiatan kreatif lainnya.');
    // });

    // $botman->hears('layanan bantuan literasi bagi orang dewasa', function (BotMan $bot) {
    //     $bot->reply('Kami menyediakan layanan bantuan literasi bagi orang dewasa yang ingin meningkatkan keterampilan membaca dan menulis mereka.');
    // });

    // $botman->hears('informasi tentang acara atau program terbaru', function (BotMan $bot) {
    //     $bot->reply('Anda dapat mendapatkan informasi tentang acara atau program terbaru di perpustakaan melalui situs web kami, media sosial, atau buletin informasi yang dikirimkan kepada anggota.');
    // });

    // $botman->hears('koleksi buku untuk studi spesifik', function (BotMan $bot) {
    //     $bot->reply('Kami memiliki koleksi buku yang beragam, termasuk untuk studi spesifik seperti hukum, kedokteran, teknik, dan banyak lagi.');
    // });

    // $botman->hears('layanan pertolongan pertama', function (BotMan $bot) {
    //     $bot->reply('Kami memiliki petugas yang dilatih untuk memberikan pertolongan pertama dalam keadaan darurat di dalam perpustakaan.');
    // });

    // $botman->hears('program untuk komunitas lokal', function (BotMan $bot) {
    //     $bot->reply('Kami menyelenggarakan berbagai program untuk komunitas lokal, termasuk pertunjukan seni, ceramah, dan acara budaya lainnya.');
    // });

    // $botman->hears('koleksi buku langka atau bersejarah', function (BotMan $bot) {
    //     $bot->reply('Kami memiliki koleksi buku langka atau bersejarah yang dapat dilihat oleh publik dengan izin khusus.');
    // });

    // $botman->hears('layanan terjemahan atau bantuan bahasa', function (BotMan $bot) {
    //     $bot->reply('Kami menyediakan layanan terjemahan atau bantuan bahasa bagi anggota yang membutuhkan.');
    // });

    // $botman->hears('memberikan umpan balik atau keluhan', function (BotMan $bot) {
    //     $bot->reply('Anda dapat memberikan umpan balik atau keluhan tentang layanan perpustakaan melalui formulir umpan balik yang tersedia di perpustakaan atau melalui kontak yang tertera di situs web kami.');
    // });

    // $botman->fallback(function (BotMan $bot) {
    //     $bot->reply('Maaf, saya masih belajar untuk memahami pertanyaan Anda. Silakan coba lagi dengan cara yang lebih spesifik.');
    // });

    //     $botman->listen();
    // }

    // public function handle()
    // {
    //     $botman = app('botman');

    //     $botman->hears('{message}', function ($botman, $message) {
    //         if ($message == 'hi' || $message == 'hello' || $message == 'halo') {
    //             $this->askName($botman);
    //         } elseif ($message == 'cari buku') {
    //             $this->askForSearchType($botman);
    //         } else {
    //             $botman->reply('Maaf, saya tidak mengerti. Silakan coba lagi.');
    //         }
    //     });

    //     $botman->listen();
    // }

    // public function askName($botman)
    // {
    // $botman->ask('Halo! Selamat datang di perpustakaan kami. Siapa nama Anda?', function (Answer $answer) {
    //     $name = $answer->getText();
    //     $this->say('Senang bertemu dengan Anda, Kak ' . $name . ' . Apa yang bisa saya bantu hari ini?');
    // });
    // }

    // public function askForSearchType($botman)
    // {
    //     $botman->ask('Apakah Anda ingin mencari buku berdasarkan judul atau pengarang?', function (Answer $answer) {
    //         $searchType = $answer->getText();
    //         if ($searchType == 'judul') {
    //             $botman->reply('Sedang mencari buku dengan judul "' . $searchType . '"...');
    //         }
    //         // if ($searchType == 'judul') {
    //         //     $this->say('Maaf, saya hanya bisa membantu mencari berdasarkan judul atau pengarang. Silakan coba lagi.');
    //         //     // $this->askForBookTitle($botman);
    //         // } elseif ($searchType == 'pengarang') {
    //         //     $this->askForAuthorName($botman);
    //         // } else {
    //         //     $this->say('Maaf, saya hanya bisa membantu mencari berdasarkan judul atau pengarang. Silakan coba lagi.');
    //         //     $this->askForSearchType();
    //         // }
    //     });
    // }
    // public function askForBookTitle($botman)
    // {
    //     $botman->ask('Silakan tuliskan judul buku yang ingin Anda cari:', function (Answer $answer) use ($botman) {
    //         $title = $answer->getText();
    //         // Lakukan pencarian berdasarkan judul buku
    //         $botman->reply('Sedang mencari buku dengan judul "' . $title . '"...');
    //     });
    // }
    // // public function askForBookTitle($botman)
    // // {
    // //     $botman->ask('Silakan tuliskan judul buku yang ingin Anda cari:', function (Answer $answer) {
    // //         // $title = $answer->getText();
    // //         // $query = $this->bukuService->findBukus($title)->orderby('updated_at', 'desc')->limit(5)->get();
    // //         // dd($query);
    // //         // $this->say('Sedang mencari buku dengan judul "' . $title . '"...');
    // //     });
    // // }

    // public function askForAuthorName($botman)
    // {
    //     $botman->ask('Silakan tuliskan nama pengarang yang ingin Anda cari:', function (Answer $answer) {
    //         $author = $answer->getText();
    //         // Lakukan pencarian berdasarkan nama pengarang
    //         $this->say('Sedang mencari buku oleh pengarang dengan nama "' . $author . '"...');
    //     });
    // }
}
