<?php

namespace App\Spiders;

use App\ItemProcessors\SaveMajorToDatabaseProcessor;
use Illuminate\Support\Facades\Log;
use Generator;
use RoachPHP\Downloader\Middleware\RequestDeduplicationMiddleware;
use RoachPHP\Extensions\LoggerExtension;
use RoachPHP\Extensions\StatsCollectorExtension;
use RoachPHP\Http\Response;
use RoachPHP\Spider\BasicSpider;
use RoachPHP\Spider\ParseResult;

class MajorsSpider extends BasicSpider
{
    public array $startUrls = [
        'https://www.virginia.edu/academics/majors'
        // 'http://records.ureg.virginia.edu/content.php?catoid=58&navoid=4883&print'
    ];

    public array $itemProcessors = [
        SaveMajorToDatabaseProcessor::class,
    ];

    public array $extensions = [
        LoggerExtension::class,
    ];

    /**
     * @return Generator<ParseResult>
     */
    public function parse(Response $response): Generator
    {
        $majors = $response->filter('ul.majors-list')->first()->children('li > a')->each(function ($node) {
            return trim($node->text());
        });

        foreach ($majors as $major) {
            yield $this->item([
                'name' => $major,
            ]);
        }
    }
}
