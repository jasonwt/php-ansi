<?php
     declare(strict_types=1);
    
     namespace ansi\sequences;

     error_reporting(E_ALL);
     ini_set('display_errors', '1');

     require_once(__DIR__ . "/SequenceInterface.php");

     abstract class AbstractSequence implements SequenceInterface {
        protected ?SequenceInterface $parent = null;
        protected ?SequenceInterface $rootParent = null;
        protected $children = array();

        public function __construct(?SequenceInterface $parent = null) {
            $this->parent = $parent;

            if (is_null($parent))
                $this->rootParent = $this;
            else
                $this->rootParent = $parent->GetRootParent();
            
        }

        public function __toString() : string {
            return $this->GetSequence();
        }

        public function AddChild(SequenceInterface $child) : bool{
            $this->children[] = $child;
            
            return true;
        }

        public function GetParent() : ?SequenceInterface {
            return $this->parent;
        }

        public function GetRootParent() : SequenceInterface {
            return $this->rootParent;            
        }

        public function TriggerEvent(string $eventName, array $args = []) {
            echo "TriggerEvent: $eventName\n";
            print_r($args);
        }

        abstract protected function GetOpeningEscape() : string;
        abstract protected function GetClosingEscape() : string;

        protected function GetSequence() : string {
            $returnValue = $this->GetOpeningEscape();

            foreach ($this->children as $child)
                $returnValue .= $child;

            $returnValue .= $this->GetClosingEscape();

            return $returnValue;
        }
     }
 
?>