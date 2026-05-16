import { useEffect, useState } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import { ShoppingCartIcon } from '@heroicons/react/24/solid';
import api from '../api/axios';
import { useAuth } from '../contexts/AuthContext';
import { useCart } from '../contexts/CartContext';
import { Spinner } from '../components/Loading';
import Swal from 'sweetalert2';

export default function BookDetailPage() {
  const { slug } = useParams();
  const navigate = useNavigate();
  const { user } = useAuth();
  const { addToCart } = useCart();
  const [book, setBook] = useState(null);
  const [loading, setLoading] = useState(true);
  const [adding, setAdding] = useState(false);

  useEffect(() => {
    api.get(`/books/${slug}`)
      .then((res) => setBook(res.data.data))
      .catch(() => navigate('/books'))
      .finally(() => setLoading(false));
  }, [slug]);

  const formatPrice = (price) =>
    new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(price);

  const handleAddToCart = async () => {
    if (!user) return navigate('/login');
    setAdding(true);
    try {
      await addToCart(book.id, 1);
      const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 2500, timerProgressBar: true });
      Toast.fire({ icon: 'success', title: `"${book.title}" ditambahkan ke keranjang! 🛒` });
    } catch (err) {
      Swal.fire({ icon: 'error', title: 'Gagal', text: err.response?.data?.message || 'Terjadi kesalahan.' });
    }
    setAdding(false);
  };

  const handleBuyNow = async () => {
    if (!user) return navigate('/login');
    setAdding(true);
    try {
      await addToCart(book.id, 1);
      navigate('/cart');
    } catch (err) {
      Swal.fire({ icon: 'error', title: 'Gagal', text: err.response?.data?.message || 'Terjadi kesalahan.' });
    }
    setAdding(false);
  };

  if (loading) return <Spinner />;
  if (!book) return null;

  return (
    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div className="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div className="grid md:grid-cols-2 gap-0">
          {/* Cover */}
          <div className="bg-gradient-to-br from-indigo-50 to-purple-50 flex items-center justify-center p-8 md:p-12">
            {book.cover_image ? (
              <img src={book.cover_image.startsWith('http') ? book.cover_image : `http://localhost:8000/storage/${book.cover_image}`}
                   alt={book.title} className="max-h-96 rounded-lg shadow-2xl" />
            ) : (
              <div className="w-64 h-80 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-lg flex flex-col items-center justify-center p-6 shadow-xl">
                <span className="text-7xl mb-4">📖</span>
                <p className="text-white text-center font-bold text-lg">{book.title}</p>
                <p className="text-white/70 text-sm mt-1">{book.author}</p>
              </div>
            )}
          </div>

          {/* Info */}
          <div className="p-8 md:p-12 flex flex-col">
            <span className="inline-block bg-indigo-100 text-indigo-600 text-xs px-3 py-1 rounded-full font-medium w-fit">
              {book.category?.name}
            </span>
            <h1 className="text-3xl font-bold text-gray-900 mt-4">{book.title}</h1>
            <p className="text-gray-500 mt-2">oleh <span className="font-medium text-gray-700">{book.author}</span></p>
            <p className="text-sm text-gray-400 mt-1">{book.publisher} • {book.year}</p>

            <div className="mt-6">
              <span className="text-3xl font-bold text-indigo-600">{formatPrice(book.price)}</span>
            </div>

            <div className="mt-3">
              {book.stock > 0 ? (
                <span className="text-sm text-green-600 font-medium">✓ Stok tersedia ({book.stock})</span>
              ) : (
                <span className="text-sm text-red-500 font-medium">✕ Stok habis</span>
              )}
            </div>

            <p className="text-gray-600 mt-6 leading-relaxed flex-1">{book.description}</p>

            {book.stock > 0 && (
              <div className="flex gap-3 mt-8">
                <button onClick={handleAddToCart} disabled={adding}
                  className="flex-1 flex items-center justify-center gap-2 px-6 py-3 border-2 border-indigo-600 text-indigo-600 rounded-xl font-semibold hover:bg-indigo-50 transition-all disabled:opacity-50">
                  <ShoppingCartIcon className="w-5 h-5" />
                  Tambah ke Keranjang
                </button>
                <button onClick={handleBuyNow} disabled={adding}
                  className="flex-1 px-6 py-3 bg-indigo-600 text-white rounded-xl font-semibold hover:bg-indigo-700 transition-all disabled:opacity-50 shadow-lg">
                  Beli Sekarang
                </button>
              </div>
            )}
          </div>
        </div>
      </div>
    </div>
  );
}
