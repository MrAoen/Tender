{include file="header.tpl" top_panes=true}
{pane title=$pane_title params='"class":"list"' tab=3}
{tree cols=$list_cols_width headers=$list_headers values=$list_values tab=5}
    <div class="ptools">

        <a href="{$pane_tools.add_chapter.href|escape:'html'}">{$pane_tools.add_chapter.content}</a>
        <a href="{$pane_tools.add_item.href|escape:'html'}">{$pane_tools.add_item.content}</a>
    </div>
{/pane}
{include file="footer.tpl" bottom_panes=true}
