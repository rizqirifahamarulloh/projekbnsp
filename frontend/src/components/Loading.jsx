export function Spinner() {
  return (
    <div className="flex justify-center items-center py-20">
      <div className="w-10 h-10 border-4 border-indigo-200 border-t-indigo-600 rounded-full animate-spin"></div>
    </div>
  );
}

export function SkeletonCard() {
  return (
    <div className="bg-white rounded-xl overflow-hidden shadow-sm">
      <div className="skeleton h-48 w-full"></div>
      <div className="p-4 space-y-3">
        <div className="skeleton h-4 w-3/4"></div>
        <div className="skeleton h-3 w-1/2"></div>
        <div className="skeleton h-5 w-1/3"></div>
      </div>
    </div>
  );
}
