<?php

namespace App\Http\Controllers;

use App\Models\Genres;
use Illuminate\Http\Request;

class GenresController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'isadmin'])->except(['index','show']);
    }
    public function index()
    {
        $genre = Genres::get();

        return response()->json([
            "message" => "Berhasil Tampil Genre",
            "data" => $genre
        ],200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:2,max:255',
        ],[
            'required' => 'The :attribute field is required ',
            'min' => 'inputan :attribute :min karakter'
        ]);

        Genres::create([
            'name' => $request->input('name')
        ]);

        return response([
            "message" => "Berhasil Tambah Genre"
        ], 201);
    }

    public function show(string $id)
    {
        $genre = Genres::with('listBook')->find($id);

        if(!$genre){
            return response([
                "message" => "Data dengan $id tidak ditemukan",
            ],404);
        }

        return response([
            "message" => "Berhasil Detail data dengan id $id",
            "data" => $genre
        ],200);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|min:2,max:255',
        ],[
            'required' => 'The :attribute field is required ',
            'min' => 'inputan :attribute :min karakter'
        ]);
        $genre = Genres::find($id);

        if(!$genre){
            return response([
                "message" => "Data dengan $id tidak ditemukan",
            ],404);
        }

        $genre->name = $request->input('name');

        $genre->save();

        return response([
            "message" => "Berhasil melakukan update Genre id $id",
        ],201);
    }

    public function destroy(string $id)
    {
        $genre = Genres::find($id);

        if(!$genre){
            return response([
                "message" => "Data dengan $id tidak ditemukan",
            ],404);
        }

        $genre->delete($id);
        return response([
            "message" => "data dengan id : $id berhasil terhapus"
        ],200);
    }
}
