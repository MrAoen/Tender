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
                <td>{$fields.number.before|replace:'<label':'<label class="multiline"'}{$fields.number.input}{$fields.number.caption}{$fields.number.after}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>{$fields.startts.before|replace:'<label':'<label class="multiline"'}{$fields.startts.input}{$fields.startts.caption}{$fields.startts.after}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>{$fields.length.before|replace:'<label':'<label class="multiline"'}{$fields.length.input}{$fields.length.caption}{$fields.length.after}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>{$fields.loadingcity.before|replace:'<label':'<label class="multiline"'}{$fields.loadingcity.input}{$fields.loadingcity.caption}{$fields.loadingcity.after}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>{$fields.loadingaddress.before|replace:'<label':'<label class="multiline"'}{$fields.loadingaddress.input}{$fields.loadingaddress.caption}{$fields.loadingaddress.after}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>{$fields.loadingts.before|replace:'<label':'<label class="multiline"'}{$fields.loadingts.input}{$fields.loadingts.caption}{$fields.loadingts.after}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>{$fields.course.before|replace:'<label':'<label class="multiline"'}{$fields.course.input}{$fields.course.caption}{$fields.course.after}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>{$fields.volume.before|replace:'<label':'<label class="multiline"'}{$fields.volume.input}{$fields.volume.caption}{$fields.volume.after}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>{$fields.body.before|replace:'<label':'<label class="multiline"'}{$fields.body.input}{$fields.body.caption}{$fields.body.after}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>{$fields.pricestart.before|replace:'<label':'<label class="multiline"'}{$fields.pricestart.input}{$fields.pricestart.caption}{$fields.pricestart.after}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>{$fields.pricewin.before|replace:'<label':'<label class="multiline"'}{$fields.pricewin.input}{$fields.pricewin.caption}{$fields.pricewin.after}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>{$fields.pricecurrent.before|replace:'<label':'<label class="multiline"'}{$fields.pricecurrent.input}{$fields.pricecurrent.caption}{$fields.pricecurrent.after}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>{$fields.lastuser.before|replace:'<label':'<label class="multiline"'}{$fields.lastuser.input}{$fields.lastuser.caption}{$fields.lastuser.after}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>{$fields.propositions.before|replace:'<label':'<label class="multiline"'}{$fields.propositions.input}{$fields.propositions.caption}{$fields.propositions.after}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>{$fields.iscomplete.before|replace:'<label':'<label class="multiline"'}{$fields.iscomplete.input}{$fields.iscomplete.caption}{$fields.iscomplete.after}</td>
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
