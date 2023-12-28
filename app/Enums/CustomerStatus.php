<?php

namespace App\Enums;


/**
 * Class CustomerStatus
 *
 * @author  Zura Sekhniashvili <zurasekhniashvili@gmail.com>
 * @package App\Enums
 */
enum CustomerStatus: string
{
    case Active = 'active';
    case Disabled = 'disabled';
}