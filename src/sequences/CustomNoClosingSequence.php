<?php
    declare(strict_types=1);

    namespace ansi\sequences;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    require_once(__DIR__ . "/SequenceInterface.php");
    require_once(__DIR__ . "/AbstractNoClosingSequence.php");;

    class CustomNoClosingSequence extends AbstractNoClosingSequence {        
        protected $openingString = "";            

        public function __construct(string $customOpeningString, ?SequenceInterface $parent = null) {
            parent::__construct($parent);

            $this->openingString = $customOpeningString;
        }                

        protected Function GetOpeningEscape(): string {
            return $this->openingString;                
        }
    }
?>