<?php

namespace App\DTOs;

use Illuminate\Http\Request;

abstract class BaseData {
    abstract public static function fromRequest(Request $request): static;

    abstract public function toUpsertArray(): array;
}
