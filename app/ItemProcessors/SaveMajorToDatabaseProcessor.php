<?php

namespace App\ItemProcessors;

use App\Models\Major;
use RoachPHP\Support\Configurable;
use RoachPHP\ItemPipeline\ItemInterface;
use RoachPHP\ItemPipeline\Processors\ItemProcessorInterface;

class SaveMajorToDatabaseProcessor implements ItemProcessorInterface
{
    use Configurable;

    public function processItem(ItemInterface $item): ItemInterface
    {
        $majorId = Major::create(['name' => $item->get('name')])->id;

        return $item->set('major_id', $majorId);
    }
}
