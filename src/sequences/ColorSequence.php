<?php
    declare(strict_types=1);

    namespace ansi\sequences;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    require_once(__DIR__ . "/SequenceInterface.php");
    require_once(__DIR__ . "/AbstractSequence.php");

    require_once(__DIR__ . "/../RGB.php");

    use ansi\RGB;

    class ColorSequence extends AbstractSequence {
        protected ?RGB $foregroundColor = null;
        protected ?RGB $backgroundColor = null;

        public function __construct(?RGB $foregroundColor, ?RGB $backgroundColor, ?SequenceInterface $parent = null) {
            $this->foregroundColor = $foregroundColor;
            $this->backgroundColor = $backgroundColor;            

            parent::__construct($parent);
        }

        protected Function GetOpeningEscape(): string {
            $colorBits = $this->rootParent->TriggerEvent("GET_COLOR_BITS");

            $returnValue = "";

            if (!is_null($this->foregroundColor)) {
                $this->rootParent->TriggerEvent("PUSH_FOREGROUND_COLOR", ["rgb" => $this->foregroundColor]);

                $returnValue .= self::GetForegroundColorEscapeSequence($colorBits, $this->foregroundColor);
            }

            if (!is_null($this->backgroundColor)) {
                $this->rootParent->TriggerEvent("PUSH_BACKGROUND_COLOR", ["rgb" => $this->backgroundColor]);

                $returnValue .= self::GetBackgroundColorEscapeSequence($colorBits, $this->backgroundColor);
            }

            return $returnValue;
        }                        

        protected function GetClosingEscape(): string {
            $colorBits = $this->rootParent->TriggerEvent("GET_COLOR_BITS");

            $returnValue = "";

            if (!is_null($this->foregroundColor)) {
                $foregroundColor = $this->rootParent->TriggerEvent("POP_FOREGROUND_COLOR");
                $returnValue .= (is_null($foregroundColor) ? "\e[39m" : self::GetForegroundColorEscapeSequence($colorBits, $foregroundColor));
            }

            if (!is_null($this->backgroundColor)) {
                $backgroundColor = $this->rootParent->TriggerEvent("POP_BACKGROUND_COLOR");
                $returnValue .= (is_null($backgroundColor) ? "\e[49m" : self::GetBackgroundColorEscapeSequence($colorBits, $backgroundColor));
            }

            return $returnValue;            
        }
//
        static public function GetForegroundColorEscapeSequence(int $colorBits, RGB $color) {
            if ($colorBits == 3) {
                throw new \Exception("Not implemented");
            } else if ($colorBits == 4) {
                throw new \Exception("Not implemented");
            } else if ($colorBits == 8) {
                return "\e[38;5;" . strval(intval(
                    16 + 
                    (36 * round($color->r() / 255 * 5)) + 
                    (6 * round($color->g() / 255 * 5)) + 
                    round($color->b() / 255 * 5)
                )) . "m";
            } else if ($colorBits == 24) {
                return "\e[38;2;" . $color->r() . ";" . $color->g() . ";" . $color->b() . "m";                
            } else {
                throw new \Exception("Unsupported colorBits '$colorBits");
            }
        }

        static public function GetBackgroundColorEscapeSequence(int $colorBits, RGB $color) {
            if ($colorBits == 3) {
                throw new \Exception("Not implemented");
            } else if ($colorBits == 4) {
                throw new \Exception("Not implemented");
            } else if ($colorBits == 8) {
                return "\e[48;5;" . strval(intval(
                    16 + 
                    (36 * round($color->r() / 255 * 5)) + 
                    (6 * round($color->g() / 255 * 5)) + 
                    round($color->b() / 255 * 5)
                )) . "m";
            } else if ($colorBits == 24) {
                return "\e[48;2;" . $color->r() . ";" . $color->g() . ";" . $color->b() . "m";                
            } else {
                throw new \Exception("Unsupported colorBits '$colorBits");
            }
        }

    }
?>