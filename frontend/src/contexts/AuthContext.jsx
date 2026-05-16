import { createContext, useContext, useState, useEffect } from 'react';
import api from '../api/axios';

const AuthContext = createContext(null);

export function AuthProvider({ children }) {
  const [user, setUser] = useState(null);
  const [loading, setLoading] = useState(true);

  // Cek status login saat pertama kali load
  useEffect(() => {
    const token = localStorage.getItem('auth_token');
    if (token) {
      api.get('/user')
        .then((res) => setUser(res.data.data))
        .catch(() => {
          localStorage.removeItem('auth_token');
          localStorage.removeItem('user');
        })
        .finally(() => setLoading(false));
    } else {
      setLoading(false);
    }
  }, []);

  const login = async (email, password) => {
    const res = await api.post('/login', { email, password });
    const { user: userData, token } = res.data.data;
    localStorage.setItem('auth_token', token);
    localStorage.setItem('user', JSON.stringify(userData));
    setUser(userData);
    return res.data;
  };

  const register = async (name, email, password, password_confirmation) => {
    const res = await api.post('/register', { name, email, password, password_confirmation });
    const { user: userData, token } = res.data.data;
    localStorage.setItem('auth_token', token);
    localStorage.setItem('user', JSON.stringify(userData));
    setUser(userData);
    return res.data;
  };

  const logout = async () => {
    try {
      await api.post('/logout');
    } catch (_) { /* ignore */ }
    localStorage.removeItem('auth_token');
    localStorage.removeItem('user');
    setUser(null);
  };

  // Clear auth state tanpa API call (dipakai saat logout dari admin dashboard)
  const clearAuth = () => {
    localStorage.removeItem('auth_token');
    localStorage.removeItem('user');
    setUser(null);
  };

  // Token untuk auto-login admin panel
  const token = localStorage.getItem('auth_token');

  return (
    <AuthContext.Provider value={{ user, token, loading, login, register, logout, clearAuth, setUser }}>
      {children}
    </AuthContext.Provider>
  );
}

export const useAuth = () => useContext(AuthContext);
