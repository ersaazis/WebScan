<?php

namespace App\Jobs;

class ScanWordpress extends Job
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $data;
    private $id;
    public function __construct($data, $id)
    {
        $this->data=$data;
        $this->id=$id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        preg_match_all('/wp-content\/plugins\/(.*?)\//si', $this->data, $plugin);
        preg_match_all('/wp-content\/themes\/(.*?)\//si', $this->data, $themes);
        
        $plugin=(array_unique($plugin[1]));
        $themes=(array_unique($themes[1]));
        foreach ($plugin as $p) {
            dispatch(new SearchSploit($this->id, $p));
        }
        foreach ($themes as $t) {
            dispatch(new SearchSploit($this->id, $p));
        }
        dispatch(new FinishScan($this->id));
    }
}
