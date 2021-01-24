<?php

namespace com\swayam\ocr\porua\model;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;

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
    private $id;

    /**
     * @OneToOne(targetEntity="UserDetails") 
     * @JoinColumn(name="user_id", referencedColumnName="id") 
     */
    private UserDetails $user;

    /** @Column(name = "ocr_word_id", type="integer") */
    private $ocrWordId;

    /** @Column(name = "corrected_text") */
    private $correctedText;

    /** @Column(type="boolean") */
    private $ignored;

    public function getId() {
        return $this->id;
    }

    public function getUser(): UserDetails {
        return $this->user;
    }

    public function getOcrWordId() {
        return $this->ocrWordId;
    }

    public function getCorrectedText() {
        return $this->correctedText;
    }

    public function isIgnored() {
        return $this->ignored;
    }

    public function setId($id): void {
        $this->id = $id;
    }

    public function setUser(UserDetails $user): void {
        $this->user = $user;
    }

    public function setOcrWordId($ocrWordId): void {
        $this->ocrWordId = $ocrWordId;
    }

    public function setCorrectedText($correctedText): void {
        $this->correctedText = $correctedText;
    }

    public function setIgnored($ignored): void {
        $this->ignored = $ignored;
    }

    public function jsonSerialize() {
        return get_object_vars($this);
    }

}
