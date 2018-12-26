<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\WebScan;
use App\ScanResult;
use Validator;
use Queue;

use App\Jobs\DetectWeb;

class WebScanController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    private function keyGenerate(){
        $alphabet=array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
        $vocal=array('a','i','u','e','o');
        $key=$vocal[rand(0,count($vocal)-1)];
        $key.=$alphabet[rand(0,count($alphabet)-1)];
        $key.=$vocal[rand(0,count($vocal)-1)];
        $key.=$alphabet[rand(0,count($alphabet)-1)];
        $key.=$vocal[rand(0,count($vocal)-1)];
        $key.=$alphabet[rand(0,count($alphabet)-1)];
        $key.=$vocal[rand(0,count($vocal)-1)];
        $key.=$alphabet[rand(0,count($alphabet)-1)];
        $key.=$vocal[rand(0,count($vocal)-1)];
        $validator = Validator::make(['key'=>$key], [
            'url' => 'unique:web_scan',
        ]);
        if ($validator->fails()){
            return $this->keyGenerate();
        }
        return $key;
    }
    public function scan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'url' => 'required|max:255',
        ]);
        $url=$request->input('url');
        $url = strpos($url, 'http') !== 0 ? "http://$url" : $url;
        $file_headers = @get_headers($url);

        if ($validator->fails()){
            return response()->json([
                'success' => false,
                'messages' => 'Scan Website Fail !',
                'data' => $validator->errors(),
            ], 400);
        }
        if(!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found'){
            return response()->json([
                'success' => false,
                'messages' => 'Scan Website Fail !',
                'data' => ['url' => ['Can\'t scan url'] ],
            ], 400);
        }
        else{
            $key=$this->keyGenerate();
            $addScan=WebScan::create([
                'id' => $key,
                'url' => $url,
                'expire' => Date('y/m/d', strtotime("+3 days")),
            ]);
            if($addScan){
                Queue::push(new DetectWeb($key));

                return response()->json([
                    'success' => true,
                    'messages' => 'Scan Website Will Process !',
                    'data' => [
                        'key' => $key
                    ]
                ], 201);
            }
            else
                return response()->json([
                    'success' => false,
                    'messages' => 'Scan Website Fail !',
                    'data' => ['url' => ['Can\'t scan url'] ],
                ], 400);
        }
    }

    public function result($id)
    {
        $ScanResult=ScanResult::where("code",$id)->get();
        if(!empty($ScanResult))
            return response()->json([
                'success' => true,
                'messages' => 'Success !',
                'data' => $ScanResult,
            ], 200);
        else
            return response()->json([
                'success' => false,
                'messages' => 'Scan Not Found !',
                'data' => '',
            ], 404);
    }
}
