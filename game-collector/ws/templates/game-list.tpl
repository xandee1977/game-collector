[
{assign var="currentItem" value=0}
{assign var="lastItem" value=count($games)}
{foreach $games as $game}
    {
        "game_id":{$game["game_id"]|json_encode},
        "game_title":{$game["game_title"]|json_encode},
        "game_desc":{$game["game_desc"]|json_encode},
        "game_developer":{$game["game_developer"]|json_encode},
        "game_type_id":{$game["game_type_id"]|json_encode},
        "image":{$game["image"]|json_encode},
        "system_id":{$game["system_id"]|json_encode},
        "flags": {$game["flags"]|json_encode}
    }
    {assign var="currentItem" value=$currentItem+1}    
    {if $currentItem < $lastItem},{/if}
{/foreach}
]