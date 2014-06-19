<?php
/*
 * (c) 2013-2014 Jannis Grimm <jannis@gje.ch>
 * MIT License
 */

$username = 'netzhuffle'; // BoardGameGeek username (also change in games.php)

header("Content-Type: text/html; charset=utf-8");

if (!intval($_GET['game'])) {
    die('no game id');
}

$mygames = @simplexml_load_file('cache/games.xml');
if (!$mygames) {
    $mygames = simplexml_load_file("http://boardgamegeek.com/xmlapi2/collection?username=$username&own=1");
    if ($mygames->getName() == 'message') {
        die($mygames);
    }
    $mygames->saveXML('cache/games.xml');
}
$infos = @simplexml_load_file('cache/game_' . intval($_GET['game']) . '.xml');
if (!$infos) {
    $infos = simplexml_load_file('http://boardgamegeek.com/xmlapi2/thing?id=' . intval($_GET['game']));
    if ($infos->count()) { // if game found
        $infos->saveXML('cache/game_' . intval($_GET['game']) . '.xml');
    }
}

$expansions = $infos->xpath("//link[@type='boardgameexpansion']");
$myexpansions = array();

$expansionsNumber = 0;
$myExpansionsNumber = 0;
foreach ($expansions as $expansion) {
    $expansionsNumber++;
    $id = $expansion['id'];
    $gamearray = $mygames->xpath("//item[@objectid='$id']");
    if (count($gamearray)) {
        $myExpansionsNumber++;
        $game = $gamearray[0];
        $name = $game->name;
        $image = $game->image;
        if (!$game->thumbnail) {
            $url = 'http://placehold.it/300/f3f3f3/bbbbbb/&amp;text=';
            $text = $name;
            $text = str_replace('–', '-', $text);
            if (strlen($text) > 15) {
                $colonposition = strpos($text, ':');
                $text = substr($text, $colonposition + 2); // +2 to start after ': '
                if (strlen($text) > 15) {
                    $text = substr($text, 0, 14) . ' …';
                }
            }
            $url .= urlencode($text);
            $image = $url;
        }
        echo "<img height='150' src='$image' alt='$name' height='100'>";
    }
}

if (!$expansionsNumber) {
    echo 'no expansions released';
} elseif (!$myExpansionsNumber) {
    echo 'none';
}
