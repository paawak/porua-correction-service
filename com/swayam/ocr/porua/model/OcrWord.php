<?php

namespace com\swayam\ocr\porua\model;

use Doctrine\Common\Collections\Collection;

/**
 *
 * @author paawak
 */
interface OcrWord {

    function getId(): int;

    function getOcrWordId(): OcrWordId;

    function getRawText(): string;

    function getX1(): int;

    function getY1(): int;

    function getX2(): int;

    function getY2(): int;

    function getConfidence(): float;

    function getLineNumber(): ?int;

    function getCorrectedWords(): Collection;
}
