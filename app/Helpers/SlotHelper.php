<?php

namespace App\Helpers;

use Carbon\Carbon;

class SlotHelper
{
    public static function generateBlockedSlots(
        $jamMulai,
        $durasi
    ) {

        $slots = [];

        $start =
            Carbon::createFromFormat(
                'H:i',
                $jamMulai
            );

        $totalSlot =
            ($durasi * 2);

        for (
            $i = 0;
            $i <= $totalSlot;
            $i++
        ) {

            $slots[] =
                $start
                ->copy()
                ->addMinutes($i * 30)
                ->format('H:i');
        }

        return $slots;
    }
}