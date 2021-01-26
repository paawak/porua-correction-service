<?php

namespace com\swayam\ocr\porua\service;

use \com\swayam\ocr\porua\model\UserDetails;
use \com\swayam\ocr\porua\model\OcrWordId;
use \com\swayam\ocr\porua\model\OcrWord;

/**
 *
 * @author paawak
 */
interface OcrWordService {

    function getWord(OcrWordId $ocrWordId): OcrWord;

    function getWords(int $bookId, int $pageImageId): array;

    function updateCorrectTextInOcrWord(int $ocrWordId, string $correctedText, UserDetails $user): int;

    function markWordAsIgnored(int $ocrWordId, UserDetails $user): int;
}
