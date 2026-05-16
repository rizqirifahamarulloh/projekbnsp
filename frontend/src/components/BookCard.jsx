import { Link } from 'react-router-dom';

// Warna gradien untuk placeholder cover buku
const coverColors = [
  'from-blue-400 to-indigo-500',
  'from-purple-400 to-pink-500',
  'from-green-400 to-teal-500',
  'from-orange-400 to-red-500',
  'from-cyan-400 to-blue-500',
  'from-yellow-400 to-orange-500',
  'from-pink-400 to-rose-500',
  'from-emerald-400 to-green-500',
];

export default function BookCard({ book }) {
  const formatPrice = (p) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(p);

  // Gradient berdasarkan ID buku agar konsisten
  const colorClass = coverColors[(book.id || 0) % coverColors.length];

  // Cover image URL — dari storage atau placeholder gradien
  const coverUrl = book.cover_image
    ? (book.cover_image.startsWith('http') ? book.cover_image : `http://localhost:8000/storage/${book.cover_image}`)
    : null;

  return (
    <Link to={`/books/${book.slug}`}
      className="group bg-white rounded-xl overflow-hidden shadow-sm border border-gray-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
      {/* Cover */}
      <div className="aspect-[3/4] overflow-hidden relative">
        {coverUrl ? (
          <img src={coverUrl} alt={book.title}
            className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
        ) : (
          <div className={`w-full h-full bg-gradient-to-br ${colorClass} flex flex-col items-center justify-center p-4 group-hover:scale-105 transition-transform duration-300`}>
            <span className="text-5xl mb-3 opacity-80">📖</span>
            <p className="text-white text-center font-bold text-sm leading-tight line-clamp-3">{book.title}</p>
            <p className="text-white/70 text-xs mt-1">{book.author}</p>
          </div>
        )}

        {/* Badge kategori */}
        <span className="absolute top-2 left-2 bg-white/90 backdrop-blur-sm text-xs px-2 py-0.5 rounded-full font-medium text-indigo-600">
          {book.category?.name}
        </span>

        {/* Badge stok habis */}
        {book.stock === 0 && (
          <div className="absolute inset-0 bg-black/40 flex items-center justify-center">
            <span className="bg-red-500 text-white px-3 py-1 rounded-full text-sm font-bold">Stok Habis</span>
          </div>
        )}
      </div>

      {/* Info */}
      <div className="p-3">
        <h3 className="font-semibold text-gray-800 text-sm line-clamp-1 group-hover:text-indigo-600 transition-colors">{book.title}</h3>
        <p className="text-xs text-gray-500 mt-0.5">{book.author}</p>
        <div className="flex items-center justify-between mt-2">
          <span className="text-indigo-600 font-bold text-sm">{formatPrice(book.price)}</span>
          {book.stock > 0 && book.stock <= 5 && (
            <span className="text-[10px] px-1.5 py-0.5 bg-orange-100 text-orange-600 rounded-full font-medium">
              Sisa {book.stock}
            </span>
          )}
        </div>
      </div>
    </Link>
  );
}
