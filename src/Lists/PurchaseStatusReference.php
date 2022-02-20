<?php

namespace App\Lists;

use App\Traits\ListTrait;

class PurchaseStatusReference
{
    use ListTrait;

    public const WAITING_TO_BE_SENT = 'waiting_to_be_sent';
    public const SENT = 'sent';
}