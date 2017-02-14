{include file="header.tpl" top_panes=true}
{pane title=$pane_title tab=3}
{if $last_change_info ne ''}
    <p class="first-half">{$last_change_info}</p>
{/if}
    <form name="mform" action="{$form_action|escape:'html'}" method="post"
          onsubmit="return VF('mform', 1{if $js_validation ne ''}, '{$js_validation|escape:'quotes'}'{/if})">
        <table class="params params-narrow">
            <tr>
                <td class="top spacer-right">Отображать поля в списке:</td>
                <td>{$fields.ord.before|replace:'<label':'<label class="multiline"'}{$fields.ord.input}{$fields.ord.caption}{$fields.ord.after}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>{$fields.addts.before|replace:'<label':'<label class="multiline"'}{$fields.addts.input}{$fields.addts.caption}{$fields.addts.after}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>{$fields.addip.before|replace:'<label':'<label class="multiline"'}{$fields.addip.input}{$fields.addip.caption}{$fields.addip.after}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>{$fields.lastin.before|replace:'<label':'<label class="multiline"'}{$fields.lastin.input}{$fields.lastin.caption}{$fields.lastin.after}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>{$fields.lastip.before|replace:'<label':'<label class="multiline"'}{$fields.lastip.input}{$fields.lastip.caption}{$fields.lastip.after}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>{$fields.company.before|replace:'<label':'<label class="multiline"'}{$fields.company.input}{$fields.company.caption}{$fields.company.after}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>{$fields.name.before|replace:'<label':'<label class="multiline"'}{$fields.name.input}{$fields.name.caption}{$fields.name.after}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>{$fields.city.before|replace:'<label':'<label class="multiline"'}{$fields.city.input}{$fields.city.caption}{$fields.city.after}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>{$fields.phone.before|replace:'<label':'<label class="multiline"'}{$fields.phone.input}{$fields.phone.caption}{$fields.phone.after}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>{$fields.email.before|replace:'<label':'<label class="multiline"'}{$fields.email.input}{$fields.email.caption}{$fields.email.after}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>{$fields.status.before|replace:'<label':'<label class="multiline"'}{$fields.status.input}{$fields.status.caption}{$fields.status.after}</td>
            </tr>
            <tr>
                <td class="readonly-wide">{$fields.ones_per_page.caption}</td>
                <td>
                    {$fields.ones_per_page.before}
                    {options_ext options=$fields.ones_per_page.options selected=$fields.ones_per_page.selected tab=9}
                    {$fields.ones_per_page.after}
                </td>
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
