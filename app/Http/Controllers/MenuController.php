<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->get('category');
        $sort = $request->get('sort', 'desc');

        $query = Menu::query();

        if ($category) {
            $query->where('category', $category);
        }

        if ($sort == 'asc') {
            $query->orderBy('name', 'asc');
        } elseif ($sort == 'desc') {
            $query->orderBy('created_at', 'desc');
        }

        $menus = $query->get();
        $totalMenus = $menus->count();

        return view('index', compact('menus', 'totalMenus', 'category', 'sort'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'stock' => 'nullable|integer',
            'category' => 'required|in:makanan,minuman',
            'photo' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

         $data = $request->all();

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('menu_photos', 'public');
            $data['photo'] = $path;
        }

        Menu::create($data);
        return redirect()->route('menu.index')->with('status', 'Menu berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);
        $data = $request->all();

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('menu_photos', 'public');
            $data['photo'] = $path;
        }

        $menu->update($data);
        return redirect()->route('menu.index')->with('status', 'Menu berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);
        $namaMenu = $menu->name;
        $menu->delete();

        return redirect()->route('menu.index')
            ->with('status', "Menu '{$namaMenu}' berhasil dihapus!");
    }
}
