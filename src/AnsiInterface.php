<?php
    declare(strict_types=1);

    namespace ansi;

    use ansi\sequences\SequenceInterface;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    require_once(__DIR__ . "/RGB.php");

    interface AnsiInterface {
        static public function NEW(int $colorBits = 8) : Ansi;
        static public function TEXT(string $text, ?RGB $foregroundColor = null, ?RGB $backgroundColor = null, int $colorBits = 8, bool $underline = false, bool $blinking = false, bool $inverse = false, bool $hidden = false, bool $strike = false) : Ansi;
//
        public function ReadByte(string $prompt = "", $showInput = false) : string;
//
        public function ReadLine(string $prompt, bool $showInput = true, array $terminatingCharacters = array("\n")) : ?string;
//
        public function __toString() : string;
//
        public function CloseLast(int $num = 1) : Ansi;
        public function cl(int $num = 1) : Ansi;
//
        public function CloseAll() : Ansi;
        public function ca() : Ansi;
//
        public function AddSequence(SequenceInterface $sequence) : Ansi;
        public function as(SequenceInterface $sequence) : Ansi;
//
        public function SetForegroundColor(int $red, int $green, int $blue) : Ansi;
        public function sfc(int $red, int $green, int $blue) : Ansi;
//
        public function SetBackgroundColor(int $red, int $green, int $blue) : Ansi;
        public function sbc(int $red, int $green, int $blue) : Ansi;        
//
        public function AddCustom(string $opening, string $closing = "") : Ansi;
        public function ac(string $opening, string $closing = "") : Ansi;
//
        public function SetColors(?RGB $foregroundColor = null, ?RGB $backgroundColor = null) : Ansi;
        public function sc(?RGB $foregroundColor = null, ?RGB $backgroundColor = null) : Ansi;
//
        public function AddText(string $text) : Ansi;
        public function at(string $text) : Ansi;
//            
        public function Underline() : Ansi;
        public function u() : Ansi;
//            
        public function Blink() : Ansi;
        public function bl() : Ansi;
//            
        public function Inverse() : Ansi;
        public function inv() : Ansi;
//            
        public function Hidden() : Ansi;
        public function h() : Ansi;
//            
        public function Strike() : Ansi;
        public function s() : Ansi;
//
        public function Bold() : Ansi;
        public function b() : Ansi;
//
        public function Faint() : Ansi;
        public function f() : Ansi;
//
        public function Italic() : Ansi;
        public function i() : Ansi;
//
        public function Reset() : Ansi;
        public function r() : Ansi;
//
        public function SaveCursorPosition() : Ansi;
        public function scp() : Ansi;
//
        public function RestoreCursorPosition() : Ansi;
        public function rcp() : Ansi;
//
        public function ClearScreen() : Ansi;
        public function cs() : Ansi;
//
        public function MoveCursorHome() : Ansi;
        public function mch() : Ansi;
//
        public function MoveCursorTo(int $l, int $c) : Ansi;
        public function mct(int $l, int $c) : Ansi;
//
        public function MoveCursorUp(int $num) : Ansi;
        public function mcu(int $num) : Ansi;
//
        public function MoveCursorDown(int $num) : Ansi;
        public function mcd(int $num) : Ansi;
//
        public function MoveCursorLeft(int $num) : Ansi;
        public function mcl(int $num) : Ansi;
//
        public function MoveCursorRight(int $num) : Ansi;
        public function mcr(int $num) : Ansi;
//
        public function ClearCurrentLine() : Ansi;
        public function ccl() : Ansi;
//
        public function ClearCurrentLineToCursor() : Ansi;
        public function ccltc() : Ansi;
//
        public function ClearCurrentLineFromCursor() : Ansi;
        public function cclfc() : Ansi;
//
        public function ClearScreenToCursor() : Ansi;
        public function cstc() : Ansi;
//
        public function ClearScreenFromCursor() : Ansi;
        public function csfc() : Ansi;
//
        public function SaveScreen() : Ansi;
        public function ss() : Ansi;
//
        public function RestoreScreen() : Ansi;
        public function rs() : Ansi;        
     }
?>