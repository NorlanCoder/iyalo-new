<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // Liste des catégories
    public function listcategory(Request $request) {
        try {
            $category = Category::all();
            return response()->json(['data' => $category, 'statut' => 200], 200);
        } catch (\Throwable $th) {
            throw $th;
        }

    }

    // Liste des proriétés par catégorie
    public function listcategoryproperty(Request $request, $id) {
        try {

            // Trouver la catégorie par son ID
                $category = Category::find($id);

                if(empty($category)) return response()->json(['data' => 'Cette categorie n\'existe pas', 'statut' => 404], 404);

            // Récupérer toutes les propriétés de cette catégorie
                $properties = $category->properties;

            return response()->json(['data' => $properties, 'statut' => 200], 200);
        } catch (\Throwable $th) {
            throw $th;
        }

    }

    // Ajouter une catégorie
    public function addcategory(Request $request) {
        try {

            // Validation
            $validation = Validator::make($request->all(), [
                'label' => 'required',
            ]);

            if ($validation->fails()) {
                return response()->json(["errors" => $validation->errors(), "statut" => 400], 400);
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

            return response()->json(['data' => $data, 'statut' => 201], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(["errors" => $e->getMessage(), "statut" => 500], 500);
        }

    }

    // Modifier catégorie
    public function updatecategory(Request $request, $id) {
        try {

            // Validation
            $validation = Validator::make($request->all(), [
                'label' => 'required'
            ]);

            if ($validation->fails()) {
                return response()->json(["errors" => $validation->errors(), "statut" => 400], 400);
            }

            // Trouver la catégorie par son ID
            $category = Category::find($id);


            if (!$category) {
                return response()->json(["errors" => "Catégorie non trouvée", "statut" => 404], 404);
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

            return response()->json(['data' => $data, 'statut' => 201], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(["errors" => $e->getMessage(), "statut" => 500], 500);
        }

    }

    // Modifier catégorie
    public function deletecategory(Request $request, $id) {
        try {

            // Trouver la catégorie par son ID
            $category = Category::find($id);


            if (!$category) {
                return response()->json(["errors" => "Catégorie non trouvée", "statut" => 404], 404);
            }

            DB::beginTransaction();

            $category->delete();

            DB::commit();

            $data = [
                'message' => "Catégorie supprimé avec succès",
            ];

            return response()->json(['data' => $data, 'statut' => 200], 200);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(["errors" => $e->getMessage(), "statut" => 500], 500);
        }

    }
}
