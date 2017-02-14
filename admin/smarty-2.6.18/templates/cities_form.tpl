{include file="header.tpl" top_panes=true}
{pane title=$pane_title tab=3}
{if $last_change_info ne ''}
    <p class="first-half">{$last_change_info}</p>
{/if}
    <form name="mform" action="{$form_action|escape:'html'}" method="post"
          onsubmit="return VF('mform', 1{if $js_validation ne ''}, '{$js_validation|escape:'quotes'}'{/if})">
        <table class="params">
            {foreach from=$fields.title_keys item=k}
                <tr>
                    <td>{$fields.$k.caption}</td>
                    <td>{$fields.$k.input}</td>
                </tr>
            {/foreach}
            <tr>
                <td>&nbsp;</td>
                <td><input class="button submit" type="submit" name="sent" value="{$submit_title|escape:'html'}"/></td>
            </tr>
        </table>
        {$form_counts}
    </form>
{/pane}
{include file="footer.tpl" bottom_panes=true}
