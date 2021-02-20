<?php

namespace com\swayam\ocr\porua\model;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;

require_once __DIR__ . '/CorrectedWord.php';

/**
 * @Entity
 * @Table(name="rajshekhar_basu_mahabharat_bangla_corrected_word")
 */
class CorrectedWordEntityTemplate implements CorrectedWord, \JsonSerializable {

    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue(strategy="IDENTITY")
     */
    private int $id;

    /**
     * @OneToOne(targetEntity="UserDetails") 
     * @JoinColumn(name="user_id", referencedColumnName="id") 
     */
    private UserDetails $user;

    /**
     * @OneToOne(targetEntity="OcrWordEntityTemplate") 
     * @JoinColumn(name="ocr_word_id", referencedColumnName="id") 
     */
    private OcrWord $ocrWord;

    /** @Column(name = "corrected_text") */
    private $correctedText;

    /** @Column(type="boolean") */
    private bool $ignored;

    public function getId(): int {
        return $this->id;
    }

    public function getUser(): UserDetails {
        return $this->user;
    }

    public function getOcrWord(): OcrWord {
        return $this->ocrWord;
    }

    public function getCorrectedText(): ?string {
        return $this->correctedText;
    }

    public function isIgnored(): bool {
        return $this->ignored;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setUser(UserDetails $user): void {
        $this->user = $user;
    }

    public function setOcrWord(OcrWord $ocrWord): void {
        $this->ocrWord = $ocrWord;
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
