<?php
namespace App\Enums;

enum UserRoomEnum:int {

    case Owner = 0;
    case Member = 1;
    case Guest = 2;
}