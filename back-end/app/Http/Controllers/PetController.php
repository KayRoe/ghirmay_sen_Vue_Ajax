<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Pet;


class PetController extends Controller {
    /**
     * Create a new controller instance.
     *
     * @return void
     */

     public function getAll() {
         $pets = Pet::select('id', 'name', 'species', 'sex')->get();
         return response()->json($pets);
     }

     public function getOne($id) {
         $pet = Pet::with('owner')->find($id);
         return response()->json($pet);
     }


 

}
