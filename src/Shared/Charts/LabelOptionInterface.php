<?php

namespace Src\Shared\Charts;

interface LabelOptionInterface {
    /**
     * @return bool
     */
    public function getShow(): bool;

    /**
     * @return string
     */
    public function getPosition(): string;

    /**
     * @return int|float
     */
    public function getDistance();

    /**
     * @return string|null
     */
    public function getAlign(): ?string;

    /**
     * @return string|null
     */
    public function getVerticalAlign(): ?string;

    /**
     * @return int
     */
    public function getRotate(): int;

    /**
     * @return string
     */
    public function getFormatter(): string;

    /**
     * @return int
     */
    public function getFontSize(): int;

    /**
     * @return array
     */
    public function getRich(): array;
}
