<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
{
    // 1. GET /posts (Halaman Daftar Post)
    public function index()
    {
        $posts = Post::with(['user.passport', 'categories'])->latest()->get();
        return view('posts.index', compact('posts'));
    }

    // 2. GET /posts/create (Halaman Form Tambah)
    public function create()
    {
        $categories = Category::all();
        $user = Auth::user();
        return view('posts.create', compact('categories', 'user'));
    }

    // 3. POST /posts (Proses Simpan Data Baru)
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
            'passport_number' => 'nullable|string|max:50',
        ]);

        $user = Auth::user();

        if (!$user->passport && $request->filled('passport_number')) {
            $user->passport()->create([
                'passport_number' => $request->passport_number,
                'expiry_date' => now()->addYears(5),
            ]);
        }

        $post = $user->posts()->create([
            'title' => $request->title,
            'content' => $request->content
        ]);

        $post->categories()->sync($request->categories);

        return redirect()->route('posts.index')->with('success', 'Post berhasil dibuat!');
    }

    // 4. GET /posts/{id}/edit (Halaman Form Edit)
    public function edit(Post $post)
    {
        // Kebijakan Keamanan (Opsional): Hanya pemilik post yang boleh mengedit
        if ($post->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit post ini.');
        }

        $categories = Category::all();
        return view('posts.edit', compact('post', 'categories'));
    }

    // 5. PUT/PATCH /posts/{id} (Proses Update Data)
    public function update(Request $request, Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
        ]);

        // Update data post (One to Many side)
        $post->update([
            'title' => $request->title,
            'content' => $request->content
        ]);

        // Update relasi kategori (Many to Many menggunakan sync)
        $post->categories()->sync($request->categories);

        return redirect()->route('posts.index')->with('success', 'Post berhasil diperbarui!');
    }
}