import { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import api from '../api/axios';
import { Spinner } from '../components/Loading';

export default function OrdersPage() {
  const [orders, setOrders] = useState([]);
  const [loading, setLoading] = useState(true);

  const formatPrice = (p) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(p);

  useEffect(() => {
    api.get('/orders').then((res) => setOrders(res.data.data)).catch(() => {}).finally(() => setLoading(false));
  }, []);

  const statusBadge = (status) => {
    const map = { paid: 'bg-green-100 text-green-700', pending: 'bg-yellow-100 text-yellow-700', cancelled: 'bg-red-100 text-red-700' };
    return <span className={`px-3 py-1 rounded-full text-xs font-semibold ${map[status] || ''}`}>{status.toUpperCase()}</span>;
  };

  if (loading) return <Spinner />;

  return (
    <div className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <h1 className="text-3xl font-bold text-gray-900 mb-6">📋 Pesanan Saya</h1>
      {orders.length === 0 ? (
        <div className="text-center py-20">
          <span className="text-6xl">📭</span>
          <p className="text-gray-500 mt-4">Belum ada pesanan. <Link to="/books" className="text-indigo-600 font-medium">Mulai belanja →</Link></p>
        </div>
      ) : (
        <div className="space-y-4">
          {orders.map((order) => (
            <details key={order.id} className="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden group">
              <summary className="flex items-center justify-between p-5 cursor-pointer hover:bg-gray-50 transition-colors">
                <div className="flex items-center gap-4">
                  <div>
                    <p className="font-semibold text-gray-800">{order.order_code}</p>
                    <p className="text-xs text-gray-400">{order.created_at}</p>
                  </div>
                </div>
                <div className="flex items-center gap-4">
                  {statusBadge(order.status)}
                  <span className="font-bold text-indigo-600">{formatPrice(order.total_price)}</span>
                </div>
              </summary>
              <div className="border-t border-gray-100 p-5 bg-gray-50">
                <table className="w-full text-sm">
                  <thead><tr className="text-gray-500"><th className="text-left pb-2">Buku</th><th className="text-right pb-2">Harga</th><th className="text-right pb-2">Qty</th><th className="text-right pb-2">Subtotal</th></tr></thead>
                  <tbody>
                    {order.order_items?.map((item) => (
                      <tr key={item.id} className="border-t border-gray-200">
                        <td className="py-2">{item.book?.title || 'Buku dihapus'}</td>
                        <td className="text-right py-2">{formatPrice(item.price)}</td>
                        <td className="text-right py-2">{item.quantity}</td>
                        <td className="text-right py-2 font-medium">{formatPrice(item.subtotal)}</td>
                      </tr>
                    ))}
                  </tbody>
                </table>
              </div>
            </details>
          ))}
        </div>
      )}
    </div>
  );
}
