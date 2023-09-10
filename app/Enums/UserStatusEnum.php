<?php
namespace App\Enums;

enum UserStatusEnum:int {

    case SuperAdmin = 0;
    case Admin = 1;
    case Member = 2;
    case Guest = 3;
}