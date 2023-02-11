<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateCategoryFormRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    private $category;
    private $totalpage = 10;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    public function index(Request $request)
    {
        $categories = $this->category->getResults($request->name);
        return response()->json($categories);
    }

    public function show($id)
    {
        $category = $this->category->find($id);

        if (!$category) {
            return response()->json(['error' => 'Not found'], 404);
        }

        return response()->json($category);
    }

    public function store(StoreUpdateCategoryFormRequest $request)
    {
        $category = $this->category->create($request->all());

        return response()->json($category, 201);
    }

    public function update(StoreUpdateCategoryFormRequest $request, $id)
    {
        $category = $this->category->find($id);

        if (!$category) {
            return response()->json(['error' => 'Not found'], 404);
        }
        $category->update($request->all());

        return response()->json($category);
    }

    public function destroy($id)
    {
        $category = $this->category->find($id);
        if (!$category) {
            return response()->json(['error' => 'Not found'], 404);
        }

        $category->delete();

        return response()->json(['success' => 'Category delete success'], 204);
    }

    public function products($id)
    {
        $category = $this->category->find($id);
        if (!$category) {
            return response()->json(['error' => 'Not found'], 404);
        }

        $products = $category->products()->paginate($this->totalpage);

        return response()->json([
            'category' => $category,
            'products' => $products,
        ]);
    }
}
