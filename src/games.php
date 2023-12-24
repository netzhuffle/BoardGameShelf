<?php
/**
 * (c) 2013-2023 Jannis Grimm <jannis@gje.ch>
 * MIT License
 */

declare(strict_types=1);

/**
 * @return array<array{
 *     name: string,
 *     thumbnail: string,
 *     rating: float,
 *     average: float,
 *     yearpublished: int,
 *     players: string,
 *     playingtime: string,
 *     collid: string,
 *     objectid: string,
 *     isNew: bool
 * }>
 */
function getGames(): array
{
    $username = 'netzhuffle'; // BoardGameGeek username (also change in expansions.php)

    $xml = @simplexml_load_file('cache/basegames.xml');
    if ($xml === false) {
        $xml = simplexml_load_file("http://boardgamegeek.com/xmlapi2/collection?username={$username}&own=1&stats=1&excludesubtype=boardgameexpansion");
        if ($xml === false) {
            die('Could not load collection from BoardGameGeek.');
        }
        if ($xml->getName() === 'message') {
            die($xml);
        }
        $xml->saveXML('cache/basegames.xml');
    }

    define('FIRST_SHOULD_COME_FIRST', -1);
    define('SECOND_SHOULD_COME_FIRST', 1);
    define('DOESNT_MATTER', 0);

    $games = [];
    foreach ($xml->item as $item) {
        $game = [];
        $game['name'] = (string) $item->name;
        $game['thumbnail'] = (string) $item->image;
        if ($item->image === null) {
            $url = 'http://placehold.it/300/f3f3f3/bbbbbb/&amp;text=';
            $text = (string) $item->name;
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
            $game['thumbnail'] = $url;
        }
        $game['rating'] = floatval($item->stats->rating['value']);
        $game['average'] = floatval($item->stats->rating->average['value']);
        $game['yearpublished'] = intval($item->yearpublished);
        $game['players'] = $item->stats['minplayers'];
        if (intval($item->stats['minplayers']) !== intval($item->stats['maxplayers'])) {
            $game['players'] .= '–' . $item->stats['maxplayers'];
        }
        $game['playingtime'] = $item->stats['playingtime'] . ' minutes';
        $game['collid'] = (string) $item['collid'];
        $game['objectid'] = (string) $item['objectid'];
        $game['isNew'] = strtotime((string) $item->status['lastmodified']) > strtotime('-4 months');
        $games[] = $game;
    }
    usort($games, function ($first, $second) {
        if ($first['rating'] !== $second['rating']) {
            if ($first['rating'] > $second['rating']) {
                return FIRST_SHOULD_COME_FIRST;
            }

            return SECOND_SHOULD_COME_FIRST;
        }
        if ($first['average'] !== $second['average']) {
            if ($first['average'] > $second['average']) {
                return FIRST_SHOULD_COME_FIRST;
            }

            return SECOND_SHOULD_COME_FIRST;
        }
        if ($first['yearpublished'] !== $second['yearpublished']) {
            if ($first['yearpublished'] > $second['yearpublished']) {
                return FIRST_SHOULD_COME_FIRST;
            }

            return SECOND_SHOULD_COME_FIRST;
        }
        if ($first['collid'] !== $second['collid']) {
            if ($first['collid'] > $second['collid']) {
                return FIRST_SHOULD_COME_FIRST;
            }

            return SECOND_SHOULD_COME_FIRST;
        }

        return DOESNT_MATTER;
    });

    return $games;
}
