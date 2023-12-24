<?php
/**
 * (c) 2013-2023 Jannis Grimm <jannis@gje.ch>
 * MIT License
 */

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/games.php';
$games = getGames();

?>
<!DOCTYPE html>
<meta charset='utf-8'>
<title>BoardGameShelf</title>
<meta name="viewport" content="width=900">
<link rel="stylesheet" href="src/style.css">
<h1>BoardGameShelf</h1>
<?php foreach ($games as $game) { ?>
    <img
        height="150"
        src="<?= $game['thumbnail'] ?>"
        alt="<?= $game['name'] ?>"
        title="<?= $game['name'] ?>"
        data-name="<?= $game['name'] ?>"
        data-rating="<?= $game['rating'] > 0 ? $game['rating'] : 'N/A' ?>"
        data-average="<?= number_format($game['average'], 1) ?>"
        data-players="<?= $game['players'] ?>"
        data-playingtime="<?= $game['playingtime'] ?>"
        data-yearpublished="<?= $game['yearpublished'] ?>"
        data-objectid="<?= $game['objectid'] ?>"
    >
<?php } ?>
<footer style="font-style: italic"><small>Data and images by <a href="http://www.boardgamegeek.com/">BoardGameGeek</a></small></footer>
<div hidden id='infopanel'>
    <img height='150' src='src/boardgameshelf.gif' id='infoimage' alt=''>
    <div>
        <h2 id='infoname'></h2>
        <dl>
            <dt>My Rating:</dt>
                <dd id='inforating'></dd>
            <dt>Average:</dt>
                <dd id='infoaverage'></dd>
            <dt>Players:</dt>
                <dd id='infoplayers'></dd>
            <dt>Playing Time:</dt>
                <dd id='infoplayingtime'></dd>
            <dt>Published:</dt>
                <dd id='infoyearpublished'></dd>
            <dt>Owned Expansions:</dt>
                <dd id='infoownedexpansions'></dd>
        </dl>
    </div>
</div>
<script src="src/script.js"></script>
