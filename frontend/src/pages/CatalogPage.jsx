import { useEffect, useState, useCallback } from 'react';
import { useSearchParams } from 'react-router-dom';
import api from '../api/axios';
import BookCard from '../components/BookCard';
import { SkeletonCard } from '../components/Loading';

export default function CatalogPage() {
  const [searchParams, setSearchParams] = useSearchParams();
  const [books, setBooks] = useState([]);
  const [categories, setCategories] = useState([]);
  const [meta, setMeta] = useState({});
  const [loading, setLoading] = useState(true);
  const [search, setSearch] = useState(searchParams.get('search') || '');

  const currentCategory = searchParams.get('category') || '';
  const currentSort = searchParams.get('sort') || 'latest';
  const currentPage = parseInt(searchParams.get('page') || '1');

  // Fetch kategori
  useEffect(() => {
    api.get('/categories').then((res) => setCategories(res.data.data)).catch(() => {});
  }, []);

  // Fetch buku berdasarkan filter
  const fetchBooks = useCallback(async () => {
    setLoading(true);
    try {
      const params = new URLSearchParams();
      if (currentCategory) params.set('category', currentCategory);
      if (search) params.set('search', search);
      if (currentSort) params.set('sort', currentSort);
      params.set('page', currentPage);

      const res = await api.get(`/books?${params.toString()}`);
      setBooks(res.data.data);
      setMeta(res.data.meta || {});
    } catch (_) {}
    setLoading(false);
  }, [currentCategory, search, currentSort, currentPage]);

  useEffect(() => { fetchBooks(); }, [fetchBooks]);

  // Debounce pencarian (400ms)
  useEffect(() => {
    const timer = setTimeout(() => {
      const params = new URLSearchParams(searchParams);
      if (search) params.set('search', search); else params.delete('search');
      params.set('page', '1');
      setSearchParams(params);
    }, 400);
    return () => clearTimeout(timer);
  }, [search]);

  const updateFilter = (key, value) => {
    const params = new URLSearchParams(searchParams);
    if (value) params.set(key, value); else params.delete(key);
    params.set('page', '1');
    setSearchParams(params);
  };

  return (
    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <h1 className="text-3xl font-bold text-gray-900 mb-6">📚 Katalog Buku</h1>

      <div className="flex flex-col lg:flex-row gap-8">
        {/* ═══ SIDEBAR FILTER ═══ */}
        <aside className="lg:w-64 flex-shrink-0">
          <div className="bg-white rounded-xl p-5 shadow-sm border border-gray-100 sticky top-20">
            <h3 className="font-semibold text-gray-800 mb-3">Kategori</h3>
            <ul className="space-y-1">
              <li>
                <button onClick={() => updateFilter('category', '')}
                  className={`w-full text-left px-3 py-2 rounded-lg text-sm transition-colors ${!currentCategory ? 'bg-indigo-50 text-indigo-600 font-medium' : 'text-gray-600 hover:bg-gray-50'}`}>
                  Semua Kategori
                </button>
              </li>
              {categories.map((cat) => (
                <li key={cat.id}>
                  <button onClick={() => updateFilter('category', cat.slug)}
                    className={`w-full text-left px-3 py-2 rounded-lg text-sm transition-colors ${currentCategory === cat.slug ? 'bg-indigo-50 text-indigo-600 font-medium' : 'text-gray-600 hover:bg-gray-50'}`}>
                    {cat.name} <span className="text-gray-400">({cat.books_count})</span>
                  </button>
                </li>
              ))}
            </ul>

            <h3 className="font-semibold text-gray-800 mt-6 mb-3">Urutkan</h3>
            <select value={currentSort} onChange={(e) => updateFilter('sort', e.target.value)}
              className="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400 outline-none">
              <option value="latest">Terbaru</option>
              <option value="cheapest">Termurah</option>
              <option value="expensive">Termahal</option>
              <option value="popular">Terpopuler</option>
            </select>
          </div>
        </aside>

        {/* ═══ MAIN CONTENT ═══ */}
        <div className="flex-1">
          {/* Search */}
          <div className="mb-6">
            <input type="text" value={search} onChange={(e) => setSearch(e.target.value)}
              placeholder="🔍 Cari judul atau penulis..."
              className="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400 outline-none transition-all" />
          </div>

          {/* Book grid */}
          <div className="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            {loading
              ? Array.from({ length: 6 }).map((_, i) => <SkeletonCard key={i} />)
              : books.length > 0
                ? books.map((book) => <BookCard key={book.id} book={book} />)
                : <div className="col-span-full text-center py-16 text-gray-400">
                    <span className="text-5xl">😔</span>
                    <p className="mt-3">Tidak ada buku ditemukan.</p>
                  </div>
            }
          </div>

          {/* Pagination */}
          {meta.last_page > 1 && (
            <div className="flex justify-center gap-2 mt-8">
              {Array.from({ length: meta.last_page }, (_, i) => i + 1).map((page) => (
                <button key={page} onClick={() => updateFilter('page', page)}
                  className={`px-4 py-2 rounded-lg text-sm font-medium transition-colors ${
                    meta.current_page === page
                      ? 'bg-indigo-600 text-white'
                      : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50'
                  }`}>
                  {page}
                </button>
              ))}
            </div>
          )}
        </div>
      </div>
    </div>
  );
}
