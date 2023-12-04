<?php

namespace App\Console\Commands;

use App\Models\Ride;
use App\Models\Address;
use App\Services\GoogleApiService;
use Illuminate\Console\Command;

class DeleteOldAddresses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-old-addresses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete inactive old addresses and refresh active old addresses';

    /**
     * Execute the console command.
     */
    public function handle(GoogleApiService $googleApiService)
    {
        // get all addresses greater than 30 days old
        $oldAddresses = Address::where('created_at', '<', now()->subDays(30))->get();

        $this->info('Found ' . $oldAddresses->count() . ' old addresses');

        // if none, return
        if ($oldAddresses->isEmpty()) {
            $this->info('Done');
            return;
        }

        $headers = array_keys($oldAddresses->first()->getAttributes());
        $this->table($headers, $oldAddresses->toArray());

        // get addresses in $oldAddresses where id in ride origin_id or destination_id or waypoint address_id
        $activeOldAddresses = $oldAddresses->filter(function ($address) {
            return Ride::where('origin_id', $address->id)
                ->orWhere('destination_id', $address->id)
                ->orWhereHas('waypoints', function ($query) use ($address) {
                    $query->where('address_id', $address->id);
                })
                ->exists();
        });

        $this->info('Found ' . $activeOldAddresses->count() . ' active old addresses');

        // delete inactive old addresses
        $oldAddresses->diff($activeOldAddresses)->each(function ($address) {
            $address->delete();
        });

        if ($activeOldAddresses->isEmpty()) {
            $this->info('Done');
            return;
        }

        $this->table($headers, $activeOldAddresses->toArray());

        // refresh active old addresses
        $activeOldAddresses->each(function ($address) use ($googleApiService) {
            $googleApiService->refreshAddress($address);
        });

        $this->info('Done');
        return;
    }
}
