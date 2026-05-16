import { useState } from 'react';
import api from '../api/axios';
import Swal from 'sweetalert2';

export default function ContactPage() {
  const [form, setForm] = useState({ name: '', email: '', subject: '', message: '' });
  const [loading, setLoading] = useState(false);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    try {
      await api.post('/contact', form);
      Swal.fire({ icon: 'success', title: 'Pesan Terkirim!', text: 'Terima kasih telah menghubungi kami. Kami akan segera merespons.', timer: 3000 });
      setForm({ name: '', email: '', subject: '', message: '' });
    } catch (err) {
      Swal.fire({ icon: 'error', title: 'Gagal', text: err.response?.data?.message || 'Terjadi kesalahan.' });
    }
    setLoading(false);
  };

  return (
    <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div className="text-center mb-10">
        <h1 className="text-4xl font-extrabold text-gray-900">📬 Hubungi Kami</h1>
        <p className="text-gray-500 mt-3">Punya pertanyaan atau saran? Kami senang mendengar dari Anda.</p>
      </div>

      <div className="grid md:grid-cols-2 gap-8">
        {/* Contact Info */}
        <div className="space-y-6">
          <div className="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-2xl p-6">
            <h3 className="font-bold text-gray-800 mb-4">Informasi Kontak</h3>
            <div className="space-y-3 text-sm text-gray-600">
              <p>📍 Jl. Pendidikan No. 123, Jakarta, Indonesia</p>
              <p>📧 support@bookwise.test</p>
              <p>📞 +62 812-3456-7890</p>
              <p>🕐 Senin - Jumat, 09:00 - 17:00 WIB</p>
            </div>
          </div>
        </div>

        {/* Contact Form */}
        <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
          <form onSubmit={handleSubmit} className="space-y-4">
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">Nama</label>
              <input type="text" value={form.name} onChange={(e) => setForm({ ...form, name: e.target.value })}
                className="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400"
                placeholder="Nama lengkap" required />
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">Email</label>
              <input type="email" value={form.email} onChange={(e) => setForm({ ...form, email: e.target.value })}
                className="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400"
                placeholder="contoh@email.com" required />
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">Subjek</label>
              <input type="text" value={form.subject} onChange={(e) => setForm({ ...form, subject: e.target.value })}
                className="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400"
                placeholder="Subjek pesan" required />
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">Pesan</label>
              <textarea value={form.message} onChange={(e) => setForm({ ...form, message: e.target.value })} rows="4"
                className="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400 resize-none"
                placeholder="Tulis pesan Anda..." required></textarea>
            </div>
            <button type="submit" disabled={loading}
              className="w-full py-3 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition-all shadow-lg disabled:opacity-50">
              {loading ? 'Mengirim...' : 'Kirim Pesan'}
            </button>
          </form>
        </div>
      </div>
    </div>
  );
}
