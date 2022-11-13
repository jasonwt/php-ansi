<?php
    declare(strict_types=1);

    namespace ansi;

    use ansi\sequences\SequenceInterface;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    require_once(__DIR__ . "/RGB.php");

    interface AnsiInterface {
        static public function NEW(int $colorBits = 8) : AnsiInterface;
        static public function TEXT(string $text, ?RGB $foregroundColor = null, ?RGB $backgroundColor = null, int $colorBits = 8, bool $underline = false, bool $blinking = false, bool $inverse = false, bool $hidden = false, bool $strike = false) : AnsiInterface;
//
        public function ReadByte(string $prompt = "", $showInput = false) : string;
//
        public function ReadLine(string $prompt, bool $showInput = true, array $terminatingCharacters = array("\n")) : ?string;
//
        public function __toString() : string;
//
        public function CloseLast(int $num = 1) : AnsiInterface;
        public function cl(int $num = 1) : AnsiInterface;
//
        public function CloseAll() : AnsiInterface;
        public function ca() : AnsiInterface;
//
        public function AddSequence(SequenceInterface $sequence) : AnsiInterface;
        public function as(SequenceInterface $sequence) : AnsiInterface;
//
        public function SetForegroundColor(int $red, int $green, int $blue) : AnsiInterface;
        public function sfc(int $red, int $green, int $blue) : AnsiInterface;
//
        public function SetBackgroundColor(int $red, int $green, int $blue) : AnsiInterface;
        public function sbc(int $red, int $green, int $blue) : AnsiInterface;        
//
        public function AddCustom(string $opening, string $closing = "") : AnsiInterface;
        public function ac(string $opening, string $closing = "") : AnsiInterface;
//
        public function SetColors(?RGB $foregroundColor = null, ?RGB $backgroundColor = null) : AnsiInterface;
        public function sc(?RGB $foregroundColor = null, ?RGB $backgroundColor = null) : AnsiInterface;
//
        public function AddText(string $text) : AnsiInterface;
        public function at(string $text) : AnsiInterface;
//            
        public function Underline() : AnsiInterface;
        public function u() : AnsiInterface;
//            
        public function Blink() : AnsiInterface;
        public function bl() : AnsiInterface;
//            
        public function Inverse() : AnsiInterface;
        public function inv() : AnsiInterface;
//            
        public function Hidden() : AnsiInterface;
        public function h() : AnsiInterface;
//            
        public function Strike() : AnsiInterface;
        public function s() : AnsiInterface;
//
        public function Bold() : AnsiInterface;
        public function b() : AnsiInterface;
//
        public function Faint() : AnsiInterface;
        public function f() : AnsiInterface;
//
        public function Italic() : AnsiInterface;
        public function i() : AnsiInterface;
//
        public function Reset() : AnsiInterface;
        public function r() : AnsiInterface;
//
        public function SaveCursorPosition() : AnsiInterface;
        public function scp() : AnsiInterface;
//
        public function RestoreCursorPosition() : AnsiInterface;
        public function rcp() : AnsiInterface;
//
        public function ClearScreen() : AnsiInterface;
        public function cs() : AnsiInterface;
//
        public function MoveCursorHome() : AnsiInterface;
        public function mch() : AnsiInterface;
//
        public function MoveCursorTo(int $l, int $c) : AnsiInterface;
        public function mct(int $l, int $c) : AnsiInterface;
//
        public function MoveCursorUp(int $num) : AnsiInterface;
        public function mcu(int $num) : AnsiInterface;
//
        public function MoveCursorDown(int $num) : AnsiInterface;
        public function mcd(int $num) : AnsiInterface;
//
        public function MoveCursorLeft(int $num) : AnsiInterface;
        public function mcl(int $num) : AnsiInterface;
//
        public function MoveCursorRight(int $num) : AnsiInterface;
        public function mcr(int $num) : AnsiInterface;
//
        public function ClearCurrentLine() : AnsiInterface;
        public function ccl() : AnsiInterface;
//
        public function ClearCurrentLineToCursor() : AnsiInterface;
        public function ccltc() : AnsiInterface;
//
        public function ClearCurrentLineFromCursor() : AnsiInterface;
        public function cclfc() : AnsiInterface;
//
        public function ClearScreenToCursor() : AnsiInterface;
        public function cstc() : AnsiInterface;
//
        public function ClearScreenFromCursor() : AnsiInterface;
        public function csfc() : AnsiInterface;
//
        public function SaveScreen() : AnsiInterface;
        public function ss() : AnsiInterface;
//
        public function RestoreScreen() : AnsiInterface;
        public function rs() : AnsiInterface;        
     }
?>