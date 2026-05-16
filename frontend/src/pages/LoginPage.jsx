import { useState } from 'react';
import { Link, useNavigate, useLocation } from 'react-router-dom';
import { useAuth } from '../contexts/AuthContext';
import Swal from 'sweetalert2';

export default function LoginPage() {
  const { login } = useAuth();
  const navigate = useNavigate();
  const location = useLocation();
  const [form, setForm] = useState({ email: '', password: '' });
  const [loading, setLoading] = useState(false);
  const [errors, setErrors] = useState({});

  const from = location.state?.from?.pathname || '/';

  const Toast = Swal.mixin({
    toast: true, position: 'top-end', showConfirmButton: false,
    timer: 3000, timerProgressBar: true,
    didOpen: (t) => { t.onmouseenter = Swal.stopTimer; t.onmouseleave = Swal.resumeTimer; },
  });

  const handleSubmit = async (e) => {
    e.preventDefault();
    const newErrors = {};
    if (!form.email) newErrors.email = 'Email wajib diisi.';
    if (!form.password) newErrors.password = 'Password wajib diisi.';
    if (Object.keys(newErrors).length) {
      setErrors(newErrors);
      Swal.fire({ icon: 'warning', title: 'Oops!', text: 'Mohon lengkapi semua field.', timer: 2000, showConfirmButton: false, customClass: { popup: 'rounded-2xl' } });
      return;
    }
    setErrors({});
    setLoading(true);
    try {
      const res = await login(form.email, form.password);
      Toast.fire({ icon: 'success', title: `Selamat datang, ${res.data.user.name}! 👋` });
      navigate(from, { replace: true });
    } catch (err) {
      Swal.fire({
        icon: 'error', title: 'Login Gagal 😢',
        text: err.response?.data?.message || 'Email atau password salah.',
        confirmButtonColor: '#6366f1',
        customClass: { popup: 'rounded-2xl' },
      });
    }
    setLoading(false);
  };

  return (
    <div className="min-h-[75vh] flex items-center justify-center px-4 py-12 bg-gradient-to-br from-indigo-50 via-white to-purple-50">
      <div className="w-full max-w-md">
        <div className="bg-white rounded-2xl shadow-xl border border-gray-100 p-8">
          <div className="text-center mb-6">
            <div className="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-2xl flex items-center justify-center mx-auto shadow-lg">
              <span className="text-3xl">📚</span>
            </div>
            <h1 className="text-2xl font-bold text-gray-900 mt-4">Masuk ke BookWise</h1>
            <p className="text-gray-500 text-sm mt-1">Selamat datang kembali!</p>
          </div>

          <form onSubmit={handleSubmit} className="space-y-4">
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">Email</label>
              <input type="email" value={form.email} onChange={(e) => setForm({ ...form, email: e.target.value })}
                className={`w-full border rounded-xl px-4 py-3 text-sm outline-none transition-all focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400 ${errors.email ? 'border-red-400 bg-red-50' : 'border-gray-200'}`}
                placeholder="contoh@email.com" />
              {errors.email && <p className="text-xs text-red-500 mt-1">{errors.email}</p>}
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">Password</label>
              <input type="password" value={form.password} onChange={(e) => setForm({ ...form, password: e.target.value })}
                className={`w-full border rounded-xl px-4 py-3 text-sm outline-none transition-all focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400 ${errors.password ? 'border-red-400 bg-red-50' : 'border-gray-200'}`}
                placeholder="Masukkan password" />
              {errors.password && <p className="text-xs text-red-500 mt-1">{errors.password}</p>}
            </div>
            <button type="submit" disabled={loading}
              className="w-full py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all shadow-lg disabled:opacity-50 transform hover:-translate-y-0.5">
              {loading ? (
                <span className="flex items-center justify-center gap-2">
                  <svg className="animate-spin h-5 w-5" viewBox="0 0 24 24"><circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4" fill="none" /><path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" /></svg>
                  Memproses...
                </span>
              ) : 'Masuk'}
            </button>
          </form>

          {/* Demo Accounts */}
          <div className="mt-4 p-3 bg-gray-50 rounded-xl">
            <p className="text-xs text-gray-500 font-medium mb-2 text-center">Demo Accounts:</p>
            <div className="grid grid-cols-2 gap-2 text-xs">
              <button onClick={() => setForm({ email: 'admin@bookwise.test', password: 'password' })}
                className="px-3 py-1.5 bg-purple-100 text-purple-700 rounded-lg hover:bg-purple-200 transition-colors font-medium">
                👑 Admin
              </button>
              <button onClick={() => setForm({ email: 'user@bookwise.test', password: 'password' })}
                className="px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors font-medium">
                👤 User
              </button>
            </div>
          </div>

          <p className="text-center text-sm text-gray-500 mt-6">
            Belum punya akun? <Link to="/register" className="text-indigo-600 font-medium hover:underline">Daftar</Link>
          </p>
        </div>
      </div>
    </div>
  );
}
