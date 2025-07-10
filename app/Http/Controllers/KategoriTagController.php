<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Tag;
use Illuminate\Http\Request;

class KategoriTagController extends Controller
{
    // Index Page with Switch
    public function index(Request $request)
    {
        $activeTab = $request->get('tab', 'kategori');
        $kategoris = Kategori::all();
        $tags = Tag::all();
        
        return view('pages.admin.kategori-tag.index', compact('kategoris', 'tags', 'activeTab'));
    }

    // Kategori Methods
    public function createKategori()
    {
        return view('pages.admin.kategori-tag.create-kategori');
    }

    public function storeKategori(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|unique:kategoris|max:255'
        ]);

        Kategori::create($request->all());
        return redirect()->route('admin.kategori-tag.index', ['tab' => 'kategori'])
                         ->with('success', 'Kategori berhasil ditambahkan');
    }

    public function editKategori($id)
    {
        $kategori = Kategori::findOrFail($id);
        return view('pages.admin.kategori-tag.edit-kategori', compact('kategori'));
    }

    public function updateKategori(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required|unique:kategoris,nama_kategori,'.$id.'|max:255'
        ]);

        $kategori = Kategori::findOrFail($id);
        $kategori->update($request->all());
        return redirect()->route('admin.kategori-tag.index', ['tab' => 'kategori'])
                         ->with('success', 'Kategori berhasil diperbarui');
    }

    public function destroyKategori($id)
    {
        Kategori::destroy($id);
        return redirect()->route('admin.kategori-tag.index', ['tab' => 'kategori'])
                         ->with('success', 'Kategori berhasil dihapus');
    }

    // Tag Methods
    public function createTag()
    {
        return view('pages.admin.kategori-tag.create-tag');
    }

    public function storeTag(Request $request)
    {
        $request->validate([
            'nama_tag' => 'required|unique:tags|max:255'
        ]);

        Tag::create($request->all());
        return redirect()->route('admin.kategori-tag.index', ['tab' => 'tag'])
                         ->with('success', 'Tag berhasil ditambahkan');
    }

    public function editTag($id)
    {
        $tag = Tag::findOrFail($id);
        return view('pages.admin.kategori-tag.edit-tag', compact('tag'));
    }

    public function updateTag(Request $request, $id)
    {
        $request->validate([
            'nama_tag' => 'required|unique:tags,nama_tag,'.$id.'|max:255'
        ]);

        $tag = Tag::findOrFail($id);
        $tag->update($request->all());
        return redirect()->route('admin.kategori-tag.index', ['tab' => 'tag'])
                         ->with('success', 'Tag berhasil diperbarui');
    }

    public function destroyTag($id)
    {
        Tag::destroy($id);
        return redirect()->route('admin.kategori-tag.index', ['tab' => 'tag'])
                         ->with('success', 'Tag berhasil dihapus');
    }
}