<?php

namespace com\swayam\ocr\porua\dto;

use com\swayam\ocr\porua\model\OcrWordId;

class OcrCorrectionDto {

    private OcrWordId $ocrWordId;
    private string $correctedText;

    public function getOcrWordId(): OcrWordId {
        return $this->ocrWordId;
    }

    public function getCorrectedText(): string {
        return $this->correctedText;
    }

    public function setOcrWordId(OcrWordId $ocrWordId): void {
        $this->ocrWordId = $ocrWordId;
    }

    public function setCorrectedText(string $correctedText): void {
        $this->correctedText = $correctedText;
    }

    public static function fromJsonArray($ocrCorrectionDtoAsArray) {
        $ocrCorrectionDto = new OcrCorrectionDto();
        $ocrCorrectionDto->ocrWordId = OcrWordId::fromJsonArray($ocrCorrectionDtoAsArray['ocrWordId']);
        $ocrCorrectionDto->correctedText = $ocrCorrectionDtoAsArray['correctedText'];
        return $ocrCorrectionDto;
    }

}
