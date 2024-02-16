<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua data news
        // sama dengan => Select * from news;
        $menus = Menu::all();
 
        // Tampilkan halaman index berita dengan rows berita 
        return view('dashboard', compact('menus'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Tampilkan halaman create berita
        return view('adm.menu.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi, 3 field ini diperlukan
        // Jika ada yang kurang, di redirect ke halaman sebelumnya dengan error
        $validated = $request->validate([
            'menu_code' => 'required|string|unique:menus',
            'name' => ['required'],
            'text' => ['required'],
            'price' => ['required'],
            'image' => ['required'],
        ]);

        // Acak nama gambar
        // Alasan acak nama gambar agar tidak ada 2 gambar dengan nama yang sama
        $imageName = $request->file('image')->hashName();

        // Taruh nama gambar baru ke array validated untuk nanti disimpan ke database
        $validated['image'] = $imageName;
        
        // Simpan gambar di folder public/news dengan nama yang diacak tadi
        $menuDirectory = public_path() . '/menuimg';
        $request->file('image')->move($menuDirectory, $imageName);
        
        // insert row baru di table news dengan data didalam validated
        Menu::create($validated);

        // Redirect ke halaman index
        return redirect()->route('dashboard')->with('success', 'Data berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Search news dengan id yang sedang diedit
        $menus = Menu::findorFail($id);
        $menu = Menu::all();

        // Ke halaman edit dengan data news yang sedang diedit
        return view('adm.menu.edit', compact('menus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validasi, bahwa 3 field ini diperlukan
        // Jika ada yang kurang, di redirect ke halaman sebelumnya dengan error
        $validated = $request->validate([
            'menu_code' => 'required|string|unique:menus',
            'name' => ['required'],
            'text' => ['required'],
            'price' => ['required'],
            'image' => ['image'], // Ubah 'required' menjadi 'image' agar gambar tidak wajib diisi
        ]);
    
        // Search menu dengan id yang sedang diedit
        $menu = Menu::find($id);
    
        // Cek apakah ada gambar baru yang diunggah
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            File::delete(public_path() . "/menuimg/$menu->image");
    
            // Acak nama gambar baru untuk menghindari duplikat
            $imageName = $request->file('image')->hashName();
    
            // Simpan gambar baru di folder public/menuimg
            $menuDirectory = public_path() . '/menuimg';
            $request->file('image')->move($menuDirectory, $imageName);
    
            // Set gambar baru ke dalam array validated untuk disimpan ke database
            $validated['image'] = $imageName;
        }
    
        // Update data menu dengan data baru termasuk gambar jika diunggah
        $menu->update($validated);
    
        // Redirect ke halaman index
        return redirect()->route('dashboard')->with('success', 'Data berhasil diedit.');
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Search news dengan id bersangkutan
        $menus = Menu::find($id);

        // Hapus gambar lama
        File::delete(public_path() . "/menuimg/$menus->image");

        // Hapus row dari table News
        $menus->delete();

        // Redirect ke halaman index
        return redirect()->route('dashboard')->with('success', 'Data berhasil dihapus.');        
    }
}
