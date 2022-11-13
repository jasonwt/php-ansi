<?php
    declare(strict_types=1);

    namespace ansi\sequences;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    require_once(__DIR__ . "/SequenceInterface.php");
    require_once(__DIR__ . "/AbstractNoClosingSequence.php");

    class TextSequence extends AbstractNoClosingSequence {                        
        protected $text = "";

        public function __construct(string $text, ?SequenceInterface $parent = null) {
            parent::__construct($parent);

            $this->text = $text;
        }            

        protected Function GetOpeningEscape(): string {
            return $this->text;
        }            
    }
?>