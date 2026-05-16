import { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { useAuth } from '../contexts/AuthContext';
import Swal from 'sweetalert2';

export default function RegisterPage() {
  const { register } = useAuth();
  const navigate = useNavigate();
  const [form, setForm] = useState({ name: '', email: '', password: '', password_confirmation: '' });
  const [loading, setLoading] = useState(false);
  const [errors, setErrors] = useState({});

  const Toast = Swal.mixin({
    toast: true, position: 'top-end', showConfirmButton: false,
    timer: 3000, timerProgressBar: true,
    didOpen: (t) => { t.onmouseenter = Swal.stopTimer; t.onmouseleave = Swal.resumeTimer; },
  });

  const validate = () => {
    const e = {};
    if (!form.name) e.name = 'Nama wajib diisi.';
    if (!form.email) e.email = 'Email wajib diisi.';
    if (!form.password || form.password.length < 8) e.password = 'Password minimal 8 karakter.';
    if (form.password !== form.password_confirmation) e.password_confirmation = 'Konfirmasi password tidak cocok.';
    return e;
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    const v = validate();
    if (Object.keys(v).length) {
      setErrors(v);
      Swal.fire({ icon: 'warning', title: 'Form Belum Lengkap!', text: Object.values(v)[0], timer: 2500, showConfirmButton: false, customClass: { popup: 'rounded-2xl' } });
      return;
    }
    setErrors({});
    setLoading(true);
    try {
      await register(form.name, form.email, form.password, form.password_confirmation);
      await Swal.fire({
        icon: 'success', title: 'Registrasi Berhasil! 🎉',
        html: `<p class="text-gray-600">Selamat datang di <strong>BookWise</strong>, ${form.name}!</p>`,
        confirmButtonColor: '#6366f1',
        customClass: { popup: 'rounded-2xl' },
      });
      navigate('/');
    } catch (err) {
      const msg = err.response?.data?.message || 'Terjadi kesalahan.';
      Swal.fire({ icon: 'error', title: 'Registrasi Gagal 😢', text: msg, confirmButtonColor: '#6366f1', customClass: { popup: 'rounded-2xl' } });
    }
    setLoading(false);
  };

  const inputClass = (field) => `w-full border rounded-xl px-4 py-3 text-sm outline-none transition-all focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400 ${errors[field] ? 'border-red-400 bg-red-50' : 'border-gray-200'}`;

  return (
    <div className="min-h-[75vh] flex items-center justify-center px-4 py-12 bg-gradient-to-br from-indigo-50 via-white to-purple-50">
      <div className="w-full max-w-md">
        <div className="bg-white rounded-2xl shadow-xl border border-gray-100 p-8">
          <div className="text-center mb-6">
            <div className="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-2xl flex items-center justify-center mx-auto shadow-lg">
              <span className="text-3xl">✨</span>
            </div>
            <h1 className="text-2xl font-bold text-gray-900 mt-4">Buat Akun Baru</h1>
            <p className="text-gray-500 text-sm mt-1">Bergabung dengan BookWise</p>
          </div>
          <form onSubmit={handleSubmit} className="space-y-4">
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
              <input type="text" value={form.name} onChange={(e) => setForm({ ...form, name: e.target.value })} className={inputClass('name')} placeholder="John Doe" />
              {errors.name && <p className="text-xs text-red-500 mt-1">{errors.name}</p>}
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">Email</label>
              <input type="email" value={form.email} onChange={(e) => setForm({ ...form, email: e.target.value })} className={inputClass('email')} placeholder="contoh@email.com" />
              {errors.email && <p className="text-xs text-red-500 mt-1">{errors.email}</p>}
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">Password</label>
              <input type="password" value={form.password} onChange={(e) => setForm({ ...form, password: e.target.value })} className={inputClass('password')} placeholder="Minimal 8 karakter" />
              {errors.password && <p className="text-xs text-red-500 mt-1">{errors.password}</p>}
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
              <input type="password" value={form.password_confirmation} onChange={(e) => setForm({ ...form, password_confirmation: e.target.value })} className={inputClass('password_confirmation')} placeholder="Ulangi password" />
              {errors.password_confirmation && <p className="text-xs text-red-500 mt-1">{errors.password_confirmation}</p>}
            </div>
            <button type="submit" disabled={loading}
              className="w-full py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all shadow-lg disabled:opacity-50 transform hover:-translate-y-0.5">
              {loading ? (
                <span className="flex items-center justify-center gap-2">
                  <svg className="animate-spin h-5 w-5" viewBox="0 0 24 24"><circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4" fill="none" /><path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" /></svg>
                  Memproses...
                </span>
              ) : 'Daftar Sekarang'}
            </button>
          </form>
          <p className="text-center text-sm text-gray-500 mt-6">
            Sudah punya akun? <Link to="/login" className="text-indigo-600 font-medium hover:underline">Masuk</Link>
          </p>
        </div>
      </div>
    </div>
  );
}
