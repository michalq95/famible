<?php
namespace App\Enums;

enum InvitationStatusEnum:int {

    case Pending = 0;
    case Accepted = 1;
    case Rejected = 2;
    case Blocked = 3;
}