<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // Liste des catégories
    public function listcategory(Request $request) {
        $category = Category::all();
    }

}
