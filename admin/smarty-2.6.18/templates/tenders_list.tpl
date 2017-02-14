{include file="header.tpl" top_panes=true}
{pane title=$pane_title params='"class":"list"' tab=3}
    <div id="scroller">{tree cols=$list_cols_width headers=$list_headers values=$list_values tab=6}
    </div>{pagination_pane pagination=$pagination tab=5}
    <div class="ptools{if $pagination.pages_count gt 1} second{/if}">
        <a href="{$pane_tools.add.href|escape:'html'}">{$pane_tools.add.content}</a>
    </div>
{/pane}
{include file="footer.tpl" bottom_panes=true}
