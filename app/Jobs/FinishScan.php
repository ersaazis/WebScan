<?php

namespace App\Jobs;
use App\WebScan;

class FinishScan extends Job
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $id;
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
        $scan=WebScan::find($this->id);
        $scan->update([
            'scanning' => 0
        ]);
    }
}
