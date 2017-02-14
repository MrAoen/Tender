{include file="header.tpl" top_panes=true}
{pane title=$pane_title tab=3}
{if $last_change_info}
    <p class="first-half">{$last_change_info}</p>
{/if}
    <form name="mform" action="{$form_action|escape:'html'}" method="post"
          onsubmit="return VF('mform', 1{if $js_validation ne ''}, '{$js_validation|escape:'quotes'}'{/if})"
          enctype="multipart/form-data">
        <table class="params">
            {if $page_action ne 'add'}
                {foreach from=$readonly_fields key=k item=v}
                    <tr class="readonly{if $v.multiline} spacer-top{/if}">
                        <td class="top readonly-wide"><acronym title="{$v.hint|escape:'html'}">{$v.caption}</acronym>:
                        </td>
                        <td>{$v.value}</td>
                    </tr>
                {/foreach}
            {/if}
            <tr>
                <td>{$fields.status.caption}</td>
                <td>
                    {radio_options_ext options=$fields.status.options name=$fields.status.name selected=$fields.status.selected multiline=$fields.status.multiline tab=8}
                </td>
            </tr>
            <tr>
                <td>{$fields.company.caption}</td>
                <td>{$fields.company.input}</td>
            </tr>
            <tr>
                <td>{$fields.name.caption}</td>
                <td>{$fields.name.input}</td>
            </tr>
            <tr>
                <td>{$fields.city.caption}</td>
                <td>{$fields.city.input}</td>
            </tr>
            <tr>
                <td>{$fields.phone.caption}</td>
                <td>{$fields.phone.input}</td>
            </tr>
            <tr>
                <td>{$fields.email.caption}</td>
                <td>{$fields.email.input}</td>
            </tr>
            <tr>
                <td>{$fields.password.caption}</td>
                <td>{$fields.password.input}</td>
            </tr>
            <tr>
                <td>{$fields.password.caption_conf}</td>
                <td>{$fields.password.input_conf}</td>
            </tr>
            <tr class="spacer-top">
                <td class="top">{$fields.srvnotes.caption}</td>
                <td>{$fields.srvnotes.input}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td><input class="button submit" type="submit" name="sent" value="{$submit_title|escape:'html'}"/></td>
            </tr>
        </table>
        {$form_counts}
    </form>
{/pane}
{include file="footer.tpl" bottom_panes=true}
