<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Artikel Blog</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="bg-gray-100 p-6 md:p-12">

    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-8 bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Sistem Relasi Blog</h1>
                <p class="text-sm text-gray-500">Hai, {{ Auth::user()->name }} (Sedang Login)</p>
            </div>
            <a href="{{ route('posts.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg shadow-sm transition text-sm">
                + Tulis Artikel Baru
            </a>
        </div>

        @if(session('success'))
        <div class="bg-green-500 text-white p-4 rounded-xl mb-6 text-sm shadow-sm font-medium">
            ✓ {{ session('success') }}
        </div>
        @endif

        <div class="space-y-6">
            @forelse($posts as $post)
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 relative">

                @if($post->user_id === Auth::id())
                <div class="absolute top-6 right-6">
                    <a href="{{ route('posts.edit', $post->id) }}" class="text-sm bg-gray-100 hover:bg-gray-200 text-gray-600 px-3 py-1 rounded-md font-medium transition border border-gray-300 shadow-sm">
                        ✎ Edit
                    </a>
                </div>
                @endif

                <h2 class="text-xl font-bold text-gray-900 mb-2 pr-16">{{ $post->title }}</h2>
                <p class="text-gray-600 text-sm leading-relaxed mb-6">{{ $post->content }}</p>

                <div class="border-t border-gray-100 pt-4 flex flex-wrap gap-y-3 justify-between items-center text-xs text-gray-500">
                    <div class="space-y-1">
                        <p>Penulis: <span class="font-semibold text-gray-800">{{ $post->user->name }}</span></p>
                        <p>No. Passport:
                            <span class="font-mono bg-gray-100 text-gray-700 px-1.5 py-0.5 rounded border border-gray-200">
                                {{ $post->user->passport->passport_number ?? 'Belum ada passport' }}
                            </span>
                        </p>
                    </div>

                    <div class="flex items-center gap-1.5">
                        <span class="font-medium text-gray-600">Kategori:</span>
                        @foreach($post->categories as $category)
                        <span class="bg-blue-50 text-blue-700 px-2.5 py-1 rounded-md font-medium border border-blue-100">
                            {{ $category->name }}
                        </span>
                        @endforeach
                    </div>
                </div>

            </div>
            @empty
            <div class="text-center bg-white p-12 rounded-xl shadow-sm border border-gray-200 text-gray-400 italic">
                Belum ada data artikel. Silakan buat artikel pertama Anda!
            </div>
            @endforelse
        </div>
    </div>

</body>

</html>