<?php
/**
 * (c) 2013-2023 Jannis Grimm <jannis@gje.ch>
 * MIT License
 */

declare(strict_types=1);

use Webmozart\Assert\Assert;

require_once __DIR__ . '/../vendor/autoload.php';

$username = 'netzhuffle'; // BoardGameGeek username (also change in games.php)

if (! isset($_GET['game'])) {
    die('no game id');
}
Assert::integerish($_GET['game']);
$gameId = (int) $_GET['game'];

$mygames = @simplexml_load_file('cache/games.xml');
if ($mygames === false) {
    $mygames = simplexml_load_file("http://boardgamegeek.com/xmlapi2/collection?username={$username}&own=1");
    if ($mygames === false) {
        die('Could not load collection from BoardGameGeek.');
    }
    if ($mygames->getName() === 'message') {
        die($mygames);
    }
    $mygames->saveXML('../cache/games.xml');
}
$infos = @simplexml_load_file("cache/game_{$gameId}.xml");
if ($infos === false) {
    $infos = simplexml_load_file("http://boardgamegeek.com/xmlapi2/thing?id={$gameId}");
    if ($infos === false) {
        die('Could not load game info from BoardGameGeek.');
    }
    if ($infos->count() >= 1) { // if game found
        $infos->saveXML("../cache/game_{$gameId}.xml");
    }
}

$expansions = $infos->xpath("//link[@type='boardgameexpansion']");
$myexpansions = [];

$expansionsNumber = 0;
$myExpansionsNumber = 0;
foreach ($expansions as $expansion) {
    $expansionsNumber++;
    $id = $expansion['id'];
    $gamearray = $mygames->xpath("//item[@objectid='{$id}']");
    if ($gamearray === false || $gamearray === null) {
        die('Could not load game info from BoardGameGeek.');
    }
    foreach ($gamearray as $game) {
        $myExpansionsNumber++;
        $name = (string) $game->name;
        $image = (string) $game->image;
        if ($game->thumbnail === null) {
            $url = 'http://placehold.it/300/f3f3f3/bbbbbb/&amp;text=';
            $text = $name;
            $text = str_replace('–', '-', $text);
            if (strlen($text) > 15) {
                $colonposition = strpos($text, ':');
                if ($colonposition !== false) {
                    $text = substr($text, $colonposition + 2); // +2 to start after ': '
                }
                if (strlen($text) > 15) {
                    $text = substr($text, 0, 14) . ' …';
                }
            }
            $url .= urlencode($text);
            $image = $url;
        }
        echo "<img height='150' src='{$image}' alt='{$name}' height='100'>";
    }
}

if ($expansionsNumber === 0) {
    echo 'no expansions released';
} elseif ($myExpansionsNumber === 0) {
    echo 'none';
}
