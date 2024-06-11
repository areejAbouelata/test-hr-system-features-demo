<?php

namespace Modules\Hr\App\Enums;

use Spatie\Enum\Laravel\Enum;

enum DayEnum {
    public const MONDAY = 'monday';
    public const TUESDAY = 'tuesday';
    public const WEDNESDAY = 'wednesday';
    public const THURSDAY = 'thursday';
    public const FRIDAY = 'friday';
    public const SATURDAY = 'saturday';
    public const SUNDAY = 'sunday';
  

    
    public static function map() : array
    {
        return [
            static::MONDAY => 'monday',
            static::TUESDAY => 'tuesday',
            static::WEDNESDAY => 'wednesday',
            static::THURSDAY => 'thursday',
            static::FRIDAY => 'friday',
            static::SATURDAY => 'saturday',
            
        ];
    }
    public static function toSelectArray(): array
    {
        return [
            ['id'=> static::MONDAY,'name' => 'Monday'],
            ['id'=> static::TUESDAY,'name' => 'Tuesday'],
            ['id'=> static::WEDNESDAY,'name' => 'WEDNESDAY'],
            ['id'=> static::THURSDAY,'name' => 'THURSDAY'],
            ['id'=> static::FRIDAY,'name' => 'FRIDAY'],
            ['id'=> static::SATURDAY,'name' => 'SATURDAY'],
            ['id'=> static::SUNDAY,'name' => 'SUNDAY'],
            /* static::TUESDAY => 'Tuesday',
            static::WEDNESDAY => 'Wednesday',
            static::THURSDAY => 'Thursday',
            static::FRIDAY => 'Friday',
            static::SATURDAY => 'Saturday',
            static::SUNDAY => 'Sunday', */
        ];
    }

    public static function getValue(string $daysString): array
    {
        $days = explode(',', $daysString);
        $validDays = [];

        foreach ($days as $day) {
         
                $validDays[] = $day;
           
        }

        return $validDays;
    }
}
