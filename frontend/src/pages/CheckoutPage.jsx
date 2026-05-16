import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import api from '../api/axios';
import { useCart } from '../contexts/CartContext';
import Swal from 'sweetalert2';

export default function CheckoutPage() {
  const { cartItems, cartTotal, fetchCart } = useCart();
  const navigate = useNavigate();
  const [paymentMethod, setPaymentMethod] = useState('midtrans');
  const [processing, setProcessing] = useState(false);

  const formatPrice = (p) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(p);

  const handleCheckout = async () => {
    setProcessing(true);
    try {
      const res = await api.post('/orders', { payment_method: paymentMethod });
      const order = res.data.data;

      if (paymentMethod === 'midtrans' && order.midtrans_token) {
        // Integrasi Midtrans Snap.js
        if (window.snap) {
          window.snap.pay(order.midtrans_token, {
            onSuccess: () => { fetchCart(); navigate('/orders'); Swal.fire('Sukses!', 'Pembayaran berhasil.', 'success'); },
            onPending: () => { fetchCart(); navigate('/orders'); Swal.fire('Info', 'Menunggu pembayaran.', 'info'); },
            onError: () => Swal.fire('Error', 'Pembayaran gagal.', 'error'),
            onClose: () => { fetchCart(); navigate('/orders'); },
          });
        } else {
          // Fallback jika Snap.js tidak tersedia (sandbox/dev)
          await fetchCart();
          Swal.fire('Pesanan Dibuat!', `Kode: ${order.order_code}. Token: ${order.midtrans_token}`, 'success');
          navigate('/orders');
        }
      } else {
        // COD
        await fetchCart();
        Swal.fire('Pesanan Dibuat!', `Kode pesanan: ${order.order_code}`, 'success');
        navigate('/orders');
      }
    } catch (err) {
      Swal.fire('Gagal', err.response?.data?.message || 'Terjadi kesalahan.', 'error');
    }
    setProcessing(false);
  };

  if (cartItems.length === 0) {
    navigate('/cart');
    return null;
  }

  return (
    <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <h1 className="text-3xl font-bold text-gray-900 mb-6">💳 Checkout</h1>
      <div className="grid md:grid-cols-2 gap-8">
        {/* Order Summary */}
        <div className="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
          <h3 className="text-lg font-bold text-gray-800 mb-4">Ringkasan Pesanan</h3>
          <div className="space-y-3 max-h-64 overflow-auto">
            {cartItems.map((item) => (
              <div key={item.id} className="flex justify-between text-sm">
                <span className="text-gray-600">{item.book?.title} × {item.quantity}</span>
                <span className="font-medium">{formatPrice(item.subtotal)}</span>
              </div>
            ))}
          </div>
          <hr className="my-4" />
          <div className="flex justify-between text-lg font-bold">
            <span>Total</span>
            <span className="text-indigo-600">{formatPrice(cartTotal)}</span>
          </div>
        </div>

        {/* Payment Method */}
        <div className="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
          <h3 className="text-lg font-bold text-gray-800 mb-4">Metode Pembayaran</h3>
          <div className="space-y-3">
            <label className={`flex items-center gap-3 p-4 border rounded-xl cursor-pointer transition-all ${paymentMethod === 'midtrans' ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 hover:border-gray-300'}`}>
              <input type="radio" name="payment" value="midtrans" checked={paymentMethod === 'midtrans'} onChange={(e) => setPaymentMethod(e.target.value)} className="accent-indigo-600" />
              <div>
                <p className="font-medium text-gray-800">💳 Midtrans Online Payment</p>
                <p className="text-xs text-gray-500">Kartu kredit, e-wallet, transfer bank</p>
              </div>
            </label>
            <label className={`flex items-center gap-3 p-4 border rounded-xl cursor-pointer transition-all ${paymentMethod === 'cod' ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 hover:border-gray-300'}`}>
              <input type="radio" name="payment" value="cod" checked={paymentMethod === 'cod'} onChange={(e) => setPaymentMethod(e.target.value)} className="accent-indigo-600" />
              <div>
                <p className="font-medium text-gray-800">🚚 Bayar di Tempat (COD)</p>
                <p className="text-xs text-gray-500">Bayar saat buku diterima</p>
              </div>
            </label>
          </div>

          <button onClick={handleCheckout} disabled={processing}
            className="w-full mt-6 py-3 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition-all shadow-lg disabled:opacity-50">
            {processing ? 'Memproses...' : 'Buat Pesanan'}
          </button>
        </div>
      </div>
    </div>
  );
}
