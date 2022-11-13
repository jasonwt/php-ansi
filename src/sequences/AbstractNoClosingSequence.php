<?php
    declare(strict_types=1);

    namespace ansi\sequences;

    require_once(__DIR__ . "/AbstractSequence.php");
    
    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    abstract class AbstractNoClosingSequence extends AbstractSequence {
        protected function GetClosingEscape(): string {
            return "";
        }
    }
?>