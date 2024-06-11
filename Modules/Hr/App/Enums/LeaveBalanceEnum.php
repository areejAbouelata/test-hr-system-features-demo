<?php

namespace Modules\Hr\App\Enums;

enum LeaveBalanceEnum {
    case VALUE_21;
    case VALUE_30;
    case VALUE_3;
    case VALUE_5;
    case VALUE_10;
    case VALUE_15;
    case VALUE_22;
    case VALUE_24;
    case VALUE_25;
    case VALUE_26;
    case VALUE_28;
    case VALUE_40;
    case VALUE_55;
    case VALUE_56;
    case VALUE_100;
    case VALUE_101;
    case VALUE_150;

    public static function getAllValues(): array {
        return [
            21,
            30,
            3,
            5,
            10,
            15,
            22,
            24,
            25,
            26,
            28,
            40,
            55,
            56,
            100,
            101,
            150,
        ];
    }
}
