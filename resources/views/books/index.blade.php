<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Daftar Buku
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                @if(session('success'))
                    <div class="mb-4 text-green-600">{{ session('success') }}</div>
                @endif

                @role('manager')
                <a href="{{ route('books.create') }}"
                   class="mb-4 inline-block bg-blue-600 text-white px-4 py-2 rounded">
                    + Tambah Buku
                </a>
                @endrole

                <table class="w-full border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border p-2">No</th>
                            <th class="border p-2">Judul</th>
                            <th class="border p-2">Penulis</th>
                            <th class="border p-2">Stok</th>
                            <th class="border p-2">Harga</th>
                            <th class="border p-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($books as $i => $book)
                        <tr>
                            <td class="border p-2 text-center">{{ $i + 1 }}</td>
                            <td class="border p-2">{{ $book->judul }}</td>
                            <td class="border p-2">{{ $book->penulis }}</td>
                            <td class="border p-2 text-center">{{ $book->stok }}</td>
                            <td class="border p-2">Rp {{ number_format($book->harga, 0, ',', '.') }}</td>
                            <td class="border p-2 text-center">
                                <a href="{{ route('books.edit', $book->id) }}"
                                   class="text-blue-600">Edit</a>
                                @role('manager')
                                <form action="{{ route('books.destroy', $book->id) }}"
                                      method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 ml-2"
                                        onclick="return confirm('Yakin hapus?')">Hapus</button>
                                </form>
                                @endrole
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</x-app-layout>