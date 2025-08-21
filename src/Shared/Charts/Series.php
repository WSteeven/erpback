<?php

namespace Src\Shared\Charts;

class Series
{
    public string $name;
    public string $type;
    public array $data;
    public ?int $barGap = null;
    public ?LabelOption $label = null;
    public array $emphasis = [];

    public function __construct(
        string      $name,
        string      $type,
        array       $data,
        ?int        $barGap = null,
        LabelOption $label = null,
        array       $emphasis = []
    )
    {
        $this->name = $name;
        $this->type = $type;
        $this->data = $data;
        $this->barGap = $barGap;
        $this->label = $label;
        $this->emphasis = $emphasis;
    }

    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'type' => $this->type,
            'data' => $this->data,
            'barGap' => $this->barGap,
            'label' => $this->label->toArray(),
            'emphasis' => $this->emphasis
        ], fn($v) => $v !== null);
    }
}
