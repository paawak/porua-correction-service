<?php

namespace com\swayam\ocr\porua\model;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Embedded;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;

require_once __DIR__ . '/CorrectedWordEntityTemplate.php';

/**
 * @Entity
 * @Table(name="rajshekhar_basu_mahabharat_bangla_ocr_word")
 */
class OcrWordEntityTemplate implements OcrWord, \JsonSerializable {

    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue(strategy="IDENTITY")
     */
    private int $id;

    /**
     * @Embedded(class = "OcrWordId", columnPrefix=false) 
     */
    private OcrWordId $ocrWordId;

    /** @Column(name = "raw_text") */
    private string $rawText;

    /** @Column(type="integer") */
    private int $x1;

    /** @Column(type="integer") */
    private int $y1;

    /** @Column(type="integer") */
    private int $x2;

    /** @Column(type="integer") */
    private int $y2;

    /** @Column(type="float") */
    private float $confidence;

    /** @Column(name = "line_number", type="integer") */
    private $lineNumber;

    /**
     * @OneToMany(targetEntity="CorrectedWordEntityTemplate", mappedBy="ocrWord")
     */
    private Collection $correctedWords;

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

    public function getCorrectedWords(): Collection {
        return $this->correctedWords;
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

    public function setLineNumber(int $lineNumber): void {
        $this->lineNumber = $lineNumber;
    }

    public function setCorrectedWords(Collection $correctedWords): void {
        $this->correctedWords = $correctedWords;
    }

    public function jsonSerialize() {
        return get_object_vars($this);
    }

}
