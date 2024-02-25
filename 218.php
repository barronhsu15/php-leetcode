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
     * Get skyline of buildings
     *
     * @param array<int, array<int, int>> $buildings
     * @return array<int, array<int, int>>
     */
    public function getSkyline($buildings)
    {
        $buildings = array_values(array_unique($buildings, SORT_REGULAR));

        $result = array_reduce($buildings, function ($accumulator, $building) use ($buildings) {
            $buildingLeft = $building[self::BUILDING_LEFT];
            $buildingRight = $building[self::BUILDING_RIGHT];
            $buildingHeight = $building[self::BUILDING_HEIGHT];

            [
                $sameBuildingOverlapLeft,
                $higherBuildingOverlapLeft,
                $sameBuildingOverlapRight,
                $higherBuildingOverlapRight,
                $lowerBuildingOverlapRight
            ] = array_reduce($buildings, function ($accumulator, $buildingAnother) use ($building) {
                if ($buildingAnother === $building) {
                    return $accumulator;
                }

                $left = $buildingAnother[self::BUILDING_LEFT];
                $right = $buildingAnother[self::BUILDING_RIGHT];
                $height = $buildingAnother[self::BUILDING_HEIGHT];

                if (
                    $left <= $building[self::BUILDING_LEFT]
                    && $right >= $building[self::BUILDING_LEFT]
                ) {
                    $accumulator[0] = $height === $building[self::BUILDING_HEIGHT]
                        ? $buildingAnother
                        : $accumulator[0];

                    $accumulator[1] = $height > $building[self::BUILDING_HEIGHT]
                        && $height > ($accumulator[1][self::BUILDING_HEIGHT] ?? 0)
                        ? $buildingAnother
                        : $accumulator[1];
                }

                if (
                    $left <= $building[self::BUILDING_RIGHT]
                    && $right >= $building[self::BUILDING_RIGHT]
                ) {
                    $accumulator[2] = $height === $building[self::BUILDING_HEIGHT]
                        ? $buildingAnother
                        : $accumulator[2];

                    $accumulator[3] = $height > $building[self::BUILDING_HEIGHT]
                        && $height > ($accumulator[3][self::BUILDING_HEIGHT] ?? 0)
                        ? $buildingAnother
                        : $accumulator[3];
                }

                if (
                    $left <= $building[self::BUILDING_RIGHT]
                    && $right > $building[self::BUILDING_RIGHT]
                ) {
                    $accumulator[4] = $height < $building[self::BUILDING_HEIGHT]
                        && $height > ($accumulator[4][self::BUILDING_HEIGHT] ?? 0)
                        ? $buildingAnother
                        : $accumulator[4];
                }

                return $accumulator;
            }, [null, null, null, null, null]);

            if (empty($sameBuildingOverlapLeft) && empty($higherBuildingOverlapLeft)) {
                $accumulator[] = [
                    self::SKYLINE_X => $buildingLeft,
                    self::SKYLINE_Y => $buildingHeight
                ];
            }

            if (!empty($sameBuildingOverlapRight) || !empty($higherBuildingOverlapRight)) {
                return $accumulator;
            }

            if (!empty($lowerBuildingOverlapRight)) {
                $accumulator[] = [
                    self::SKYLINE_X => $buildingRight,
                    self::SKYLINE_Y => $lowerBuildingOverlapRight[self::BUILDING_HEIGHT]
                ];

                return $accumulator;
            }

            $accumulator[] = [
                self::SKYLINE_X => $buildingRight,
                self::SKYLINE_Y => 0
            ];

            return $accumulator;
        }, []);

        sort($result);

        return $result;
    }
}
