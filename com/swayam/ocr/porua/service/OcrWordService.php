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

    function getWords(integer $bookId, integer $pageImageId): array;

    function updateCorrectTextInOcrWord(integer $ocrWordId, string $correctedText, UserDetails $user): integer;

    function markWordAsIgnored(integer $ocrWordId, UserDetails $user): integer;
}
