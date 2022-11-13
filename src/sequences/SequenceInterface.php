<?php
    declare(strict_types=1);

    namespace ansi\sequences;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    interface SequenceInterface {
//        public function __construct(?SequenceInterface $parent = null);

        public function __toString() : string;

        public function AddChild(SequenceInterface $child) : bool;

        public function GetParent() : ?SequenceInterface;

        public function GetRootParent() : SequenceInterface;

        public function TriggerEvent(string $eventName, array $args = []);
    }
?>