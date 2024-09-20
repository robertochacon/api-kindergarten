<?php

namespace App\Http\Controllers;

use App\Models\Authorizations;
use App\Models\Kids;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Exception;
use Illuminate\Support\Facades\DB;

class AuthorizedPersonsController extends Controller
{

    public function index()
    {
        $authorizations = Authorizations::with('kid')->with('tutor')->paginate(10);
        return response()->json(["data"=>$authorizations],200);
    }


    public function watch($id){
        try{
            $authorization = Authorizations::with('kid')->with('tutor')->find($id);
            return response()->json(["data"=>$authorization],200);
        }catch (Exception $e) {
            return response()->json(["data"=>"none"],200);
        }
    }

    public function register(Request $request)
    {
        try {
            $authorization = new Authorizations($request->all());
            $authorization->save();
            return response()->json(["data" => $authorization], 200);

        } catch (Exception $e) {
            return response()->json(["data"=>"Problemas tecnicos"],500);
        }
    }

    public function update(Request $request, $id){
        try{
            $authorization = Authorizations::where('id',$id)->first();
            $authorization->update($request->all());
            return response()->json(["data"=>"ok"],200);
        }catch (Exception $e) {
            return response()->json(["data"=>"Problemas tecnicos"],500);
        }
    }

    public function delete($id){
        try{
            Authorizations::destroy($id);
            return response()->json(["data"=>"ok"],200);
        }catch (Exception $e) {
            return response()->json(["data"=>"Problemas tecnicos"],500);
        }
    }

}
