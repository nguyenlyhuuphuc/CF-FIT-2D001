<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    public function index(){
        $title = 'MY TITLE ABC';
        $page = 27;
        //Pass variable to view
        //C1:  
        // return view('admin.pages.product_category.index', 
        // [
        //     'title' => $title,
        //     'page' => 10
        // ]);

        //C2
        // return view('admin.pages.product_category.index')
        // ->with('title', $title)
        // ->with('page', 19);

        //C3
        return view('admin.pages.product_category.index', compact('title', 'page'));
    }

    public function create(){
        return view('admin.pages.product_category.create');
    }

    public function store(){
        dd(1111);
    }
}
