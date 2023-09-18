<?php
namespace App\Enums;

enum PostStatusEnum:int {

    case Pending = 0;
    case OnIt = 1;
    case Complications = 2;
    case Done = 3;
}