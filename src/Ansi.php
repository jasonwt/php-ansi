<?php
    //https://gist.github.com/fnky/458719343aabd01cfb17a3a4f7296797
    //https://stackoverflow.com/questions/59864485/capturing-mouse-in-virtual-terminal-with-ansi-escape#59868142
    //https://en.wikipedia.org/wiki/ANSI_escape_code
    //https://stackoverflow.com/questions/4842424/list-of-ansi-color-escape-sequences

     declare(strict_types=1);
    
     namespace ansi;
 
     error_reporting(E_ALL);
     ini_set('display_errors', '1');

     require_once(__DIR__ . "/AnsiInterface.php");

     require_once(__DIR__ . "/sequences/SequenceInterface.php");
     require_once(__DIR__ . "/sequences/AbstractSequence.php");
     require_once(__DIR__ . "/sequences/CustomSequence.php");
     require_once(__DIR__ . "/sequences/AbstractNoClosingSequence.php");
     require_once(__DIR__ . "/sequences/CustomNoClosingSequence.php");
     require_once(__DIR__ . "/sequences/ColorSequence.php");
     require_once(__DIR__ . "/sequences/TextSequence.php");
     require_once(__DIR__ . "/sequences/AbstractNoSequence.php");

     require_once(__DIR__ . "/RGB.php");

     use ansi\sequences\SequenceInterface;
     use ansi\sequences\AbstractSequence;
     use ansi\sequences\CustomSequence;
     use ansi\sequences\AbstractNoClosingSequence;
     use ansi\sequences\CustomNoClosingSequence;
     use ansi\sequences\ColorSequence;
     use ansi\sequences\TextSequence;
     use ansi\sequences\AbstractNoSequence;

     class Ansi extends AbstractNoSequence implements AnsiInterface {
        protected $colorBits = 24;
        protected SequenceInterface $tail;

        protected array $foregroundColorHistory = array();
        protected array $backgroundColorHistory = array();
//
        public function __construct(?RGB $foregroundColor = null, ?RGB $backgroundColor = null, int $colorBits = 24) {
            parent::__construct();

            $this->tail = $this;

            if ($colorBits != 3 && $colorBits != 4 && $colorBits != 8 && $colorBits != 24)
                throw new \Exception("Invalid colorBits '$colorBits'. Expected 3, 4, 8 or 24.");

            $this->colorBits = $colorBits;

            if (!is_null($foregroundColor))
                $this->SetForegroundColor($foregroundColor->r(), $foregroundColor->g(), $foregroundColor->b());

            if (!is_null($backgroundColor))
                $this->SetBackgroundColor($backgroundColor->r(), $backgroundColor->g(), $backgroundColor->b());

            return $this;
        }
//
        public function __toString() : string {             
            $returnValue = "";

            foreach ($this->children as $child)
                $returnValue .= $child;

//          $returnValue .= "\e[0m";
//          return str_replace("\e", "\n\\e", $returnValue);
            return $returnValue;       
        }
//
        static public function NEW(int $colorBits = 8) : Ansi {
            return new Ansi(null,null,$colorBits);
        }        
//
        static public function TEXT(string $text, ?RGB $foregroundColor = null, ?RGB $backgroundColor = null, int $colorBits = 8, bool $underline = false, bool $blinking = false, bool $inverse = false, bool $hidden = false, bool $strike = false, SequenceInterface $parent = null) : Ansi {
            $ansi = new Ansi($foregroundColor, $backgroundColor, $colorBits, $parent);            

            if ($underline)
                $ansi->Underline();

            if ($blinking)
                $ansi->Blink();

            if ($inverse)
                $ansi->Inverse();

            if ($hidden)
                $ansi->Hidden();

            if ($strike)
                $ansi->Strike();

            if ($text)
                $ansi->AddText($text);

            return $ansi;
        }
//
        public function TriggerEvent(string $eventName, array $eventArgs = []) {
            if ($eventName == "GET_COLOR_BITS") {
                return $this->colorBits;
            } if ($eventName == "PUSH_FOREGROUND_COLOR") {
                $this->foregroundColorHistory[] = $eventArgs["rgb"];
            } else if ($eventName == "POP_FOREGROUND_COLOR") {
                array_pop($this->foregroundColorHistory);

                return (count($this->foregroundColorHistory) == 0 ? null : $this->foregroundColorHistory[count($this->foregroundColorHistory)-1]);                
            } else if ($eventName == "PUSH_BACKGROUND_COLOR") {
                $this->backgroundColorHistory[] = $eventArgs["rgb"];
            } else if ($eventName == "POP_BACKGROUND_COLOR") {
                array_pop($this->backgroundColorHistory);

                return (count($this->backgroundColorHistory) == 0 ? null : $this->backgroundColorHistory[count($this->backgroundColorHistory)-1]);
            } else {
                parent::TriggerEvent($eventName, $eventArgs);
            }
        }
//        
        public function ReadByte(string $prompt = "", $showInput = false) : string {
            readline_callback_handler_install($prompt, function() {});
            $char = stream_get_contents(STDIN, 1);
            readline_callback_handler_remove();

            if ($showInput)
                echo $char;

            return $char;
        }
//
        public function ReadLine(string $prompt, bool $showInput = true, array $terminatingCharacters = array("\n")) : ?string {
            echo $prompt;

            $dataString = "";

            while (true) {
                $char = $this->ReadByte("", false);

                if (in_array($char, $terminatingCharacters)) {
                    break;                    
                } else if (ord($char) == 127) {
                    if (strlen($dataString) > 0) {
                        if ($showInput)
                            echo $char;

                        $dataString = substr($dataString, 0, strlen($dataString) - 1);
                    }
                } else if (ord($char) == 27) {

                    echo ord($char) . " ";
                } else {
                    if ($showInput)
                        echo $char;
                                                
                    $dataString .= $char;
                }
            }

            return $dataString;
        }
//
        protected function AddElement(SequenceInterface $element) : Ansi {                
            $this->tail->AddChild($element);

            if (!($element instanceof AbstractNoClosingSequence))                    
                $this->tail = $element;

            return $this;
        }
//
        public function CloseLast(int $num = 1) : Ansi {
            for ($cnt = 0; $cnt < max(1, $num) && !is_null($this->tail->GetParent()); $cnt ++)
                $this->tail = $this->tail->GetParent();                    
            
            return $this;
        }            

        public function cl(int $num = 1) : Ansi {
            return $this->CloseLast($num);
        }
//
        public function CloseAll() : Ansi {
            while (!is_null($this->tail->GetParent())) {
                $this->CloseLast();                    
            }                

            return $this;
        }
        
        public function ca() : Ansi {
            return $this->CloseAll();
        }
//
        public function AddSequence(SequenceInterface $sequence) : Ansi {
            return $this->AddElement($sequence);
        }

        public function as(SequenceInterface $sequence) : Ansi {
            return $this->AddSequence($sequence);
        }
//
        public function SetForegroundColor(int $red, int $green, int $blue) : Ansi {
            return $this->SetColors(RGB::NEW($red, $green, $blue));
        }

        public function sfc(int $red, int $green, int $blue) : Ansi {
            return $this->SetForegroundColor($red, $green, $blue);                
        }
//
        public function SetBackgroundColor(int $red, int $green, int $blue) : Ansi {
            return $this->SetColors(null, RGB::NEW($red, $green, $blue));
        }

        public function sbc(int $red, int $green, int $blue) : Ansi {
            return $this->SetBackgroundColor($red, $green, $blue);                
        }
//
        public function SetColors(?RGB $foregroundColor = null, ?RGB $backgroundColor = null) : Ansi {
            return (
                $this->AddElement(
                    (new ColorSequence($foregroundColor, $backgroundColor, $this->tail))
                )
            );            
        }

        public function sc(?RGB $foregroundColor = null, ?RGB $backgroundColor = null) : Ansi {
            return $this->SetColors($foregroundColor, $backgroundColor);
        }
//
        public function AddCustom(string $opening, string $closing = "") : Ansi {
            if ($closing)
                return $this->AddElement(new CustomSequence($opening, $closing, $this->tail));

            return $this->AddElement(new CustomNoClosingSequence($opening, $this->tail));
        }

        public function ac(string $opening, string $closing = "") : Ansi {
            return $this->AddCustom($opening, $closing);
        }            
//
        public function AddText(string $text) : Ansi {
            return $this->AddElement(new TextSequence($text, $this->tail));
        }

        public function at(string $text) : Ansi {
            return $this->AddText($text);
        }
//            
        public function Underline() : Ansi {
            return $this->AddElement(new CustomSequence("\e[4m", "\e[24m", $this->tail));
        }

        public function u() : Ansi {
            return $this->Underline();
        }
//            
        public function Blink() : Ansi {                
            return $this->AddElement(new CustomSequence("\e[5m", "\e[25m", $this->tail));
        }

        public function bl() : Ansi {
            return $this->Blink();
        }
//            
        public function Inverse() : Ansi {
            return $this->AddElement(new CustomSequence("\e[7m", "\e[27m", $this->tail));                
        }

        public function inv() : Ansi {
            return $this->Inverse();
        }
//            
        public function Hidden() : Ansi {                
            return $this->AddElement(new CustomSequence("\e[8m", "\e[28m", $this->tail));
        }

        public function h() : Ansi {
            return $this->Hidden();
        }
//            
        public function Strike() : Ansi {                
            return $this->AddElement(new CustomSequence("\e[9m", "\e[29m", $this->tail));
        }

        public function s() : Ansi {
            return $this->Strike();
        }
//
        public function Bold() : Ansi {                
            return $this->AddElement(new CustomSequence("\e[1m", "\e[22m", $this->tail));
        }

        public function b() : Ansi {
            return $this->Bold();
        }            
//
        public function Faint() : Ansi {                
            return $this->AddElement(new CustomSequence("\e[2m", "\e[22m", $this->tail));
        }

        public function f() : Ansi {
            return $this->Faint();
        }            
//
        public function Italic() : Ansi {                
            return $this->AddElement(new CustomSequence("\e[3m", "\e[22m", $this->tail));
        }

        public function i() : Ansi {
            return $this->Italic();
        }                        
//
        public function Reset() : Ansi {                
            return $this->AddElement(new CustomNoClosingSequence("\e[0m", $this->tail));
        }

        public function r() : Ansi {
            return $this->Reset();
        }                        
//
        public function SaveCursorPosition() : Ansi {
            return $this->AddElement(new CustomNoClosingSequence("\e[s", $this->tail));
        }

        public function scp() : Ansi {
            return $this->SaveCursorPosition();
        }
//
        public function RestoreCursorPosition() : Ansi {
            return $this->AddElement(new CustomNoClosingSequence("\e[u", $this->tail));
        }

        public function rcp() : Ansi {
            return $this->RestoreCursorPosition();
        }
//
        public function ClearScreen() : Ansi {
            return $this->AddElement(new CustomNoClosingSequence("\e[2J\e[H", $this->tail));
        }

        public function cs() : Ansi {
            return $this->ClearScreen();
        }            
//
        public function MoveCursorHome() : Ansi {
            return $this->AddElement(new CustomNoClosingSequence("\e[H", $this->tail));
        }

        public function mch() : Ansi {
            return $this->MoveCursorHome();
        }                        
//
        public function MoveCursorTo(int $l, int $c) : Ansi {
            return $this->AddElement(new CustomNoClosingSequence("\e[$l;$c" . "f", $this->tail));
        }

        public function mct(int $l, int $c) : Ansi {
            return $this->MoveCursorTo($l,$c);
        }                                    
//
        public function MoveCursorUp(int $num) : Ansi {
            return $this->AddElement(new CustomNoClosingSequence("\e[$num" . "A", $this->tail));
        }

        public function mcu(int $num) : Ansi {
            return $this->MoveCursorUp($num);
        }
//
        public function MoveCursorDown(int $num) : Ansi {
            return $this->AddElement(new CustomNoClosingSequence("\e[$num" . "B", $this->tail));
        }

        public function mcd(int $num) : Ansi {
            return $this->MoveCursorDown($num);
        }
//
        public function MoveCursorLeft(int $num) : Ansi {
            return $this->AddElement(new CustomNoClosingSequence("\e[$num" . "D", $this->tail));
        }

        public function mcl(int $num) : Ansi {
            return $this->MoveCursorLeft($num);
        }
//
        public function MoveCursorRight(int $num) : Ansi {
            return $this->AddElement(new CustomNoClosingSequence("\e[$num" . "C", $this->tail));
        }

        public function mcr(int $num) : Ansi {
            return $this->MoveCursorRight($num);
        }            
//
        public function ClearCurrentLine() : Ansi {
            return $this->AddElement(new CustomNoClosingSequence("\e[2K", $this->tail));
        }

        public function ccl() : Ansi {
            return $this->ClearCurrentLine();
        }                        
//
        public function ClearCurrentLineToCursor() : Ansi {
            return $this->AddElement(new CustomNoClosingSequence("\e[1K", $this->tail));
        }

        public function ccltc() : Ansi {
            return $this->ClearCurrentLineToCursor();
        }                        
//
        public function ClearCurrentLineFromCursor() : Ansi {
            return $this->AddElement(new CustomNoClosingSequence("\e[0K", $this->tail));
        }

        public function cclfc() : Ansi {
            return $this->ClearCurrentLineFromCursor();
        }                                    
//
        public function ClearScreenToCursor() : Ansi {
            return $this->AddElement(new CustomNoClosingSequence("\e[1J", $this->tail));
        }

        public function cstc() : Ansi {
            return $this->ClearScreenToCursor();
        }                        
//
        public function ClearScreenFromCursor() : Ansi {
            return $this->AddElement(new CustomNoClosingSequence("\e[0J", $this->tail));
        }

        public function csfc() : Ansi {
            return $this->ClearScreenFromCursor();
        }
//
        public function SaveScreen() : Ansi {
            return $this->AddElement(new CustomNoClosingSequence("\e[?47h", $this->tail));
        }
        public function ss() : Ansi {
            return $this->SaveScreen();
        }
//
        public function RestoreScreen() : Ansi {
            return $this->AddElement(new CustomNoClosingSequence("\e[?47l", $this->tail));
        }
        public function rs() : Ansi {
            return $this->RestoreScreen();
        }
     }
?>