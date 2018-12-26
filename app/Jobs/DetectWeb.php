<?php

namespace App\Jobs;

use App\WebScan;
use App\ScanResult;

class DetectWeb extends Job
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $id;
    public function __construct($id)
    {
        $this->id=$id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $website=WebScan::find($this->id);
        $url=$website->url;
        $data=file_get_contents($url);

        $cekWP=$this->cekWordpress($data);
    }

    private function cekWordpress($data)
    {
        preg_match('/(wp-content)/', $data, $checker);
        if(!empty($checker[0])?true:false){
            dispatch(new ScanWordpress($data, $this->id));
            return true;
        }
        else
            return false;
    }
}
