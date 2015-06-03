[
{assign var="currentItem" value=0}
{assign var="lastItem" value=count($liststates)}
{foreach $liststates as $state}
    {
        "state_id":"{$state["state_id"]}",
        "state_name":"{$state["state_name"]}",
        "country_id":"{$state["country_id"]}",
        "state_short":"{$state["state_short"]}"
    }
    {assign var="currentItem" value=$currentItem+1}    
    {if $currentItem < $lastItem},{/if}
{/foreach}
]