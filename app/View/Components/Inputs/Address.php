<?php

namespace App\View\Components\Inputs;

use Closure;
use App\Models\Address as AddressModel;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class Address extends Component
{
    public array $layers;
    public array $addressComponents; // The hidden address component inputs that will be submitted with the form.

    /**
     * Create a new address input instance.
     * 
     * @param ?AddressModel $address Optional address model. If set, the autocomplete input and hidden address component inputs will be pre-populated accordingly.
     * @param ?string $near The location to search near, in the format "latitude,longitude". If not specified, the search will automatically be biased based on the client's IP geolocation.
     * @param int $debounceMS The number of milliseconds to wait after typing is complete to refresh the results list.
     * @param int $minCharacters The minimum number of characters that need to be typed before fetching results.
     * @param int $limit The maximum number of results to show in the results list.
     * @param bool $disabled A boolean that indicates whether the input should be disabled.
     * @param string $countryCode An optional 2-letter ISO 3166 country code. If set, results will only be fetched from the specified country.
     * @param string $layers Optional layer filters. A string, comma-separated, including one or more of place, address, postalCode, locality, county, state, country, coarse, and fine. Note that coarse includes all of postalCode, locality, county, state, and country, whereas fine includes address and place.
     * @param string $addressComponents Optional address component filters. A string, comma-separated, including one or more of formattedAddress, city, state, country, latitude, and longitude.
     * 
     * @see https://radar.com/documentation/maps/autocomplete and https://radar.com/documentation/api#autocomplete
     */
    public function __construct(
        public ?AddressModel $address = null,
        public ?string $near = null,
        public int $debounceMS = 200,
        public int $minCharacters = 3,
        public int $limit = 5,
        public bool $disabled = false,
        public string $countryCode = "us",
        string $layers = "address,coarse",
        string $addressComponents = "formattedAddress,city,state,country,latitude,longitude"
    ) {
        $this->layers = explode(',', $layers);
        $this->addressComponents = explode(',', $addressComponents);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.inputs.address');
    }
}
