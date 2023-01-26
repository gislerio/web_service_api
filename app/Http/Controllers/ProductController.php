<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUpdateProductFormRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    private $product;
    private $Totalpage = 5;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $products = $this->product->getResults($request->all(), $this->Totalpage);

        return response()->json($products);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUpdateProductFormRequest $request)
    {
        $data = $request->all();
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $name = Str::kebab($request->name);
            $extension = $request->file('image')->extension();

            $nameFile = "$name" . ".$extension";
            $data['image'] = $nameFile;

            $upload = $request->image->storeAs('products', $nameFile);

            if (!$upload) {
                return response()->json(['error' => 'Fail_Upload'], 500);
            }
        }
        $product = $this->product->create($data);
        return response()->json($product, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = $this->product->find($id);
        if (!$product) {
            return response()->json(['error' => 'Not found'], 404);
        }

        return response()->json($product);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreUpdateProductFormRequest $request, $id)
    {
        $product = $this->product->find($id);
        if (!$product) {
            return response()->json(['error' => 'Not found'], 404);
        }
        $product->update($request->all());
        return response()->json($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = $this->product->find($id);
        if (!$product) {
            return response()->json(['error' => 'Not found'], 404);
        }

        $product->delete();

        return response()->json(['success' => true, 204]);
    }
}
