<?php

namespace App\Allocator\Stand;

use App\Models\Stand\Stand;
use App\Models\Vatsim\NetworkAircraft;
use App\Services\AirlineService;
use Illuminate\Database\Eloquent\Collection;

class AirlineDestinationArrivalStandAllocator extends AbstractArrivalStandAllocator
{
    private AirlineService $airlineService;

    public function __construct(AirlineService $airlineService)
    {
        $this->airlineService = $airlineService;
    }

    protected function getPossibleStands(NetworkAircraft $aircraft): Collection
    {
        $airline = $this->airlineService->getAirlineForAircraft($aircraft);
        if ($airline === null) {
            return new Collection();
        }

        return $this->getArrivalAirfieldStandQuery($aircraft)
            ->with('airlines')
            ->airlineDestination($airline, $this->getDestinationStrings($aircraft))
            ->orderByRaw('airline_stand.destination IS NOT NULL')
            ->orderByRaw('LENGTH(airline_stand.destination) DESC')
            ->inRandomOrder()
            ->get();
    }

    public function getDestinationStrings(NetworkAircraft $aircraft): array
    {
        return [
            substr($aircraft->planned_depairport, 0, 1),
            substr($aircraft->planned_depairport, 0, 2),
            substr($aircraft->planned_depairport, 0, 3),
            $aircraft->planned_depairport
        ];
    }
}
