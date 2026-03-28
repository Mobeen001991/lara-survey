<?php

// app/Http/Controllers/ProductController.php
namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ProductController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('name')->get();
        return view('products.index', compact('categories'));
    }

    public function data(Request $request)
    {
        $query = Product::with('category');

        // apply nested (relationship) filter:
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        return DataTables::of($query)
            ->addColumn('category_name', fn(Product $p) => $p->category->name)
            ->filterColumn('category_name', function($query, $keyword) {
                // if you wanted to filter by category name text:
                $query->whereHas('category', fn($q) =>
                    $q->where('name','like',"%{$keyword}%")
                );
            })
            ->make(true);
    }
}
