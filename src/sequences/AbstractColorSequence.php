<?php
    declare(strict_types=1);

    namespace ansi\sequences;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    require_once(__DIR__ . "/SequenceInterface.php");
    require_once(__DIR__ . "/AbstractSequence.php");

    require_once(__DIR__ . "/../RGB.php");

    use ansi\RGB;

    abstract class AbstractColorSequence extends AbstractSequence {
        protected $rgb;

        public function __construct(int $red, int $green, int $blue, ?SequenceInterface $parent = null) {
            $this->rgb = new RGB($red, $green, $blue);            

            parent::__construct($parent);
        }
    }
?>