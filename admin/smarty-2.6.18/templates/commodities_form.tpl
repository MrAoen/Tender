{include file="header.tpl" top_panes=true}
{pane title=$pane_title tab=3}
{if $last_change_info}
    <p class="first-half">{$last_change_info}</p>
{/if}
    <form name="mform" action="{$form_action|escape:'html'}" method="post"
          onsubmit="return VF('mform', 1{if $js_validation ne ''}, '{$js_validation|escape:'quotes'}'{/if})"
          enctype="multipart/form-data">
        <table class="params">
            <tr>
                <td colspan="2">{$fields.parentid.caption}</td>
                <td>
                    {$fields.parentid.before}
                    {options_ext options=$fields.parentid.options selected=$fields.parentid.selected tab=9}
                    {$fields.parentid.after}
                </td>
            </tr>
            <tr>
                <td colspan="2">{$fields.ord.caption}</td>
                <td>
                    {$fields.ord.before}
                    {options_ext options=$fields.ord.options selected=$fields.ord.selected tab=9}
                    {$fields.ord.after}
                </td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
                <td class="last">
                    {$fields.ispublished.before|replace:'<label':'<label class="multiline"'}{$fields.ispublished.input}{$fields.ispublished.caption}{$fields.ispublished.after}
                    {$fields.isprotected.before|replace:'<label':'<label class="multiline"'}{$fields.isprotected.input}{$fields.isprotected.caption}{$fields.isprotected.after}
                </td>
            </tr>
            {foreach from=$fields.title_keys item=k}
                <tr>
                    <td colspan="2">{$fields.$k.caption}</td>
                    <td>{$fields.$k.input}</td>
                </tr>
            {/foreach}
            {foreach from=$fields.keywords_keys item=k}
                <tr>
                    <td colspan="2">{$fields.$k.caption}</td>
                    <td>{$fields.$k.input}</td>
                </tr>
            {/foreach}
            {foreach from=$fields.description_keys item=k}
                <tr>
                    <td colspan="2">{$fields.$k.caption}</td>
                    <td>{$fields.$k.input}</td>
                </tr>
            {/foreach}
            {foreach from=$fields.body_keys item=k}
                <tr>
                    <td colspan="3">
                        <p class="form-elm-caption">{$fields[$k.body].caption}</p>
                        {$fields[$k.body].input}
                    </td>
                </tr>
            {/foreach}
            {if $fields.url.caption}
                <tr>
                    <td colspan="2">{$fields.url.caption}</td>
                    <td>{$fields.url.input}</td>
                </tr>
            {/if}
            <tr>
                <td colspan="2">&nbsp;</td>
                <td><input class="button submit" type="submit" name="sent" value="{$submit_title|escape:'html'}"/></td>
            </tr>
        </table>
        {$form_counts}
    </form>
{/pane}
{include file="footer.tpl" bottom_panes=true}
