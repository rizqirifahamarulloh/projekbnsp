import { createContext, useContext, useState, useEffect, useCallback } from 'react';
import api from '../api/axios';
import { useAuth } from './AuthContext';

const CartContext = createContext(null);

export function CartProvider({ children }) {
  const { user } = useAuth();
  const [cartItems, setCartItems] = useState([]);
  const [cartCount, setCartCount] = useState(0);
  const [cartTotal, setCartTotal] = useState(0);

  // Ambil keranjang saat user login
  const fetchCart = useCallback(async () => {
    if (!user) {
      setCartItems([]);
      setCartCount(0);
      setCartTotal(0);
      return;
    }
    try {
      const res = await api.get('/cart');
      const data = res.data.data;
      setCartItems(data.items || []);
      setCartCount(data.total_items || 0);
      setCartTotal(data.total_price || 0);
    } catch (_) { /* ignore */ }
  }, [user]);

  useEffect(() => {
    fetchCart();
  }, [fetchCart]);

  const addToCart = async (bookId, quantity = 1) => {
    const res = await api.post('/cart', { book_id: bookId, quantity });
    await fetchCart();
    return res.data;
  };

  const updateQuantity = async (cartId, quantity) => {
    const res = await api.patch(`/cart/${cartId}`, { quantity });
    await fetchCart();
    return res.data;
  };

  const removeItem = async (cartId) => {
    const res = await api.delete(`/cart/${cartId}`);
    await fetchCart();
    return res.data;
  };

  return (
    <CartContext.Provider value={{
      cartItems, cartCount, cartTotal,
      fetchCart, addToCart, updateQuantity, removeItem,
    }}>
      {children}
    </CartContext.Provider>
  );
}

export const useCart = () => useContext(CartContext);
