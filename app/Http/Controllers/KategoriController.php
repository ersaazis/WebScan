<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Kategori;
use Validator;

class KategoriController extends Controller
{
    //list Kategori
    public function index(Request $request, $p=10)
    {
        if(!is_numeric($p))
            $p=10;

        $kategori=Kategori::paginate($p);
        return response()->json([
            'success' => true,
            'messages' => 'Success !',
            'data' => $kategori,
        ], 200);
    }
    //get Kategori
    public function show($id)
    {
        $kategori=Kategori::find($id);
        if(!empty($kategori))
            return response()->json([
                'success' => true,
                'messages' => 'Success !',
                'data' => $kategori,
            ], 200);
        else
            return response()->json([
                'success' => false,
                'messages' => 'kategori Not Found !',
                'data' => '',
            ], 404);
    }
    //input kategori
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
        ]);
        if ($validator->fails()){
            return response()->json([
                'success' => false,
                'messages' => 'Add Kategori Fail !',
                'data' => $validator->errors(),
            ], 400);
        }
        $name = $request->input('name');

        $kategori =Kategori::create([
            'name' => $name,
        ]);
        if($kategori){
            return response()->json([
                'success' => true,
                'messages' => 'Add kategori Success !',
                'data' => $kategori
            ], 201);
        }
        else {
            return response()->json([
                'success' => false,
                'messages' => 'Add kategori Fail !',
                'data' => ''
            ], 400);
        }
    }
    //update kategori
    public function update(Request $request, $id)
    {
        $kategori=Kategori::find($id);
        if(!empty($kategori)){
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255'
            ]);
            if ($validator->fails()){
                return response()->json([
                    'success' => false,
                    'messages' => 'Update Kategori Fail !',
                    'data' => $validator->errors(),
                ], 400);
            }

            $name = $request->input('name');
            $kategori->update([
                'name' => $name
            ]);
            return response()->json([
                'success' => true,
                'messages' => 'Update Kategori Success !',
                'data' => ''
            ], 201);
        }
        else
            return response()->json([
                'success' => false,
                'messages' => 'Kategori not found !',
                'data' => ''
            ], 404);
    }
    //delete kategori
    public function destroy($id)
    {
        $kategori=Kategori::find($id);
        if(!empty($kategori)){
            $kategori->delete();
            return response()->json([
                'success' => true,
                'messages' => 'Delete Kategori Success !',
                'data' => ''
            ], 201);
        }
        else
            return response()->json([
                'success' => false,
                'messages' => 'Kategori Not Found !',
                'data' => ''
            ], 404);
    }
    //filrer kategori
    public function filter(Request $request, $p=10)
    {
        if(!is_numeric($p))
            $p=10;

        $validator = Validator::make($request->all(), [
            'search' => 'required|min:3',
        ]);
        if ($validator->fails()){
            return response()->json([
                'success' => false,
                'messages' => 'filter Kategori Fail !',
                'data' => $validator->errors(),
            ], 400);
        }
        $search = $request->input('search');

        $kategori=Kategori::where('name','like','%'.$search.'%')->paginate($p);

        return response()->json([
            'success' => true,
            'messages' => 'Success !',
            'data' => $kategori,
        ], 200);
    }
}
