<?php

class Solution
{
    /**
     * @var int
     */
    const BUILDING_LEFT = 0;

    /**
     * @var int
     */
    const BUILDING_RIGHT = 1;

    /**
     * @var int
     */
    const BUILDING_HEIGHT = 2;

    /**
     * @var int
     */
    const SKYLINE_X = 0;

    /**
     * @var int
     */
    const SKYLINE_Y = 1;

    /**
     * Get sky line of buildings
     *
     * @param array<int, array<int, int>> $buildings
     * @return array<int, array<int, int>>
     */
    public function getSkyline($buildings)
    {
        [$buildingsRangeBegin, $buildingsRangeEnd] = array_reduce($buildings, function ($accumulator, $building) {
            return [
                min($accumulator[0] ?? PHP_INT_MAX, $building[self::BUILDING_LEFT]),
                max($accumulator[1] ?? 0, $building[self::BUILDING_RIGHT])
            ];
        }, []);

        $result = [];

        for ($x = $buildingsRangeBegin; $x <= $buildingsRangeEnd; $x++) {
            $highestPoint = array_reduce($buildings, function ($accumulator, $building) use ($x) {
                return $x >= $building[self::BUILDING_LEFT]
                    && $x < $building[self::BUILDING_RIGHT]
                    && $building[self::BUILDING_HEIGHT] > $accumulator
                    ? $building[self::BUILDING_HEIGHT]
                    : $accumulator;
            }, 0);

            $previousPoint = $result[count($result) - 1][self::SKYLINE_Y] ?? 0;

            if ($previousPoint !== $highestPoint) {
                $result[] = [
                    self::SKYLINE_X => $x,
                    self::SKYLINE_Y => $highestPoint
                ];
            }
        }

        return $result;
    }
}
