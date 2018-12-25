<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\News;
use App\Kategori;
use App\KategoriNews;
use Validator;
use DB;

class NewsController extends Controller
{
    //list News and pagination
    public function index(Request $request, $p=10)
    {
        if(!is_numeric($p))
            $p=10;
        $page=$request->input('page');
        if($page == 0 or !is_numeric($page))
            $page=1;

        $query=News::join('kategori_news','news.id','=','kategori_news.id_news')
                    ->join('kategori','kategori.id','=','kategori_news.id_kategori');
        $kategori=$query->select('kategori_news.id_news','kategori.*')->skip(($page-1)*$p)->take($p)->get();
        $news=$query->select('news.*')->groupBy('news.id')->paginate($p);

        return response()->json([
            'success' => true,
            'messages' => 'Success !',
            'data' => [
                'news' => $news,
                'kategori' => $kategori
            ]
        ], 200);
    }
    //get News
    public function show($id)
    {
        $query=News::join('kategori_news','news.id','=','kategori_news.id_news')
            ->join('kategori','kategori.id','=','kategori_news.id_kategori')
            ->where('news.id',$id);
        $kategori=$query->select('kategori_news.id_news','kategori.*')->get();
        $news=$query->select('news.*')->groupBy('news.id')->first();

        if(!empty($news))
            return response()->json([
                'success' => true,
                'messages' => 'Success !',
                'data' => [
                    'news' => $news,
                    'kategori' => $kategori
                ]
            ], 200);
        else
            return response()->json([
                'success' => false,
                'messages' => 'News Not Found !',
                'data' => '',
            ], 404);
    }
    //input News
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|max:255',
            'isi' => 'required|max:255',
            'foto' => 'required|image',
            'kategori.*' => 'required',
            'kategori' => 'required|array',
        ]);
        if ($validator->fails()){
            return response()->json([
                'success' => false,
                'messages' => 'Add News Fail !',
                'data' => $validator->errors(),
            ], 400);
        }
        $judul = $request->input('judul');
        $isi = $request->input('isi');
        //$foto = $request->input('foto');
        $kategori = $request->input('kategori');

        $news =News::create([
            'judul' => $judul,
            'isi' => $isi,
        //    'foto' => $foto,
        ]);
        foreach ($kategori as $k) {
            $cekKategori=Kategori::find($k);
            if($cekKategori)
                KategoriNews::create(['id_news'=>$news->id,'id_kategori'=>$k]);
        }
        if($news){
            return response()->json([
                'success' => true,
                'messages' => 'Add News Success !',
                'data' => $news
            ], 201);
        }
        else {
            return response()->json([
                'success' => false,
                'messages' => 'Add News Fail !',
                'data' => ''
            ], 400);
        }
    }
    //update News
    public function update(Request $request, $id)
    {
        $news=News::find($id);
        if(!empty($news)){
            $validator = Validator::make($request->all(), [
                'judul' => 'required|max:255',
                'isi' => 'required|max:255',
                'foto' => 'required|image',
                'kategori.*' => 'required',
                'kategori' => 'required|array',
            ]);
            if ($validator->fails()){
                return response()->json([
                    'success' => false,
                    'messages' => 'Update News Fail !',
                    'data' => $validator->errors(),
                ], 400);
            }

            $judul = $request->input('judul');
            $isi = $request->input('isi');
            //$foto = $request->input('foto');
            $kategori = $request->input('kategori');
    
            $news->update([
                'judul' => $judul,
                'isi' => $isi,
            //    'foto' => $foto,
            ]);
            KategoriNews::where('id_news','=',$news->id)->delete();
            foreach ($kategori as $k) {
                $cekKategori=Kategori::find($k);
                if($cekKategori)
                   KategoriNews::create(['id_news'=>$news->id,'id_kategori'=>$k]);
            }
            return response()->json([
                'success' => true,
                'messages' => 'Update News Success !',
                'data' => $news
            ], 201);
        }
        else
            return response()->json([
                'success' => false,
                'messages' => 'News not found !',
                'data' => ''
            ], 404);
    }
    //delete News
    public function destroy($id)
    {
        $news=News::find($id);
        if(!empty($news)){
            $news->delete();
            return response()->json([
                'success' => true,
                'messages' => 'Delete News Success !',
                'data' => ''
            ], 201);
        }
        else
            return response()->json([
                'success' => false,
                'messages' => 'News Not Found !',
                'data' => ''
            ], 404);
    }
    //filrer News
    public function filter(Request $request, $p=10)
    {
        if(!is_numeric($p))
            $p=10;

        $page=$request->input('page');
        if($page == 0 or !is_numeric($page))
            $page=1;

        $validator = Validator::make($request->all(), [
            'search' => 'required|min:3',
        ]);
        if ($validator->fails()){
            return response()->json([
                'success' => false,
                'messages' => 'filter News Fail !',
                'data' => $validator->errors(),
            ], 400);
        }
        $search = $request->input('search');

        $query=News::join('kategori_news','news.id','=','kategori_news.id_news')
            ->join('kategori','kategori.id','=','kategori_news.id_kategori')
            ->where('news.judul','like','%'.$search.'%');
        $kategori=$query->select('kategori_news.id_news','kategori.*')->skip(($page-1)*$p)->take($p)->get();
        $news=$query->select('news.*')->groupBy('news.id')->paginate($p);

        return response()->json([
            'success' => true,
            'messages' => 'Success !',
            'data' => [
                'news' => $news,
                'kategori' => $kategori
            ]
        ], 200);
    }
}
