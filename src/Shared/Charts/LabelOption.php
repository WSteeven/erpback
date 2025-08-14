<?php

namespace Src\Shared\Charts;

class LabelOption
{
    public function __construct(
        public bool $show,
        public string $position,
        public int|float $distance,
        public ?string $align,
        public ?string $verticalAlign,
        public int $rotate,
        public string $formatter,
        public int $fontSize,
        public array $rich
    ) {}


    public function toArray(): array
    {
        return [
            'show' => $this->show,
            'position' => $this->position,
            'distance' => $this->distance,
            'align' => $this->align,
            'verticalAlign' => $this->verticalAlign,
            'rotate' => $this->rotate,
            'formatter' => $this->formatter,
            'fontSize' => $this->fontSize,
            'rich' => $this->rich,
        ];
    }
}
