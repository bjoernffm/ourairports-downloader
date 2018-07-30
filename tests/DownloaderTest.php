<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use bjoernffm\ourAirportsDownloader\Downloader;

final class DownloaderTest extends TestCase
{
    public function testDownload()
    {
        $downloader = new Downloader();
        $result = $downloader->download();
        
        $this->assertTrue($result['airports']['success']);
        $this->assertTrue($result['airport-frequencies']['success']);
        $this->assertTrue($result['runways']['success']);
        
        $this->assertGreaterThan(0, filesize($result['airports']['file']));
        $this->assertGreaterThan(0, filesize($result['airport-frequencies']['file']));
        $this->assertGreaterThan(0, filesize($result['airports']['file']));
        
        $this->assertStringEndsWith('-airports.csv', $result['airports']['file']);
        $this->assertStringEndsWith('-airport-frequencies.csv', $result['airport-frequencies']['file']);
        $this->assertStringEndsWith('-runways.csv', $result['runways']['file']);
    } 
    
    public function testRun()
    {
        $downloader = new Downloader();
        $result = $downloader->run();
        
        
        $this->assertInstanceOf(Iterator::class, $result['airports']);
        $this->assertInstanceOf(Iterator::class, $result['airport-frequencies']);
        $this->assertInstanceOf(Iterator::class, $result['runways']);
    }
    
    public function testDownloadFail()
    {
        $downloader = new Downloader();
        $downloader::$urls = [
            'airports' => 'http://ourairports.com/data/404.csv',
            'airport-frequencies' => 'http://ourairports.com/data/404.csv',
            'runways' => 'http://ourairports.com/data/404.csv',
        ];
        
        $result = $downloader->download();
        
        $this->assertFalse($result['airports']['success']);
        $this->assertFalse($result['airport-frequencies']['success']);
        $this->assertFalse($result['runways']['success']);
        
        $this->assertNull($result['airports']['file']);
        $this->assertNull($result['airport-frequencies']['file']);
        $this->assertNull($result['runways']['file']);
    }
}