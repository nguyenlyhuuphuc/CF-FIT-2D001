<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    public function index(){
        $title = 'MY TITLE';
        //Pass variable to view
        //C1:  
        return view('admin.pages.product_category.index', ['title' => $title]);
    }

    public function create(){
        return view('admin.pages.product_category.create');
    }
}
