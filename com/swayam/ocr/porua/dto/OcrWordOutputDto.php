<?php

namespace com\swayam\ocr\porua\dto;

use \com\swayam\ocr\porua\model\OcrWordId;
use \com\swayam\ocr\porua\model\OcrWord;
use \com\swayam\ocr\porua\model\CorrectedWord;
use \com\swayam\ocr\porua\model\UserDetails;

class OcrWordOutputDto implements OcrWord, CorrectedWord, \JsonSerializable {

    private int $id;
    private OcrWordId $ocrWordId;
    private string $rawText;
    private int $x1;
    private int $y1;
    private int $x2;
    private int $y2;
    private float $confidence;
    private $lineNumber;
    private string $correctedText;
    private bool $ignored;

    public function getId(): int {
        return $this->id;
    }

    public function getOcrWordId(): OcrWordId {
        return $this->ocrWordId;
    }

    public function getRawText(): string {
        return $this->rawText;
    }

    public function getX1(): int {
        return $this->x1;
    }

    public function getY1(): int {
        return $this->y1;
    }

    public function getX2(): int {
        return $this->x2;
    }

    public function getY2(): int {
        return $this->y2;
    }

    public function getConfidence(): float {
        return $this->confidence;
    }

    public function getLineNumber(): ?int {
        return $this->lineNumber;
    }

    public function getCorrectedText(): string {
        return $this->correctedText;
    }

    public function getCorrectedWords(): array {
        return null;
    }

    public function getUser(): UserDetails {
        return null;
    }

    public function isIgnored(): bool {
        return $this->ignored;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setOcrWordId(OcrWordId $ocrWordId): void {
        $this->ocrWordId = $ocrWordId;
    }

    public function setRawText(string $rawText): void {
        $this->rawText = $rawText;
    }

    public function setX1(int $x1): void {
        $this->x1 = $x1;
    }

    public function setY1(int $y1): void {
        $this->y1 = $y1;
    }

    public function setX2(int $x2): void {
        $this->x2 = $x2;
    }

    public function setY2(int $y2): void {
        $this->y2 = $y2;
    }

    public function setConfidence(float $confidence): void {
        $this->confidence = $confidence;
    }

    public function setLineNumber(?int $lineNumber): void {
        $this->lineNumber = $lineNumber;
    }

    public function setCorrectedText(string $correctedText): void {
        $this->correctedText = $correctedText;
    }

    public function setIgnored(bool $ignored): void {
        $this->ignored = $ignored;
    }
    
    public function jsonSerialize() {
        return get_object_vars($this);
    }

}
