<?php

namespace com\swayam\ocr\porua\repo;

use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ObjectRepository;
use \com\swayam\ocr\porua\model\CorrectedWord;
use com\swayam\ocr\porua\model\CorrectedWordEntityTemplate;
use \com\swayam\ocr\porua\model\UserDetails;

/**
 *
 * @author paawak
 */
class CorrectedWordRepositoryImpl implements CorrectedWordRepository {

    private $entityManager;

    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function getCorrectedWord(integer $ocrWordId, UserDetails $user): CorrectedWord {
        $correctedWord = $this->getRepository()->findOneBy(array(
            'ocrWordId' => $ocrWordId,
            'user.id' => $user->getId()
        ));
        return $correctedWord;
    }

    public function save(CorrectedWord $entity): CorrectedWord {
        return $this->entityManager->persist($entity);
    }

    public function updateCorrectedText(integer $ocrWordId, string $correctedText, UserDetails $user): integer {
        $sql = "UPDATE " . CorrectedWordEntityTemplate::class . " word SET word.correctedText = :correctedText WHERE word.ocrWordId = :ocrWordId AND word.user.id = :userId";
        $updateQuery = $this->entityManager->createQuery($sql);
        $updated = $updateQuery->execute(array(
            'ocrWordId' => $ocrWordId,
            'correctedText' => $correctedText,
            'userId' => $user->getId()
        ));
        return $updated;
    }

    public function markAsIgnored(integer $ocrWordId, UserDetails $user): integer {
        $sql = "UPDATE " . CorrectedWordEntityTemplate::class . " word SET word.ignored = TRUE WHERE word.ocrWordId = :ocrWordId AND word.user.id = :userId";
        $updateQuery = $this->entityManager->createQuery($sql);
        $updated = $updateQuery->execute(array(
            'ocrWordId' => $ocrWordId,
            'userId' => $user->getId()
        ));
        return $updated;
    }

    private function getRepository(): ObjectRepository {
        return $this->entityManager->getRepository(CorrectedWordEntityTemplate::class);
    }

}
