<?php

namespace App\Http\Controllers\Api\Visiteur;

use App\Http\Controllers\Controller;
use App\Models\Favory;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //Favoris

    // Liste User Favoris
    public function listfavoris(Request $request, $id) {
        try {

            // Trouver le visiteur par son ID
            $user = User::find($id);

            if(empty($user)) return response()->json(['data' => 'Cet utilisateur n\'existe pas', 'statut' => 404], 404);

            // Récupérer toutes les favoris de cet utilisateur
            $properties = $user->favoriteProperties;

            return response()->json(['data' => $properties, 'statut' => 200], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["errors" => $e->getMessage(), "statut" => 500], 500);
        }
    }

    // Ajouter / Retirer Favoris
    public function togglefavoris(Request $request, $iduser, $idproperty) {
        try {

            // Check existing favorite
            $favoris = Favory::where('user_id', $iduser)
                                ->where('property_id', $idproperty)
                                ->first();

            if ($favoris) {

                DB::beginTransaction();
                    $favoris->delete();
                DB::commit();

                return response()->json(["message" => "Favori retiré avec succès", "statut" => 200], 200);
            } else {

                DB::beginTransaction();
                    $favoris = Favory::create([
                        'property_id' => $idproperty,
                        'user_id' => $iduser,
                    ]);
                DB::commit();

                return response()->json(['message' => 'Favoris ajouté avec succès','data' => $favoris, 'statut' => 201], 201);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["errors" => $e->getMessage(), "statut" => 500], 500);
        }
    }
}
