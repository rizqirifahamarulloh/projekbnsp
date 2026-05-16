<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

// [BNSP: Menggunakan Basis Data]
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ═══════════════════════════════════════════
        // 1. AKUN DEMO (Admin & User)
        // ═══════════════════════════════════════════
        User::create([
            'name'     => 'Admin BookWise',
            'email'    => 'admin@bookwise.test',
            'password' => bcrypt('password'),
            'role'     => 'admin',
        ]);

        User::create([
            'name'     => 'John Doe',
            'email'    => 'user@bookwise.test',
            'password' => bcrypt('password'),
            'role'     => 'user',
        ]);

        // ═══════════════════════════════════════════
        // 2. KATEGORI (5 kategori)
        // ═══════════════════════════════════════════
        $categories = [
            ['name' => 'Fiksi',           'slug' => 'fiksi',           'description' => 'Novel, cerpen, dan karya fiksi lainnya'],
            ['name' => 'Non-Fiksi',       'slug' => 'non-fiksi',       'description' => 'Buku pengetahuan, biografi, dan referensi'],
            ['name' => 'Teknologi',       'slug' => 'teknologi',       'description' => 'Buku pemrograman, IT, dan teknologi terkini'],
            ['name' => 'Bisnis',          'slug' => 'bisnis',          'description' => 'Manajemen, keuangan, dan kewirausahaan'],
            ['name' => 'Seni & Budaya',   'slug' => 'seni-budaya',     'description' => 'Seni rupa, musik, sastra, dan budaya'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }

        // ═══════════════════════════════════════════
        // 3. BUKU SAMPEL (20 buku, 4 per kategori)
        // ═══════════════════════════════════════════
        $books = [
            // ── Fiksi (category_id: 1) ──
            [
                'category_id' => 1,
                'title'       => 'Laskar Pelangi',
                'author'      => 'Andrea Hirata',
                'publisher'   => 'Bentang Pustaka',
                'year'        => 2005,
                'price'       => 89000,
                'stock'       => 50,
                'description' => 'Novel inspiratif tentang perjuangan anak-anak di Belitung dalam meraih pendidikan.',
            ],
            [
                'category_id' => 1,
                'title'       => 'Bumi Manusia',
                'author'      => 'Pramoedya Ananta Toer',
                'publisher'   => 'Hasta Mitra',
                'year'        => 1980,
                'price'       => 95000,
                'stock'       => 35,
                'description' => 'Novel sejarah yang berlatar belakang era kolonial Belanda di Hindia Belanda.',
            ],
            [
                'category_id' => 1,
                'title'       => 'Perahu Kertas',
                'author'      => 'Dee Lestari',
                'publisher'   => 'Bentang Pustaka',
                'year'        => 2009,
                'price'       => 79000,
                'stock'       => 40,
                'description' => 'Kisah cinta dan mimpi dua anak muda yang bertemu di bangku kuliah.',
            ],
            [
                'category_id' => 1,
                'title'       => 'Negeri 5 Menara',
                'author'      => 'Ahmad Fuadi',
                'publisher'   => 'Gramedia',
                'year'        => 2009,
                'price'       => 85000,
                'stock'       => 45,
                'description' => 'Perjalanan seorang santri dari Sumatera Barat yang bermimpi menjelajah dunia.',
            ],

            // ── Non-Fiksi (category_id: 2) ──
            [
                'category_id' => 2,
                'title'       => 'Sapiens: Riwayat Singkat Umat Manusia',
                'author'      => 'Yuval Noah Harari',
                'publisher'   => 'Gramedia',
                'year'        => 2011,
                'price'       => 120000,
                'stock'       => 30,
                'description' => 'Eksplorasi mendalam tentang sejarah manusia dari zaman prasejarah hingga modern.',
            ],
            [
                'category_id' => 2,
                'title'       => 'Atomic Habits',
                'author'      => 'James Clear',
                'publisher'   => 'Gramedia',
                'year'        => 2018,
                'price'       => 99000,
                'stock'       => 60,
                'description' => 'Cara membangun kebiasaan baik dan menghentikan kebiasaan buruk melalui perubahan kecil.',
            ],
            [
                'category_id' => 2,
                'title'       => 'Filosofi Teras',
                'author'      => 'Henry Manampiring',
                'publisher'   => 'Penerbit Buku Kompas',
                'year'        => 2018,
                'price'       => 75000,
                'stock'       => 55,
                'description' => 'Pengantar filsafat Stoa untuk kehidupan sehari-hari masyarakat modern Indonesia.',
            ],
            [
                'category_id' => 2,
                'title'       => 'Sebuah Seni untuk Bersikap Bodo Amat',
                'author'      => 'Mark Manson',
                'publisher'   => 'Gramedia',
                'year'        => 2016,
                'price'       => 82000,
                'stock'       => 42,
                'description' => 'Pendekatan yang berlawanan dengan intuisi untuk menjalani hidup yang lebih baik.',
            ],

            // ── Teknologi (category_id: 3) ──
            [
                'category_id' => 3,
                'title'       => 'Clean Code',
                'author'      => 'Robert C. Martin',
                'publisher'   => 'Prentice Hall',
                'year'        => 2008,
                'price'       => 150000,
                'stock'       => 25,
                'description' => 'Panduan menulis kode yang bersih, mudah dibaca, dan mudah dipelihara.',
            ],
            [
                'category_id' => 3,
                'title'       => 'Laravel Up & Running',
                'author'      => 'Matt Stauffer',
                'publisher'   => 'O\'Reilly Media',
                'year'        => 2019,
                'price'       => 175000,
                'stock'       => 20,
                'description' => 'Panduan lengkap untuk membangun aplikasi web modern dengan framework Laravel.',
            ],
            [
                'category_id' => 3,
                'title'       => 'JavaScript: The Good Parts',
                'author'      => 'Douglas Crockford',
                'publisher'   => 'O\'Reilly Media',
                'year'        => 2008,
                'price'       => 130000,
                'stock'       => 28,
                'description' => 'Mengupas bagian terbaik dari bahasa pemrograman JavaScript.',
            ],
            [
                'category_id' => 3,
                'title'       => 'Eloquent JavaScript',
                'author'      => 'Marijn Haverbeke',
                'publisher'   => 'No Starch Press',
                'year'        => 2018,
                'price'       => 140000,
                'stock'       => 32,
                'description' => 'Pengantar modern ke pemrograman JavaScript dan konsep pemrograman secara umum.',
            ],

            // ── Bisnis (category_id: 4) ──
            [
                'category_id' => 4,
                'title'       => 'Rich Dad Poor Dad',
                'author'      => 'Robert T. Kiyosaki',
                'publisher'   => 'Gramedia',
                'year'        => 1997,
                'price'       => 88000,
                'stock'       => 48,
                'description' => 'Pelajaran tentang keuangan pribadi yang diajarkan oleh dua sosok ayah.',
            ],
            [
                'category_id' => 4,
                'title'       => 'The Lean Startup',
                'author'      => 'Eric Ries',
                'publisher'   => 'Crown Business',
                'year'        => 2011,
                'price'       => 115000,
                'stock'       => 22,
                'description' => 'Metodologi membangun startup yang efisien melalui eksperimen dan iterasi cepat.',
            ],
            [
                'category_id' => 4,
                'title'       => 'Zero to One',
                'author'      => 'Peter Thiel',
                'publisher'   => 'Crown Business',
                'year'        => 2014,
                'price'       => 105000,
                'stock'       => 30,
                'description' => 'Catatan tentang startup dan bagaimana membangun masa depan.',
            ],
            [
                'category_id' => 4,
                'title'       => 'Think and Grow Rich',
                'author'      => 'Napoleon Hill',
                'publisher'   => 'Gramedia',
                'year'        => 1937,
                'price'       => 72000,
                'stock'       => 38,
                'description' => 'Prinsip-prinsip kesuksesan finansial berdasarkan riset selama 20 tahun.',
            ],

            // ── Seni & Budaya (category_id: 5) ──
            [
                'category_id' => 5,
                'title'       => 'Catatan Pinggir',
                'author'      => 'Goenawan Mohamad',
                'publisher'   => 'Pustaka Utama Grafiti',
                'year'        => 1998,
                'price'       => 68000,
                'stock'       => 20,
                'description' => 'Kumpulan esai sastra dan budaya dari kolom Majalah Tempo.',
            ],
            [
                'category_id' => 5,
                'title'       => 'Seni Rupa Modern Indonesia',
                'author'      => 'Mikke Susanto',
                'publisher'   => 'DictiArt Lab',
                'year'        => 2011,
                'price'       => 195000,
                'stock'       => 15,
                'description' => 'Penjelajahan komprehensif perkembangan seni rupa modern di Indonesia.',
            ],
            [
                'category_id' => 5,
                'title'       => 'Ronggeng Dukuh Paruk',
                'author'      => 'Ahmad Tohari',
                'publisher'   => 'Gramedia',
                'year'        => 1982,
                'price'       => 78000,
                'stock'       => 33,
                'description' => 'Novel klasik Indonesia tentang kehidupan penari ronggeng di pedesaan Jawa.',
            ],
            [
                'category_id' => 5,
                'title'       => 'Membaca Sastra',
                'author'      => 'Melani Budianta',
                'publisher'   => 'Indonesia Tera',
                'year'        => 2006,
                'price'       => 62000,
                'stock'       => 27,
                'description' => 'Pengantar memahami karya sastra Indonesia dan dunia secara kritis.',
            ],
        ];

        foreach ($books as $bookData) {
            $bookData['slug'] = Str::slug($bookData['title']);
            Book::create($bookData);
        }

        $this->command->info('✅ Seeder berhasil: 2 user, 5 kategori, 20 buku.');
    }
}
