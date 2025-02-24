<?php

namespace App\Http\Controllers;

use App\Models\Books;
use Illuminate\Http\Request;

class BooksController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'isadmin'])->except(['index', 'show', 'getBookS']);
    }
    public function index(Request $request)
    {
        // $books = Book::get();


        $query = Books::query();
        if ($request->has("search")) {
            $searching = $request->input("search");
            $query->where('name', "LIKE", "%$searching");
        };

        $perpage = $request->input('per_page', 10);

        $books = $query->paginate($perpage);

        return response([
            "message" => "tampil data berhasil",
            "data" => $books
        ], 200);
    }

    public function getBook()
    {
        $books = Books::get();

        return response()->json([
            "message" => "Berhasil Tampil Books",
            "data" => $books
        ], 200);
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:2,max:255',
            'price' => 'required|integer',
            'author' => 'required|min:2,max:255',
            'year' => 'required|integer',
            'description' => 'required|min:2,max:255',
            'cover_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'stock' => 'required|integer',
            'genre_id' => 'required|exists:genres,id',
        ], [
            'required' => 'The :attribute harus diisi tidak boleh kosong ',
            'min' => 'inputan :attribute :min karakter',
            'max' => 'inputan :attribute :max karakter',
            'mimes' => 'inputan :attribute harus berformat jpeg,png,jpg,gif',
            'image' => 'inputan :attribute harus gambar',
            'exist' => 'inputan :attribute tidak ditemukan di table genres',
            'integer' => 'inputan harus berupa angka'
        ]);

        $uploadedFileUrl = cloudinary()->upload($request->file('cover_image')->getRealPath(), [
            'folder' => 'final',
        ])->getSecurePath();

        $books = new Books;

        $books->name = $request->input('name');
        $books->price = $request->input('price');
        $books->author = $request->input('author');
        $books->year = $request->input('year');
        $books->description = $request->input('description');
        $books->cover_image = $uploadedFileUrl;
        $books->stock = $request->input('stock');
        $books->genre_id = $request->input('genre_id');

        $books->save();

        return response()->json([
            "message" => "Data berhasil ditambahkan",
        ], 201);
    }

    public function show(string $id)
    {
        $books = Books::with(['category', 'listOrders'])->find($id);

        if (!$books) {
            return response([
                "message" => "Data dengan $id tidak ditemukan",
            ], 404);
        }

        return response([
            "message" => "Data Detail ditampilkan",
            "data" => $books
        ], 200);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|min:2,max:255',
            'price' => 'required|integer',
            'author' => 'required|min:2,max:255',
            'year' => 'required|integer',
            'description' => 'required|min:2,max:255',
            'cover_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'stock' => 'required|integer',
            'genre_id' => 'required|exists:genres,id',
        ], [
            'required' => ':attribute harus diisi tidak boleh kosong ',
            'min' => 'inputan :attribute :min karakter',
            'max' => 'inputan :attribute :max karakter',
            'mimes' => 'inputan :attribute harus berformat jpeg,png,jpg,gif',
            'image' => 'inputan :attribute harus gambar',
            'exists' => 'inputan :attribute tidak ditemukan di tabel genres',
            'integer' => 'inputan harus berupa angka'
        ]);

        $books = Books::find($id);

        if (!$books) {
            return response([
                "message" => "Data dengan ID $id tidak ditemukan",
            ], 404);
        }


        if ($request->hasFile('cover_image')) {
            $uploadedFileUrl = cloudinary()->upload($request->file('cover_image')->getRealPath(), [
                'folder' => 'final',
            ])->getSecurePath();
            $books->cover_image = $uploadedFileUrl;
        }

        $books->name = $request->input('name');
        $books->price = $request->input('price');
        $books->author = $request->input('author');
        $books->year = $request->input('year');
        $books->description = $request->input('description');
        $books->stock = $request->input('stock');
        $books->genre_id = $request->input('genre_id');

        $books->save();

        return response([
            "message" => "Data berhasil diperbarui",
        ], 200);
    }


    public function destroy(string $id)
    {
        $books = Books::find($id);

        if (!$books) {
            return response([
                "message" => "Data dengan $id tidak ditemukan",
            ], 404);
        }

        $books->delete();
        return response([
            "message" => "berhasil Menghapus books"
        ], 200);
    }
}
