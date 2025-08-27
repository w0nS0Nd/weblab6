<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    private function storeKey(): string { return 'lab_products'; }

    private function boot()
    {
        if (!Cache::has($this->storeKey())) {
            Cache::forever($this->storeKey(), [
                1 => ['id'=>1,'name'=>'Laptop','price'=>1200],
                2 => ['id'=>2,'name'=>'Headphones','price'=>150],
            ]);
        }
    }

    public function index()
    {
        $this->boot();
        return response()->json(array_values(Cache::get($this->storeKey(), [])));
    }

    public function show($id)
    {
        $this->boot();
        $items = Cache::get($this->storeKey(), []);
        return $items[$id] ?? response()->json(['message'=>'Not found'], 404);
    }

    public function store(Request $request)
    {
        $this->boot();
        $items = Cache::get($this->storeKey(), []);
        $id = (count($items) ? max(array_keys($items)) : 0) + 1;
        $item = ['id'=>$id, 'name'=>$request->input('name'), 'price'=>$request->input('price',0)];
        $items[$id] = $item;
        Cache::forever($this->storeKey(), $items);
        return response()->json($item, 201);
    }

    public function update($id, Request $request)
    {
        $this->boot();
        $items = Cache::get($this->storeKey(), []);
        if (!isset($items[$id])) return response()->json(['message'=>'Not found'], 404);
        $items[$id]['name'] = $request->input('name', $items[$id]['name']);
        $items[$id]['price'] = $request->input('price', $items[$id]['price']);
        Cache::forever($this->storeKey(), $items);
        return response()->json($items[$id]);
    }

    public function destroy($id)
    {
        $this->boot();
        $items = Cache::get($this->storeKey(), []);
        if (!isset($items[$id])) return response()->json(['message'=>'Not found'], 404);
        unset($items[$id]);
        Cache::forever($this->storeKey(), $items);
        return response()->json(['deleted'=>true]);
    }
}
