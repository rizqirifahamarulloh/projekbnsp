import { Link, useNavigate } from 'react-router-dom';
import { ShoppingCartIcon, Bars3Icon, XMarkIcon, UserCircleIcon, ChevronDownIcon } from '@heroicons/react/24/outline';
import { useAuth } from '../contexts/AuthContext';
import { useCart } from '../contexts/CartContext';
import { useState, useRef, useEffect } from 'react';
import Swal from 'sweetalert2';

export default function Navbar() {
  const { user, token, logout } = useAuth();
  const { cartCount } = useCart();
  const [mobileOpen, setMobileOpen] = useState(false);
  const [dropdownOpen, setDropdownOpen] = useState(false);
  const navigate = useNavigate();
  const dropdownRef = useRef(null);

  // Tutup dropdown saat klik di luar
  useEffect(() => {
    const handleClickOutside = (e) => {
      if (dropdownRef.current && !dropdownRef.current.contains(e.target)) {
        setDropdownOpen(false);
      }
    };
    document.addEventListener('mousedown', handleClickOutside);
    return () => document.removeEventListener('mousedown', handleClickOutside);
  }, []);

  const handleLogout = async () => {
    const result = await Swal.fire({
      title: 'Logout?',
      text: 'Apakah Anda yakin ingin keluar?',
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#6366f1',
      cancelButtonColor: '#94a3b8',
      confirmButtonText: '<i class="fa fa-sign-out-alt"></i> Ya, Keluar',
      cancelButtonText: 'Batal',
      customClass: { popup: 'rounded-2xl' },
    });
    if (result.isConfirmed) {
      await logout();
      const Toast = Swal.mixin({
        toast: true, position: 'top-end', showConfirmButton: false,
        timer: 2500, timerProgressBar: true,
        didOpen: (t) => { t.onmouseenter = Swal.stopTimer; t.onmouseleave = Swal.resumeTimer; },
      });
      Toast.fire({ icon: 'success', title: 'Berhasil logout! 👋' });
      navigate('/');
    }
  };

  // Buka Admin Panel via auto-login (tanpa login ulang)
  const openAdminPanel = () => {
    if (token) {
      window.open(`http://localhost:8000/admin/auto-login?token=${token}`, '_blank');
    }
    setDropdownOpen(false);
  };

  const navLinks = [
    { to: '/', label: 'Beranda' },
    { to: '/books', label: 'Katalog' },
    { to: '/about', label: 'Tentang' },
    { to: '/contact', label: 'Kontak' },
  ];

  return (
    <nav className="bg-white/80 backdrop-blur-md border-b border-gray-200 sticky top-0 z-50">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex justify-between items-center h-16">
          {/* Logo */}
          <Link to="/" className="flex items-center gap-2">
            <span className="text-2xl">📚</span>
            <span className="text-xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
              BookWise
            </span>
          </Link>

          {/* Desktop Nav */}
          <div className="hidden md:flex items-center gap-6">
            {navLinks.map((l) => (
              <Link key={l.to} to={l.to}
                className="text-gray-600 hover:text-indigo-600 font-medium transition-colors">
                {l.label}
              </Link>
            ))}
          </div>

          {/* Right Actions */}
          <div className="flex items-center gap-3">
            {user && (
              <Link to="/cart" className="relative p-2 text-gray-600 hover:text-indigo-600 transition-colors">
                <ShoppingCartIcon className="w-6 h-6" />
                {cartCount > 0 && (
                  <span className="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center font-bold animate-bounce">
                    {cartCount > 99 ? '99+' : cartCount}
                  </span>
                )}
              </Link>
            )}

            {user ? (
              /* ═══ DROPDOWN PROFIL ═══ */
              <div className="hidden md:block relative" ref={dropdownRef}>
                <button onClick={() => setDropdownOpen(!dropdownOpen)}
                  className="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-gray-100 transition-all">
                  <div className="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-full flex items-center justify-center">
                    <span className="text-white font-bold text-sm">{user.name?.charAt(0).toUpperCase()}</span>
                  </div>
                  <span className="text-sm font-medium text-gray-700 max-w-[100px] truncate">{user.name}</span>
                  <ChevronDownIcon className={`w-4 h-4 text-gray-400 transition-transform ${dropdownOpen ? 'rotate-180' : ''}`} />
                </button>

                {dropdownOpen && (
                  <div className="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden animate-fadeIn z-50">
                    {/* Header */}
                    <div className="px-4 py-3 bg-gradient-to-r from-indigo-50 to-purple-50 border-b border-gray-100">
                      <p className="text-sm font-semibold text-gray-800">{user.name}</p>
                      <p className="text-xs text-gray-500 truncate">{user.email}</p>
                      <span className="inline-block mt-1 text-[10px] px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-600 font-medium uppercase">
                        {user.role}
                      </span>
                    </div>

                    {/* Menu Items */}
                    <div className="py-1">
                      {/* Admin Panel — hanya untuk admin */}
                      {user.role === 'admin' && (
                        <button onClick={openAdminPanel}
                          className="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-purple-700 hover:bg-purple-50 transition-colors">
                          <span className="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">⚙️</span>
                          <div className="text-left">
                            <p className="font-medium">Admin Panel</p>
                            <p className="text-[11px] text-gray-400">Kelola toko buku</p>
                          </div>
                        </button>
                      )}

                      {/* Pesanan Saya — semua role */}
                      <Link to="/orders" onClick={() => setDropdownOpen(false)}
                        className="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                        <span className="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">📋</span>
                        <div>
                          <p className="font-medium">Pesanan Saya</p>
                          <p className="text-[11px] text-gray-400">Riwayat pembelian</p>
                        </div>
                      </Link>
                    </div>

                    {/* Logout */}
                    <div className="border-t border-gray-100 py-1">
                      <button onClick={() => { setDropdownOpen(false); handleLogout(); }}
                        className="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                        <span className="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">🚪</span>
                        <p className="font-medium">Keluar</p>
                      </button>
                    </div>
                  </div>
                )}
              </div>
            ) : (
              <div className="hidden md:flex items-center gap-2">
                <Link to="/login"
                  className="px-4 py-2 text-sm font-medium text-indigo-600 hover:text-indigo-700 transition-colors">
                  Masuk
                </Link>
                <Link to="/register"
                  className="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors">
                  Daftar
                </Link>
              </div>
            )}

            {/* Mobile toggle */}
            <button onClick={() => setMobileOpen(!mobileOpen)} className="md:hidden p-2 text-gray-600">
              {mobileOpen ? <XMarkIcon className="w-6 h-6" /> : <Bars3Icon className="w-6 h-6" />}
            </button>
          </div>
        </div>
      </div>

      {/* ═══ MOBILE MENU ═══ */}
      {mobileOpen && (
        <div className="md:hidden bg-white border-t border-gray-200 px-4 pb-4">
          {navLinks.map((l) => (
            <Link key={l.to} to={l.to} onClick={() => setMobileOpen(false)}
              className="block py-2 text-gray-600 hover:text-indigo-600 font-medium">
              {l.label}
            </Link>
          ))}
          <hr className="my-2" />
          {user ? (
            <>
              {/* Info user */}
              <div className="flex items-center gap-2 py-2">
                <div className="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-full flex items-center justify-center">
                  <span className="text-white font-bold text-sm">{user.name?.charAt(0).toUpperCase()}</span>
                </div>
                <div>
                  <p className="text-sm font-medium text-gray-800">{user.name}</p>
                  <p className="text-xs text-gray-400">{user.role}</p>
                </div>
              </div>

              {user.role === 'admin' && (
                <button onClick={() => { setMobileOpen(false); openAdminPanel(); }}
                  className="block w-full text-left py-2 text-purple-600 font-medium">⚙️ Admin Panel</button>
              )}
              <Link to="/orders" onClick={() => setMobileOpen(false)} className="block py-2 text-gray-600">📋 Pesanan Saya</Link>
              <button onClick={() => { setMobileOpen(false); handleLogout(); }}
                className="block py-2 text-red-600">🚪 Keluar</button>
            </>
          ) : (
            <>
              <Link to="/login" onClick={() => setMobileOpen(false)} className="block py-2 text-indigo-600">Masuk</Link>
              <Link to="/register" onClick={() => setMobileOpen(false)} className="block py-2 text-indigo-600">Daftar</Link>
            </>
          )}
        </div>
      )}
    </nav>
  );
}
