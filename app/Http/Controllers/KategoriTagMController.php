<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Tag;
use Illuminate\Http\Request;

class KategoriTagMController extends Controller
{
    // Index Page with Switch
    public function index(Request $request)
    {
        $activeTab = $request->get('tab', 'kategori');
        $kategoris = Kategori::all();
        $tags = Tag::all();
        
        return view('pages.mahasiswa.kategori-tag.index', compact('kategoris', 'tags', 'activeTab'));
    }
}