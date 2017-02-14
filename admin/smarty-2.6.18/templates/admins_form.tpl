{include file="header.tpl" top_panes=true}
{pane title=$pane_title tab=3}
{if $last_change_info ne ''}
    <p class="first-half">{$last_change_info}</p>
{/if}
{if $last_login ne ''}
    <p>{$last_login}</p>
{/if}
    <form name="mform" action="{$form_action|escape:'html'}" method="post"
          onsubmit="return VF('mform', 1{if $js_validation ne ''}, '{$js_validation|escape:'quotes'}'{/if})">
        <table class="params">
            {if $is_admin}
                {*
                                        <tr>
                                            <td>{$fields.typeid.caption}</td>
                                            <td>
                                                {radio_options_ext options=$fields.typeid.options name=$fields.typeid.name selected=$fields.typeid.selected multiline=$fields.typeid.multiline tab=8}
                                            </td>
                                        </tr>
                *}
                <tr>
                    <td>{$fields.fname.caption}</td>
                    <td>{$fields.fname.input}</td>
                </tr>
                <tr>
                    <td>{$fields.sname.caption}</td>
                    <td>{$fields.sname.input}</td>
                </tr>
                <tr>
                    <td>{$fields.lname.caption}</td>
                    <td>{$fields.lname.input}</td>
                </tr>
                <tr>
                    <td>{$fields.emails.caption}</td>
                    <td>{$fields.emails.input}</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>{$fields.notify.before|replace:'<label':'<label class="multiline"'}{$fields.notify.input}{$fields.notify.caption}{$fields.notify.after}</td>
                </tr>
                <tr>
                    <td>{$fields.login.caption}</td>
                    <td>{$fields.login.input}</td>
                </tr>
            {else}
                {*
                                        <tr class="readonly">
                                            <td><acronym title="{$readonly_fields.typeid.hint|escape:'html'}">{$readonly_fields.typeid.caption}</acronym>:</td>
                                            <td>{$readonly_fields.typeid.value}</td>
                                        </tr>
                *}
                <tr class="readonly">
                    <td><acronym
                                title="{$readonly_fields.fname.hint|escape:'html'}">{$readonly_fields.fname.caption}</acronym>:
                    </td>
                    <td>{$readonly_fields.fname.value}</td>
                </tr>
                <tr class="readonly">
                    <td><acronym
                                title="{$readonly_fields.sname.hint|escape:'html'}">{$readonly_fields.sname.caption}</acronym>:
                    </td>
                    <td>{$readonly_fields.sname.value}</td>
                </tr>
                <tr class="readonly">
                    <td><acronym
                                title="{$readonly_fields.lname.hint|escape:'html'}">{$readonly_fields.lname.caption}</acronym>:
                    </td>
                    <td>{$readonly_fields.lname.value}</td>
                </tr>
                <tr class="readonly">
                    <td><acronym
                                title="{$readonly_fields.emails.hint|escape:'html'}">{$readonly_fields.emails.caption}</acronym>:
                    </td>
                    <td>{$readonly_fields.emails.value}</td>
                </tr>
                {*
                                        <tr class="readonly">
                                            <td>&nbsp;</td>
                                            <td>{$fields.notify.before|replace:'<label':'<label class="multiline"'}{$fields.notify.input}{$fields.notify.caption}{$fields.notify.after}</td>
                                        </tr>
                *}
                <tr class="readonly">
                    <td><acronym
                                title="{$readonly_fields.login.hint|escape:'html'}">{$readonly_fields.login.caption}</acronym>:
                    </td>
                    <td>{$readonly_fields.login.value}</td>
                </tr>
            {/if}
            {if $page_action eq 'edit'}
                <tr>
                    <td>{$fields.old_password.caption}</td>
                    <td>{$fields.old_password.input}</td>
                </tr>
            {/if}
            <tr>
                <td>{$fields.password.caption}</td>
                <td>{$fields.password.input}</td>
            </tr>
            <tr>
                <td>{$fields.password.caption_conf}</td>
                <td>{$fields.password.input_conf}</td>
            </tr>
            {if $is_admin}
                <tr>
                    <td class="top">{$fields.srvnotes.caption}</td>
                    <td>{$fields.srvnotes.input}</td>
                </tr>
            {/if}
            <tr>
                <td>&nbsp;</td>
                <td><input class="button submit" type="submit" name="sent" value="{$submit_title|escape:'html'}"/></td>
            </tr>
        </table>
        {$form_counts}
    </form>
{/pane}
{include file="footer.tpl" bottom_panes=true}
