[
{assign var="currentItem" value=0}
{assign var="lastItem" value=count($citylist)}
{foreach $citylist as $city}
    {
        "city_id":"{$city["city_id"]}",
        "city_name":"{$city["city_name"]}",
        "state_id":"{$city["state_id"]}"
    }
    {assign var="currentItem" value=$currentItem+1}    
    {if $currentItem < $lastItem},{/if}
{/foreach}
]