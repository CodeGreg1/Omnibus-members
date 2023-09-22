<?php

namespace Modules\Auth\Listeners;

use Illuminate\Auth\Events\Registered;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Base\Repositories\CountryRepository;
use Modules\Base\Support\Location\VisitorLocation;

class RegistrationUpdateCountry
{
    /**
     * @var VisitorLocation
     */
    protected $visitorLocation;

    /**
     * @var CountryRepository
     */
    protected $countries;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(VisitorLocation $visitorLocation, CountryRepository $countries)
    {
        $this->visitorLocation = $visitorLocation;

        $this->countries = $countries;
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        $address = $this->visitorLocation->get();

        if ( !$address ) {
            return;
        }

        $country = $this->countries->findBy('iso_3166_2', $address->countryCode);

        if ( !is_null($country) ) {
            $event->user->update([
                'country_id' => $country->id
            ]);
        }
    }
}
