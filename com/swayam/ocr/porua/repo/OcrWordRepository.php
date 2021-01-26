<?php

namespace com\swayam\ocr\porua\repo;

use \com\swayam\ocr\porua\model\OcrWordId;
use \com\swayam\ocr\porua\model\OcrWord;

/**
 *
 * @author paawak
 */
interface OcrWordRepository {

    function getWord(OcrWordId $ocrWordId): OcrWord;

    function getWordsInPage(int $bookId, int $pageImageId): array;
}
