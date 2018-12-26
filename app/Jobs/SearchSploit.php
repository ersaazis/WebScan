<?php

namespace App\Jobs;
use App\ScanResult;

class SearchSploit extends Job
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $id;
    private $search;
    public function __construct($id, $search)
    {
        $this->id=$id;
        $this->search=$search;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $url="https://nvd.nist.gov/vuln/search/results?form_type=Basic&results_type=overview&query=".$this->search."&search_type=all";
        $data=file_get_contents($url);
        preg_match_all('/data-testid="vuln-detail-link-.*">(.*?)<\/a>/i', $data, $ids);
        preg_match_all('/<p data-testid=\'vuln-summary-.*\'>(.*?)<\/p>/i', $data, $summary);
        preg_match_all('/<span data-testid=\'vuln-published-on-.*\'>(.*?)<\/span>/i', $data, $publish);
        
        preg_match_all('/data-testid="vuln-cvss3-link-.*">(.*?)<\/a>/i', $data, $cvss3);
        preg_match_all('/data-testid="vuln-cvss2-link-.*">(.*?)<\/a>/i', $data, $cvss2);
        $results=array();
        for ($i=0;$i<count($ids[1]);$i++) {
            $results=[
                "code"=>$this->id,
                "name"=>"Wordpress / ".$this->search,
                "ids"=>$ids[1][$i],
                "summary"=>$summary[1][$i],
                "publish"=>$publish[1][$i],
                "severity"=>
                    "CVSS v2 : ".(!empty($cvss2[1][$i])?$cvss2[1][$i]:"").
                    "|CVSS v3 : ".(!empty($cvss3[1][$i])?$cvss3[1][$i]:""),
            ];
            // print_r($results);
            ScanResult::create($results);
        }
    }
}
