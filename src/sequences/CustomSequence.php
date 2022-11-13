<?php
    declare(strict_types=1);

    namespace ansi\sequences;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    require_once(__DIR__ . "/SequenceInterface.php");
    require_once(__DIR__ . "/AbstractSequence.php");

    class CustomSequence extends AbstractSequence {        
        protected $openingString = "";
        protected $closingString = "";

        public function __construct(string $customOpeningString, string $customClosingString, ?SequenceInterface $parent = null) {
            parent::__construct($parent);

            $this->openingString = $customOpeningString;
            $this->closingString = $customClosingString;
        }                
        protected Function GetOpeningEscape(): string {
            return $this->openingString;                
        }                        

        protected function GetClosingEscape(): string {
            return $this->closingString;                
        }
    }

?>