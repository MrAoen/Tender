{include file="header.tpl" top_panes=true}
{pane title=$pane_title params='"class":"list"' tab=3}
{tree cols='"40%", "30%", "30%", "1"' headers=$list_headers values=$list_values tab=5}
    <div class="ptools">
        <a href="{$pane_tools.add.href|escape:'html'}">{$pane_tools.add.content}</a>
    </div>
{/pane}
{include file="footer.tpl" bottom_panes=true}
