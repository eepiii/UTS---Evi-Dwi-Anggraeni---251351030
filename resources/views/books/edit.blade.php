<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Buku
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <form action="{{ route('books.update', $book->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    @role('manager')
                    <div class="mb-4">
                        <label class="block text-gray-700">Judul</label>
                        <input type="text" name="judul" value="{{ $book->judul }}"
                            class="w-full border rounded p-2 mt-1">
                        @error('judul') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700">Penulis</label>
                        <input type="text" name="penulis" value="{{ $book->penulis }}"
                            class="w-full border rounded p-2 mt-1">
                        @error('penulis') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                    </div>
                    @endrole

                    <div class="mb-4">
                        <label class="block text-gray-700">Stok</label>
                        <input type="number" name="stok" value="{{ $book->stok }}"
                            class="w-full border rounded p-2 mt-1">
                        @error('stok') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                    </div>

                    @role('manager')
                    <div class="mb-4">
                        <label class="block text-gray-700">Harga</label>
                        <input type="number" name="harga" value="{{ $book->harga }}"
                            class="w-full border rounded p-2 mt-1">
                        @error('harga') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                    </div>
                    @endrole

                    <button type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded">Update</button>
                    <a href="{{ route('books.index') }}"
                        class="ml-2 text-gray-600">Batal</a>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>