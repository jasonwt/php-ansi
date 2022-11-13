<?php
     declare(strict_types=1);
    
     namespace ansi;

use Exception;

     error_reporting(E_ALL);
     ini_set('display_errors', '1');

     class RGB {
        protected $red = 0;
        protected $green = 0;
        protected $blue = 0;

        public function __construct(int $red, int $green, int $blue) {
            $this->red = min(255, max(0, $red));
            $this->green = min(255, max(0, $green));
            $this->blue = min(255, max(0, $blue));
        }

        public function r() : int {
            return $this->red;
        }

        public function g() : int {
            return $this->green;
        }

        public function b() : int {
            return $this->blue;
        }

        public function rgb() : array {
            return [
                "r" => $this->r,
                "g" => $this->g,
                "b" => $this->b
            ];
        }

        static public function NEW(int $red, int $green, int $blue) {
            return new RGB($red, $green, $blue);
        }

        static public function HEX($hexColor) : RGB {
            if (($hexColor = trim($hexColor)) == "")
                throw new \Exception("Invalid hexColor '$hexColor'");

            if (substr($hexColor, 0, 1) == "#")
                $hexColor = substr($hexColor, 1);

            if (strlen($hexColor) != 6)
                throw new \Exception("Invalid hexColor '$hexColor'");

            if (!preg_match('/^[a-f0-9]{6}$/i', $hexColor))
                throw new \Exception("Invalid hexColor '$hexColor'");

            return new RGB(
                intval(hexdec(substr($hexColor, 0, 2))),
                intval(hexdec(substr($hexColor, 2, 2))),
                intval(hexdec(substr($hexColor, 4, 2)))
            );
        }
     }


?>