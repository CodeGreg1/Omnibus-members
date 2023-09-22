<?php

namespace Modules\Base\Support\Location;

use Illuminate\Support\Facades\Http;
use Stevebauman\Location\Facades\Location;
use Modules\Profile\Models\SessionLocation;
use Modules\Base\Repositories\CountryRepository;

class VisitorLocation
{	
	/**
	 * @var CountryRepository
	 */
	protected $countries;

	public function __construct() 
	{
		$this->countries = new CountryRepository;
	}

	/**
	 * Get the visitors location
	 * 
	 * @return Location
	 */
	public function get() 
	{
		return Location::get();
	}

	/**
	 * Store visitors location
	 * 
	 * @return void
	 */
	public function store() 
	{
		$address = $this->get();

		if ( !$address ) {
            return;
        }

		$country = $this->countries->findBy('iso_3166_2', $address->countryCode);

		if ( $country ) {
			SessionLocation::create([
	            'session_id' => session()->getId(),
	            'country_id' => $country->id,
	            'region' => $address->regionName,
	            'city' => $address->cityName,
	            'zip' => $address->zipCode
	        ]);
		}
	}
}