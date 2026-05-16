import { Link } from 'react-router-dom';

export default function Footer() {
  return (
    <footer className="bg-gray-900 text-gray-300 mt-auto">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div className="grid grid-cols-1 md:grid-cols-4 gap-8">
          {/* Brand */}
          <div className="col-span-1 md:col-span-2">
            <div className="flex items-center gap-2 mb-4">
              <span className="text-2xl">📚</span>
              <span className="text-xl font-bold text-white">BookWise</span>
            </div>
            <p className="text-sm text-gray-400 max-w-md">
              Platform toko buku online terlengkap di Indonesia. Temukan ribuan buku berkualitas dari berbagai genre dan penulis favorit Anda.
            </p>
            <div className="flex gap-4 mt-4">
              <a href="#" className="text-gray-400 hover:text-white transition-colors"><i className="fab fa-facebook">FB</i></a>
              <a href="#" className="text-gray-400 hover:text-white transition-colors"><i className="fab fa-twitter">TW</i></a>
              <a href="#" className="text-gray-400 hover:text-white transition-colors"><i className="fab fa-instagram">IG</i></a>
            </div>
          </div>

          {/* Navigation */}
          <div>
            <h3 className="text-white font-semibold mb-4">Navigasi</h3>
            <ul className="space-y-2">
              <li><Link to="/" className="text-sm hover:text-white transition-colors">Beranda</Link></li>
              <li><Link to="/books" className="text-sm hover:text-white transition-colors">Katalog Buku</Link></li>
              <li><Link to="/about" className="text-sm hover:text-white transition-colors">Tentang Kami</Link></li>
              <li><Link to="/contact" className="text-sm hover:text-white transition-colors">Kontak</Link></li>
            </ul>
          </div>

          {/* Account */}
          <div>
            <h3 className="text-white font-semibold mb-4">Akun</h3>
            <ul className="space-y-2">
              <li><Link to="/login" className="text-sm hover:text-white transition-colors">Masuk</Link></li>
              <li><Link to="/register" className="text-sm hover:text-white transition-colors">Daftar</Link></li>
              <li><Link to="/cart" className="text-sm hover:text-white transition-colors">Keranjang</Link></li>
              <li><Link to="/orders" className="text-sm hover:text-white transition-colors">Pesanan Saya</Link></li>
            </ul>
          </div>
        </div>

        <div className="border-t border-gray-700 mt-8 pt-8 text-center text-sm text-gray-500">
          <p>&copy; {new Date().getFullYear()} BookWise BNSP. All rights reserved.</p>
        </div>
      </div>
    </footer>
  );
}
