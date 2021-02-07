<?php

namespace com\swayam\ocr\porua\model;

/**
 *
 * @author paawak
 */
interface CorrectedWord {

    function getCorrectedText(): string;

    function isIgnored(): bool;

    function getUser(): UserDetails;
}
