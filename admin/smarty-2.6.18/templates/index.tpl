{include file="header.tpl" top_panes=true}
{if $is_intro}
    {pane title=$pane_title tab=3}
        <p>{$auth_info}</p>
    {if $lastin_info ne ''}
        <p>{$lastin_info}</p>
    {/if}
        <p>{$logout_notify}</p>
    {/pane}
{/if}
{if $is_stats}
    {pane title=$stats_title params='"class":"list index"' tab=3}
    {tree cols='"100%", "1", "1"' headers=$list_headers values=$list_values tab=5}
    {/pane}
{/if}
{include file="footer.tpl" bottom_panes=true}
