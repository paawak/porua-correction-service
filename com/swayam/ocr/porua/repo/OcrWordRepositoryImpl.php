<?php

namespace com\swayam\ocr\porua\repo;

use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ObjectRepository;
use \com\swayam\ocr\porua\model\OcrWordId;
use \com\swayam\ocr\porua\model\OcrWord;
use \com\swayam\ocr\porua\model\OcrWordEntityTemplate;

require_once __DIR__ . '/OcrWordRepository.php';
require_once __DIR__ . '/../model/OcrWordEntityTemplate.php';

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
        $ocrWord = $this->getRepository()->findOneBy(array(
            'ocrWordId.bookId' => $ocrWordId->getBookId(),
            'ocrWordId.pageImageId' => $ocrWordId->getPageImageId(),
            'ocrWordId.wordSequenceId' => $ocrWordId->getWordSequenceId()
        ));        
        return $ocrWord;
    }

    public function getWordsInPage(int $bookId, int $pageImageId): array {
        $words = $this->getRepository()->findBy(
                array(
                    'ocrWordId.bookId' => $bookId,
                    'ocrWordId.pageImageId' => $pageImageId
                ),
                array('ocrWordId.wordSequenceId' => 'ASC')
        );

        return $words;
    }
    
    private function getRepository(): ObjectRepository {
        return $this->entityManager->getRepository(OcrWordEntityTemplate::class);
    }

}
