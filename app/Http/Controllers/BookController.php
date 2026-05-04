<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::all();
        return view('books.index', compact('books'));
    }

    public function create()
    {
        if (!auth()->user()->hasRole('manager')) abort(403);
        return view('books.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->hasRole('manager')) abort(403);
        $request->validate([
            'judul'   => 'required',
            'penulis' => 'required',
            'stok'    => 'required|integer',
            'harga'   => 'required|numeric',
        ]);
        Book::create($request->all());
        return redirect()->route('books.index')->with('success', 'Buku berhasil ditambahkan!');
    }

    public function edit(Book $book)
    {
        return view('books.edit', compact('book'));
    }

    public function update(Request $request, Book $book)
    {
        if (auth()->user()->hasRole('manager')) {
            $request->validate([
                'judul'   => 'required',
                'penulis' => 'required',
                'stok'    => 'required|integer',
                'harga'   => 'required|numeric',
            ]);
            $book->update($request->all());
        } else {
            // Staff hanya boleh update stok
            $request->validate(['stok' => 'required|integer']);
            $book->update(['stok' => $request->stok]);
        }
        return redirect()->route('books.index')->with('success', 'Buku berhasil diupdate!');
    }

    public function destroy(Book $book)
    {
        if (!auth()->user()->hasRole('manager')) abort(403);
        $book->delete();
        return redirect()->route('books.index')->with('success', 'Buku berhasil dihapus!');
    }
}