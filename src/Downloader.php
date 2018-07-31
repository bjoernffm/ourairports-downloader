<?php

namespace bjoernffm\ourAirportsDownloader;

use Ramsey\Uuid\Uuid;
use League\Csv\Reader;
use \Exception;
                                              
class Downloader
{
    static $urls = [
        'airports' => 'http://ourairports.com/data/airports.csv',
        'airport-frequencies' => 'http://ourairports.com/data/airport-frequencies.csv',
        'runways' => 'http://ourairports.com/data/runways.csv',
    ];
    
    public function __construct()
    {
    }
    
    public function download()
    {
        $id = Uuid::uuid4();
        $id = $id->toString();
        
        $result = [];
        
        $client = new \GuzzleHttp\Client();
        
        try {
            $client->get(self::$urls['airports'], ['sink' => '/tmp/'.$id.'-airports.csv']);
            $result['airports'] = [
                'file' => '/tmp/'.$id.'-airports.csv',
                'success' => true
            ]; 
        } catch(Exception $e) {
            $result['airports'] = [
                'file' => null,
                'success' => false
            ];
        }
        
        try {
            $client->get(self::$urls['airport-frequencies'], ['sink' => '/tmp/'.$id.'-airport-frequencies.csv']);
            $result['airport-frequencies'] = [
                'file' => '/tmp/'.$id.'-airport-frequencies.csv',
                'success' => true
            ]; 
        } catch(Exception $e) {
            $result['airport-frequencies'] = [
                'file' => null,
                'success' => false
            ];
        }
        
        try {
            $client->get(self::$urls['runways'], ['sink' => '/tmp/'.$id.'-runways.csv']);
            $result['runways'] = [
                'file' => '/tmp/'.$id.'-runways.csv',
                'success' => true
            ]; 
        } catch(Exception $e) {
            $result['runways'] = [
                'file' => null,
                'success' => false
            ];
        }
        
        return $result;
    }
    
    public function run()
    {
        $return = $this->download();
        
        $result = [];
        
        $csv = Reader::createFromPath($return['airports']['file'], 'r');
        $csv->setHeaderOffset(0);
        $result['airports'] = $csv;
        
        $csv = Reader::createFromPath($return['airport-frequencies']['file'], 'r');
        $csv->setHeaderOffset(0);
        $result['airport-frequencies'] = $csv;
        
        $csv = Reader::createFromPath($return['runways']['file'], 'r');
        $csv->setHeaderOffset(0);
        $result['runways'] = $csv;
        
        return $result;
    }
}