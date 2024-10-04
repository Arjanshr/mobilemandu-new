<?php
namespace App\Enums;

enum BankTermStatus: string {
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
}