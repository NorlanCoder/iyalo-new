<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    /**
     * All Category
     *
     * @unauthenticated
     * @return \Illuminate\Http\Response
     *
     */
    public function listcategory(Request $request) {
        try {
            $category = Category::all();
            return response()->json(['data' => $category, 'status' => 200], 200);
        } catch (\Exception $e) {
            return response()->json(["errors" => $e->getMessage(),"status" => 500], 500);
        }

    }

    /**
     * Porperty by Category
     *
     * @unauthenticated
     * @return \Illuminate\Http\Response
     *
     */
    public function listcategoryproperty(Request $request, $id) {
        try {

            // Trouver la catégorie par son ID
                $category = Category::find($id);

                if(empty($category)) return response()->json(['data' => 'Cette categorie n\'existe pas', 'status' => 404], 404);

            // Récupérer toutes les propriétés de cette catégorie
                $properties = $category->properties;

            return response()->json(['data' => $properties, 'status' => 200], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["errors" => $e->getMessage(), "status" => 500], 500);
        }

    }

    /**
     * Add Category
     *
     * @return \Illuminate\Http\Response
     *
     */
    public function addcategory(Request $request) {
        try {

            // Validation
            $validation = Validator::make($request->all(), [
                'label' => 'required',
            ]);

            if ($validation->fails()) {
                return response()->json(["errors" => $validation->errors(), "status" => 400], 400);
            }

            DB::beginTransaction();

            $categoory = Category::create([
                'label' => $request->label
            ]);

            DB::commit();

            $data = [
                'message' => "Catégorie créer avec succès",
                'categorie' => $categoory,
            ];

            return response()->json(['data' => $data, 'status' => 201], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["errors" => $e->getMessage(), "status" => 500], 500);
        }

    }

    /**
     * Update Category
     *
     * @return \Illuminate\Http\Response
     *
     */
    public function updatecategory(Request $request, $id) {
        try {

            // Validation
            $validation = Validator::make($request->all(), [
                'label' => 'required'
            ]);

            if ($validation->fails()) {
                return response()->json(["errors" => $validation->errors(), "status" => 400], 400);
            }

            // Trouver la catégorie par son ID
            $category = Category::find($id);


            if (!$category) {
                return response()->json(["errors" => "Catégorie non trouvée", "status" => 404], 404);
            }

            DB::beginTransaction();

            $category->update([
                'label' => $request->label
            ]);
            $category->save();

            DB::commit();

            $data = [
                'message' => "Catégorie mis-à-jour avec succès",
                'categorie' => $category,
            ];

            return response()->json(['data' => $data, 'status' => 201], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(["errors" => $e->getMessage(), "status" => 500], 500);
        }

    }

    /**
     * Delete Category
     *
     * @return \Illuminate\Http\Response
     *
     */
    public function deletecategory(Request $request, $id) {
        try {

            // Trouver la catégorie par son ID
            $category = Category::find($id);


            if (!$category) {
                return response()->json(["errors" => "Catégorie non trouvée", "status" => 404], 404);
            }

            DB::beginTransaction();

            $category->delete();

            DB::commit();

            $data = [
                'message' => "Catégorie supprimé avec succès",
            ];

            return response()->json(['data' => $data, 'status' => 200], 200);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(["errors" => $e->getMessage(), "status" => 500], 500);
        }

    }
}
