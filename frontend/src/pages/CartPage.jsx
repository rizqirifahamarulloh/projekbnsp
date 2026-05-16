import { Link } from 'react-router-dom';
import { TrashIcon, MinusIcon, PlusIcon } from '@heroicons/react/24/outline';
import { useCart } from '../contexts/CartContext';
import Swal from 'sweetalert2';

export default function CartPage() {
  const { cartItems, cartTotal, updateQuantity, removeItem } = useCart();

  const formatPrice = (p) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(p);

  const handleRemove = async (id, title) => {
    const result = await Swal.fire({
      title: 'Hapus Item?', text: `Hapus "${title}" dari keranjang?`, icon: 'warning',
      showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#94a3b8',
      confirmButtonText: '🗑️ Hapus', cancelButtonText: 'Batal', reverseButtons: true,
    });
    if (result.isConfirmed) {
      await removeItem(id);
      const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 2000, timerProgressBar: true });
      Toast.fire({ icon: 'success', title: 'Item berhasil dihapus! 🗑️' });
    }
  };

  if (cartItems.length === 0) {
    return (
      <div className="max-w-4xl mx-auto px-4 py-20 text-center">
        <span className="text-6xl">🛒</span>
        <h2 className="text-2xl font-bold text-gray-800 mt-4">Keranjang Kosong</h2>
        <p className="text-gray-500 mt-2">Yuk, mulai belanja dan temukan buku favoritmu!</p>
        <Link to="/books" className="inline-block mt-6 px-6 py-3 bg-indigo-600 text-white rounded-xl font-semibold hover:bg-indigo-700">
          Mulai Belanja
        </Link>
      </div>
    );
  }

  return (
    <div className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <h1 className="text-3xl font-bold text-gray-900 mb-6">🛒 Keranjang Belanja</h1>
      <div className="grid lg:grid-cols-3 gap-8">
        {/* Items */}
        <div className="lg:col-span-2 space-y-4">
          {cartItems.map((item) => (
            <div key={item.id} className="bg-white rounded-xl p-4 shadow-sm border border-gray-100 flex gap-4">
              <div className="w-20 h-24 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                {item.book?.cover_image ? (
                  <img src={item.book.cover_image.startsWith('http') ? item.book.cover_image : `http://localhost:8000/storage/${item.book.cover_image}`} alt="" className="w-full h-full object-cover" />
                ) : <div className="w-full h-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-2xl text-white">📖</div>}
              </div>
              <div className="flex-1">
                <h3 className="font-semibold text-gray-800">{item.book?.title}</h3>
                <p className="text-sm text-gray-500">{item.book?.author}</p>
                <p className="text-indigo-600 font-bold mt-1">{formatPrice(item.book?.price)}</p>
              </div>
              <div className="flex flex-col items-end justify-between">
                <button onClick={() => handleRemove(item.id, item.book?.title)} className="text-gray-400 hover:text-red-500">
                  <TrashIcon className="w-5 h-5" />
                </button>
                <div className="flex items-center gap-2 bg-gray-100 rounded-lg px-2 py-1">
                  <button onClick={() => updateQuantity(item.id, Math.max(1, item.quantity - 1))}
                    className="p-1 hover:bg-gray-200 rounded"><MinusIcon className="w-4 h-4" /></button>
                  <span className="w-8 text-center font-medium text-sm">{item.quantity}</span>
                  <button onClick={() => updateQuantity(item.id, item.quantity + 1)}
                    className="p-1 hover:bg-gray-200 rounded"><PlusIcon className="w-4 h-4" /></button>
                </div>
                <p className="text-sm font-semibold text-gray-700">{formatPrice(item.subtotal)}</p>
              </div>
            </div>
          ))}
        </div>

        {/* Summary */}
        <div className="bg-white rounded-xl p-6 shadow-sm border border-gray-100 h-fit sticky top-20">
          <h3 className="text-lg font-bold text-gray-800 mb-4">Ringkasan</h3>
          <div className="flex justify-between text-gray-600 mb-2">
            <span>Total Item</span>
            <span>{cartItems.reduce((a, b) => a + b.quantity, 0)}</span>
          </div>
          <hr className="my-3" />
          <div className="flex justify-between text-lg font-bold text-gray-900">
            <span>Total Harga</span>
            <span className="text-indigo-600">{formatPrice(cartTotal)}</span>
          </div>
          <Link to="/checkout"
            className="block w-full mt-6 py-3 bg-indigo-600 text-white text-center font-semibold rounded-xl hover:bg-indigo-700 transition-all shadow-lg">
            Checkout →
          </Link>
        </div>
      </div>
    </div>
  );
}
