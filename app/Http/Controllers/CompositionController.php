<?php

namespace App\Http\Controllers;

// Модели
use App\Product;
use App\ProductsCategory;
use App\Article;

use Illuminate\Http\Request;

class CompositionController extends Controller
{
    // Сущность над которой производит операции контроллер
    protected $entity_name = 'compositions';
    protected $entity_dependence = false;

    public function index()
    {
        //
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

     // --------------------------------------------- Ajax -------------------------------------------------

    public function ajax_add(Request $request)
    {

        $composition = Product::with(['unit', 'articles' => function ($query) {
            $query->whereNull('template');
        }])->findOrFail($request->id);

        $article = Article::with('compositions_values')->findOrFail($request->article_id);
        $compositions_values = $article->compositions_values->keyBy('product_id');

        return view($request->entity.'.compositions.composition', compact('composition', 'compositions_values'));
    }
    
    public function ajax_add_relation(Request $request)
    {

        $products_category = ProductsCategory::findOrFail($request->products_category_id);
        $products_category->compositions()->toggle([$request->id]);

        $composition = Product::findOrFail($request->id);

        return view($request->entity.'.compositions.composition', compact('composition'));
    }

    public function ajax_delete_relation(Request $request)
    {

        $products_category = ProductsCategory::findOrFail($request->products_category_id);
        $res = $products_category->compositions()->toggle([$request->id]);

        if ($res) {
            $result = [
                'error_status' => 0,
            ];
        } else {
            $result = [
                'error_message' => 'Не удалось удалить состав!',
                'error_status' => 1,
            ];
        }
        
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
}