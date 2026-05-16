import { useEffect, useState } from 'react';
import { Link, useSearchParams } from 'react-router-dom';
import api from '../api/axios';
import BookCard from '../components/BookCard';
import { SkeletonCard } from '../components/Loading';
import { useAuth } from '../contexts/AuthContext';
import Swal from 'sweetalert2';

export default function HomePage() {
  const [latestBooks, setLatestBooks] = useState([]);
  const [loading, setLoading] = useState(true);
  const [searchParams, setSearchParams] = useSearchParams();
  const { clearAuth } = useAuth();

  // Deteksi logout dari admin dashboard
  useEffect(() => {
    if (searchParams.get('logout') === 'admin') {
      // Bersihkan auth state di frontend
      clearAuth();

      // Tampilkan notifikasi
      const Toast = Swal.mixin({
        toast: true, position: 'top-end', showConfirmButton: false,
        timer: 3000, timerProgressBar: true,
        didOpen: (t) => { t.onmouseenter = Swal.stopTimer; t.onmouseleave = Swal.resumeTimer; },
      });
      Toast.fire({ icon: 'success', title: 'Berhasil logout dari Admin Panel! 👋' });

      // Hapus query param dari URL
      setSearchParams({}, { replace: true });
    }
  }, []);

  useEffect(() => {
    api.get('/books?sort=latest')
      .then((res) => setLatestBooks(res.data.data.slice(0, 8)))
      .catch(() => {})
      .finally(() => setLoading(false));
  }, []);

  return (
    <div>
      {/* ═══ HERO SECTION ═══ */}
      <section className="relative bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500 overflow-hidden">
        <div className="absolute inset-0 opacity-10">
          <div className="absolute top-10 left-10 w-72 h-72 bg-white rounded-full blur-3xl"></div>
          <div className="absolute bottom-10 right-10 w-96 h-96 bg-yellow-300 rounded-full blur-3xl"></div>
        </div>
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 md:py-32 relative z-10">
          <div className="text-center">
            <h1 className="text-4xl md:text-6xl font-extrabold text-white leading-tight">
              Temukan Buku
              <span className="block text-yellow-300">Favoritmu</span>
            </h1>
            <p className="mt-6 text-lg md:text-xl text-indigo-100 max-w-2xl mx-auto">
              Jelajahi ribuan koleksi buku dari penulis terbaik Indonesia dan dunia. Belanja mudah, pengiriman cepat.
            </p>
            <div className="mt-8 flex flex-col sm:flex-row gap-4 justify-center">
              <Link to="/books"
                className="px-8 py-3 bg-white text-indigo-600 font-semibold rounded-xl hover:bg-gray-100 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                🛒 Belanja Sekarang
              </Link>
              <Link to="/about"
                className="px-8 py-3 border-2 border-white/30 text-white font-semibold rounded-xl hover:bg-white/10 transition-all">
                Tentang Kami
              </Link>
            </div>
          </div>
        </div>
      </section>

      {/* ═══ BUKU TERBARU ═══ */}
      <section className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div className="flex items-center justify-between mb-8">
          <div>
            <h2 className="text-2xl md:text-3xl font-bold text-gray-900">📖 Buku Terbaru</h2>
            <p className="text-gray-500 mt-1">Koleksi terbaru yang baru saja ditambahkan</p>
          </div>
          <Link to="/books" className="text-indigo-600 hover:text-indigo-700 font-medium text-sm">
            Lihat Semua →
          </Link>
        </div>

        <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
          {loading
            ? Array.from({ length: 8 }).map((_, i) => <SkeletonCard key={i} />)
            : latestBooks.map((book) => <BookCard key={book.id} book={book} />)
          }
        </div>
      </section>

      {/* ═══ CTA SECTION ═══ */}
      <section className="bg-gradient-to-r from-indigo-50 to-purple-50 py-16">
        <div className="max-w-4xl mx-auto px-4 text-center">
          <h2 className="text-2xl md:text-3xl font-bold text-gray-900">Siap Menemukan Buku Impianmu?</h2>
          <p className="text-gray-600 mt-3">Daftar sekarang dan dapatkan akses ke seluruh koleksi buku kami.</p>
          <Link to="/register"
            className="inline-block mt-6 px-8 py-3 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition-all shadow-lg">
            Daftar Gratis
          </Link>
        </div>
      </section>
    </div>
  );
}
