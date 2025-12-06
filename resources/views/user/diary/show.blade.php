@extends('layouts.user')

@section('title', 'Detail Diary')

@section('content')
<div class="max-w-6xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <a href="{{ route('user.diary.index') }}"
                class="flex items-center text-gray-600 hover:text-indigo-600 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                        clip-rule="evenodd" />
                </svg>
                Kembali ke Daftar
            </a>

            <div class="flex gap-3">
                
                <button type="button" onclick="confirmDelete()"
                    class="px-4 py-2 bg-red-100 text-red-700 rounded-lg text-sm font-medium hover:bg-red-200 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Hapus
                </button>

                <form id="delete-form" action="{{ route('user.diary.destroy', $diary->id) }}" method="POST" class="hidden">
                @csrf
                @method('DELETE')
        </form>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden h-full">
                    {{-- Header Diary --}}
                    <div class="border-b border-gray-100 px-6 py-4 bg-gray-50/50 flex justify-between items-center">
                        <div class="flex items-center gap-2 text-sm text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            {{ $diary->created_at->translatedFormat('l, d F Y • H:i') }} WIB
                        </div>
                        @if ($diary->is_private)
                            <span
                                class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                    </path>
                                </svg>
                                Private
                            </span>
                        @else
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Public
                            </span>
                        @endif
                    </div>

                    {{-- Isi Konten --}}
                    <div class="p-8">
                        <article class="prose prose-indigo max-w-none text-gray-700 leading-relaxed whitespace-pre-line">
                            {{ $diary->content }}
                        </article>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1 space-y-6">

                @if ($diary->analysis)
                    <div class="bg-gradient-to-br from-indigo-600 to-purple-700 rounded-2xl shadow-lg text-white p-6 relative overflow-hidden">
                        <div class="absolute top-0 right-0 -mr-4 -mt-4 w-24 h-24 rounded-full bg-white/10 blur-xl"></div>
                        <div class="absolute bottom-0 left-0 -ml-4 -mb-4 w-20 h-20 rounded-full bg-white/10 blur-xl"></div>

                        <h3 class="text-indigo-100 font-medium text-sm mb-4 uppercase tracking-wider">Analisis VibeSense</h3>

                        <div class="flex items-end justify-between mb-2">
                            <span class="text-3xl font-bold">{{ $diary->analysis->mood }}</span>
                            <span class="text-xl font-medium bg-white/20 px-2 py-1 rounded-lg">
                                {{ $diary->analysis->mood_score }}/100
                            </span>
                        </div>

                        <div class="w-full bg-black/20 rounded-full h-2.5 mb-6">
                            <div class="bg-white h-2.5 rounded-full transition-all duration-1000"
                                style="width: {{ $diary->analysis->mood_score }}%"></div>
                        </div>

                        <div class="bg-white/10 rounded-xl p-4 backdrop-blur-sm border border-white/10">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 mt-1 text-yellow-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-sm leading-relaxed text-indigo-50 italic">
                                    "{{ $diary->analysis->reflection }}"
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="p-2 bg-green-100 rounded-lg text-green-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                </svg>
                            </div>
                            <h4 class="font-bold text-gray-800">Saran Tindakan</h4>
                        </div>
                        <p class="text-gray-600 text-sm leading-relaxed">
                            {{ $diary->analysis->habit_insight }}
                        </p>
                    </div>

                @else
                    <div class="bg-white rounded-2xl shadow-sm border border-orange-100 p-6 text-center">
                        <div class="inline-flex items-center justify-center w-12 h-12 bg-orange-100 text-orange-500 rounded-full mb-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <h4 class="font-bold text-gray-800 mb-2">Analisis Belum Tersedia</h4>
                        <p class="text-gray-500 text-sm mb-4">
                            Mungkin terjadi gangguan koneksi saat menyimpan, atau AI sedang istirahat.
                        </p>
                        <button class="text-indigo-600 font-semibold text-sm hover:underline">
                            Coba Analisis Sekarang
                        </button>
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete() {
        Swal.fire({
            title: 'Hapus Diary Ini?',
            text: "Diary ini akan dihapus permanen dan tidak bisa dikembalikan.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444', // Warna Merah Tailwind
            cancelButtonColor: '#6b7280', // Warna Abu Tailwind
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Jika user klik Ya, submit form tersembunyi
                document.getElementById('delete-form').submit();
            }
        })
    }
</script>