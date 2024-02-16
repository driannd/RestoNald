<?php

namespace App\Http\Controllers;

use App\Models\Chef;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ChefController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       
        $chefs = Chef::all();

        // Tampilkan halaman index berita dengan rows berita 
        return view('chef', compact('chefs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Tampilkan halaman create berita
        return view('adm.chef.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi, 3 field ini diperlukan
        // Jika ada yang kurang, di redirect ke halaman sebelumnya dengan error
        $validated = $request->validate([
            'chef_id' => 'required|string|unique:chefs',
            'chef_name' => ['required'],
            'skill' => ['required'],
            'information' => ['required'],
            'image' => ['required'],
        ]);

        // Acak nama gambar
        // Alasan acak nama gambar agar tidak ada 2 gambar dengan nama yang sama
        $imageName = $request->file('image')->hashName();

        // Taruh nama gambar baru ke array validated untuk nanti disimpan ke database
        $validated['image'] = $imageName;
        
        // Simpan gambar di folder public/news dengan nama yang diacak tadi
        $chefDirectory = public_path() . '/chefimg';
        $request->file('image')->move($chefDirectory, $imageName);
        
        // insert row baru di table news dengan data didalam validated
        Chef::create($validated);

        // Redirect ke halaman index
        return redirect()->route('chef')->with('success', 'Data berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Search news dengan id yang sedang diedit
        $chefs = Chef::findorFail($id);
        $chef = Chef::all();

        // Ke halaman edit dengan data news yang sedang diedit
        return view('adm.chef.edit', compact('chefs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validasi, bahwa 3 field ini diperlukan
        // Jika ada yang kurang, di redirect ke halaman sebelumnya dengan error
        $validated = $request->validate([
            'chef_id' => 'required|string|unique:chefs',
            'chef_name' => ['required'],
            'skill' => ['required'],
            'information' => ['required'],
            'image' => ['image'], // Ubah 'required' menjadi 'image' agar gambar tidak wajib diisi
        ]);
    
        // Search menu dengan id yang sedang diedit
        $chef = Chef::find($id);
    
        // Cek apakah ada gambar baru yang diunggah
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            File::delete(public_path() . "/chefimg/$chef->image");
    
            // Acak nama gambar baru untuk menghindari duplikat
            $imageName = $request->file('image')->hashName();
    
            // Simpan gambar baru di folder public/menuimg
            $chefDirectory = public_path() . '/chefimg';
            $request->file('image')->move($chefDirectory, $imageName);
    
            // Set gambar baru ke dalam array validated untuk disimpan ke database
            $validated['image'] = $imageName;
        }
    
        // Update data menu dengan data baru termasuk gambar jika diunggah
        $chef->update($validated);
    
        // Redirect ke halaman index
        return redirect()->route('chef')->with('success', 'Data berhasil diedit.');
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Search news dengan id bersangkutan
        $chefs = Chef::find($id);

        // Hapus gambar lama
        File::delete(public_path() . "/chefimg/$chefs->image");

        // Hapus row dari table News
        $chefs->delete();

        // Redirect ke halaman index
        return redirect()->route('chef')->with('success', 'Data berhasil dihapus.');        
    }
}
