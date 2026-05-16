import { Navigate, useLocation } from 'react-router-dom';
import { useAuth } from '../contexts/AuthContext';
import { Spinner } from './Loading';

export default function ProtectedRoute({ children }) {
  const { user, loading } = useAuth();
  const location = useLocation();

  if (loading) return <Spinner />;

  if (!user) {
    // Simpan halaman tujuan agar bisa redirect setelah login
    return <Navigate to="/login" state={{ from: location }} replace />;
  }

  return children;
}
