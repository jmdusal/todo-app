<?php

namespace App\DTOs;

use Illuminate\Http\Request;

class TodoData extends BaseData {

    public function __construct(
        public ?int $id,
        public string $title,
        public ?string $description,
        public ?int $user_id,
    ) {}

    public static function fromRequest(Request $request): static {
        return new static(
            id: $request->input('id'),
            title: $request->input('title'),
            description: $request->input('description'),
            user_id: $request->input('user_id'),
        );
    }

    public function toUpsertArray(): array {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'user_id' => $this->user_id,
        ];
    }
}
