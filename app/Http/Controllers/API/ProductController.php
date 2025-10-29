<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        if ($search = $request->search) {
            $query->where('name', 'like', "%$search%");
        }
        if ($category = $request->category) {
            $query->where('category', $category);
        }

        return response()->json($query->orderByDesc('id')->paginate(10));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'category' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product = Product::create($validated);
        return response()->json($product, 201);
    }

    public function show(Request $request)
    {
        $id = $request->id;

        if (!$id) {
            return response()->json([
                'status' => false,
                'message' => 'ID produk tidak ditemukan dalam request.'
            ], 400);
        }

        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Produk tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $product
        ]);
    }


   public function update(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|numeric|exists:products,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'category' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $product = Product::findOrFail($validated['id']);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        return response()->json([
            'status' => true,
            'message' => 'Produk berhasil diperbarui',
            'data' => $product,
        ]);
    }


    public function destroy(Request $request)
    {
        $id = $request->id;

        if (!$id) {
            return response()->json([
                'status' => false,
                'message' => 'ID produk harus dikirim.'
            ], 400);
        }

        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Produk tidak ditemukan.'
            ], 404);
        }

        // Hapus gambar jika ada
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return response()->json([
            'status' => true,
            'message' => 'Produk berhasil dihapus.'
        ]);
    }

}
