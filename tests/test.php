<?php
    declare(strict_types=1);

    ini_set('xdebug.max_nesting_level', '30000');

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    require_once(__DIR__ . "/../src/Ansi.php");
    require_once(__DIR__ . "/../src/RGB.php");
    require_once(__DIR__ . "/../src/sequences/ColorSequence.php");

    use ansi\Ansi;
    use ansi\RGB;
    use ansi\sequences\ColorSequence;

    echo Ansi::NEW()->
        SaveScreen()->
        ClearScreen()->
        MoveCursorDown(10);    
    
    for ($r = 0; $r < 256; $r+=8) {
        for ($g = 0; $g < 256; $g+=4)
            echo Ansi::TEXT(" ",null, RGB::NEW($r, $g, intval(($g+$r)/2)), 24);
        
        echo Ansi::TEXT("\n");
    }

    echo Ansi::TEXT("\n")->
        AddText("TEXT\n\n")->
        SetColors(RGB::HEX('ff0000'))->
        AddText("\tSetColors(RGB::HEX('ff0000'))\n\n")->
        SetForegroundColor(0, 255, 0)->
        AddText("\t\tSetForegroundColor(0, 255, 0)\n\n")->
        SetForegroundColor(0, 0, 255)->
        AddText("\t\t\tSetForegroundColor(0, 0, 255)\n\n")->
        SetColors(null,RGB::HEX('#ffffff'))->
        AddText("\t\t\t\tSetColors(null,RGB::HEX('#ffffff'))\n\n")->        
        SetColors(RGB::HEX('00ff00'),RGB::HEX('#0000ff'))->
        AddText("\t\t\t\t\tSetColors(RGB::HEX('00ff00'),RGB::HEX('#0000ff'))\n\n")->        
        CloseLast()->
        AddText("\t\t\t\tCloseLast()\n\n")->
        CloseLast()->
        AddText("\t\t\tCloseLast()\n\n")->
        Underline()->
        AddText("\t\t\t\tUnderline()\n\n")->
        CloseLast()->
        AddText("\t\t\tCloseLast()\n\n")->
        CloseLast()->
        AddText("\t\tCloseLast()\n\n")->
        Strike()->
        AddText("\t\t\tStrike()\n\n")->
        Blink()->
        AddText("\t\t\t\tBlink()\n\n")->
        CloseLast()->
        AddText("\t\t\tCloseLast()\n\n")->
        CloseLast()->
        AddText("\t\tCloseLast()\n\n")->
        CloseLast()->
        AddText("\tCloseLast()\n\n")->
        CloseLast()->
        AddText("TEXT\n\n");

    $tmp = Ansi::NEW()->ReadLine("echo input: ", true);
    echo "\n\ninput: '" . $tmp . "'\n\n";

    $tmp = Ansi::NEW()->ReadLine("no echo input: ", false);
    echo "\n\ninput: '" . $tmp . "'\n\n";

    $key_pressed = Ansi::NEW()->ReadByte("press any key: ", false);
    echo "\n\nkey pressed: " . ord($key_pressed) . "\n\n";

    Ansi::NEW()->ReadByte("Press any key to continue", false);

    echo Ansi::NEW()->RestoreScreen();
?>