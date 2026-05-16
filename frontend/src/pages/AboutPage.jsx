export default function AboutPage() {
  return (
    <div className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      {/* Header */}
      <div className="text-center mb-12">
        <h1 className="text-4xl font-extrabold text-gray-900">Tentang Kami</h1>
        <p className="text-gray-500 mt-3 max-w-2xl mx-auto">
          BookWise adalah platform toko buku online yang dibangun untuk memudahkan masyarakat Indonesia dalam menemukan dan membeli buku berkualitas.
        </p>
      </div>

      {/* Visi & Misi */}
      <div className="grid md:grid-cols-2 gap-8 mb-16">
        <div className="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-2xl p-8">
          <h2 className="text-2xl font-bold text-indigo-700 mb-4">🎯 Visi</h2>
          <p className="text-gray-600 leading-relaxed">
            Menjadi platform toko buku online terpercaya dan terlengkap di Indonesia, yang menghubungkan pembaca dengan ilmu pengetahuan melalui teknologi modern.
          </p>
        </div>
        <div className="bg-gradient-to-br from-green-50 to-teal-50 rounded-2xl p-8">
          <h2 className="text-2xl font-bold text-green-700 mb-4">🚀 Misi</h2>
          <ul className="text-gray-600 space-y-2">
            <li>✅ Menyediakan koleksi buku berkualitas dari berbagai genre</li>
            <li>✅ Memberikan pengalaman belanja yang mudah dan menyenangkan</li>
            <li>✅ Mendukung literasi dan budaya membaca di Indonesia</li>
            <li>✅ Menggunakan teknologi terkini untuk layanan terbaik</li>
          </ul>
        </div>
      </div>

      {/* Tim */}
      <div className="text-center mb-8">
        <h2 className="text-2xl font-bold text-gray-900">👥 Tim Kami</h2>
        <p className="text-gray-500 mt-2">Dibangun oleh pengembang yang berdedikasi</p>
      </div>
      <div className="grid sm:grid-cols-3 gap-8 max-w-3xl mx-auto">
        {[
          { name: 'Developer', role: 'Full-Stack Developer', emoji: '👨‍💻' },
          { name: 'Designer', role: 'UI/UX Designer', emoji: '🎨' },
          { name: 'Manager', role: 'Project Manager', emoji: '📊' },
        ].map((member, i) => (
          <div key={i} className="bg-white rounded-2xl p-6 text-center shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div className="text-5xl mb-3">{member.emoji}</div>
            <h3 className="font-semibold text-gray-800">{member.name}</h3>
            <p className="text-sm text-gray-500">{member.role}</p>
          </div>
        ))}
      </div>
    </div>
  );
}
