<?php

namespace com\swayam\ocr\porua\model;

/**
 *
 * @author paawak
 */
interface OcrWord {
    
    function getId():integer;

    function getOcrWordId():OcrWordId;

    function getRawText():string;

    function getX1():integer;

    function getY1():integer;

    function getX2():integer;

    function getY2():integer;

    function getConfidence():float;

    function getLineNumber():integer;

    function getCorrectedWords():Array;
    
}
