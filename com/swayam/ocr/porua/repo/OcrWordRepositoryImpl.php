<?php

namespace com\swayam\ocr\porua\repo;

use Doctrine\ORM\EntityManager;
use \com\swayam\ocr\porua\model\OcrWordId;
use \com\swayam\ocr\porua\model\OcrWord;
use \com\swayam\ocr\porua\model\OcrWordEntityTemplate;

/**
 *
 * @author paawak
 */
class OcrWordRepositoryImpl implements OcrWordRepository {

    private $entityManager;

    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function getWord(OcrWordId $ocrWordId): OcrWord {
        $ocrWord = $this->entityManager->getRepository(OcrWordEntityTemplate::class)->findOneBy(array(
            'ocrWordId.bookId' => $ocrWordId->getBookId(),
            'ocrWordId.pageImageId' => $ocrWordId->getPageImageId(),
            'ocrWordId.wordSequenceId' => $ocrWordId->getWordSequenceId()
        ));
        return $ocrWord;
    }

    public function getWordsInPage(integer $bookId, integer $pageImageId): array {
        $words = $this->entityManager->getRepository(OcrWordEntityTemplate::class)->findBy(
                array(
                    'ocrWordId.bookId' => $bookId,
                    'ocrWordId.pageImageId' => $pageImageId,
                    'ignored' => false
                ),
                array('ocrWordId.wordSequenceId' => 'ASC')
        );

        return $words;
    }

}
