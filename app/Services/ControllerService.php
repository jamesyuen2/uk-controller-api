<?php

namespace App\Services;

use App\Models\Airfield\Airfield;
use App\Models\Controller\ControllerPosition;

class ControllerService
{
    /**
     * Get the legacy dependency as represented in controller-positions.json.
     *
     * @return array
     */
    public function getLegacyControllerPositionsDependency() : array
    {
        $controllers = ControllerPosition::with('topDownAirfields')->get();
        $dependency = [];
        $controllers->each(function (ControllerPosition $controller) use (&$dependency) {
            $dependency[$controller->callsign] = [
                'frequency' => (float) $controller->frequency,
            ];

            $controller->topDownAirfields->each(function (Airfield $airfield) use ($controller, &$dependency) {
                $dependency[$controller->callsign]['top-down'][] = $airfield->code;
            });
        });

        return $dependency;
    }

    /**
     * Get the legacy dependency as represented in airfield-ownership.json.
     *
     * @return array
     */
    public function getLegacyAirfieldOwnershipDependency() : array
    {
        $airfields = Airfield::with('controllers')->get();

        $dependency = [];
        $airfields->each(function (Airfield $airfield) use (&$dependency) {
            $controllers = $airfield->controllers()->orderBy('order')->get();

            $controllers->each(function (ControllerPosition $controller) use ($airfield, &$dependency) {
                $dependency[$airfield->code][] = $controller->callsign;
            });
        });

        return $dependency;
    }

    public static function getControllerLevelFromCallsign(string $callsign): string
    {
        return strtoupper(strpos($callsign, '_') === false ? '' : substr($callsign, strrpos($callsign, '_') + 1));
    }

    public static function getControllerFacilityFromCallsign(string $callsign): string
    {
        return strtoupper(strpos($callsign, '_') === false ? $callsign : substr($callsign, 0, strpos($callsign, '_')));
    }
}
