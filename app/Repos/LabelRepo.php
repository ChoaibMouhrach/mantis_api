<?php

namespace App\Repos;

use App\Exceptions\BadRequestException;
use App\Exceptions\NotFoundException;
use App\Models\Label;

class LabelRepo
{
    public function getPaginatedLabels()
    {
        $user = auth()->user();

        $labels = Label::where([
            "user_id" => $user->id
        ])->paginate(8);

        return $labels;
    }

    public function findOrThrow(int $id)
    {
        $label = Label::find($id);

        if (!$label) {
            throw new NotFoundException("Label not found");
        }

        return $label;
    }

    public function create($value)
    {
        $user = auth()->user();

        $label = Label::where([
            "user_id" => $user->id,
            "value" => $value
        ])->first();

        if ($label) {
            throw new BadRequestException("Label already exists");
        }

        return Label::create([
            "value" => $value,
            "user_id" => $user->id
        ]);
    }

    public function update(int $id, string $value)
    {
        $label = $this->findOrThrow($id);
        $user = auth()->user();

        $valueLabel = Label::where([
            "value" => $value,
            "user_id" => $user->id
        ])->first();

        if ($valueLabel && $valueLabel->id !== $id) {
            throw new BadRequestException("Label already exists");
        }

        $label->update([
            "value" => $value
        ]);

        return $label;
    }

    public function destroy(int $id)
    {
        $label = $this->findOrThrow($id);
        $label->delete();
    }
}
