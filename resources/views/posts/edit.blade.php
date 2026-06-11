<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Artikel</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="bg-gray-100 p-6 md:p-12">

    <div class="max-w-2xl mx-auto bg-white p-8 rounded-xl shadow-sm border border-gray-200">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Edit Artikel</h1>
            <a href="{{ route('posts.index') }}" class="text-sm font-medium text-blue-600 hover:underline">← Batal</a>
        </div>

        <form action="{{ route('posts.update', $post->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Judul Artikel</label>
                <input type="text" name="title" value="{{ old('title', $post->title) }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 @error('title') border-red-500 @enderror">
                @error('title') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Isi Konten</label>
                <textarea name="content" rows="6"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 @error('content') border-red-500 @enderror">{{ old('content', $post->content) }}</textarea>
                @error('content') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Perbarui Kategori Artikel</label>
                <div class="flex flex-wrap gap-3">
                    @foreach($categories as $category)
                    <label class="inline-flex items-center text-sm text-gray-700 bg-gray-50 border border-gray-200 px-4 py-2 rounded-lg cursor-pointer hover:bg-gray-100 transition">
                        <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                            class="rounded text-blue-600 mr-2 focus:ring-blue-500"
                            @checked(in_array($category->id, old('categories', $post->categories->pluck('id')->toArray())))>
                        {{ $category->name }}
                    </label>
                    @endforeach
                </div>
                @error('categories') <p class="text-red-500 text-xs mt-2 font-medium">{{ $message }}</p> @enderror
            </div>

            <div class="pt-3">
                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2.5 rounded-lg shadow-sm transition text-sm">
                    Simpan Perubahan Artikel
                </button>
            </div>
        </form>
    </div>

</body>

</html>