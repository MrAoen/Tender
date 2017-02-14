{include file="header.tpl" top_panes=true}
{pane title=$pane_title params='"class":"templates"' tab=3}
{if $last_change_info ne ''}
    <p class="first-half">{$last_change_info}</p>
{/if}
    <form name="mform" action="{$form_action|escape:'html'}" method="post"
          onsubmit="return VF('mform', 1{if $js_validation ne ''}, '{$js_validation|escape:'quotes'}'{/if})">
        {foreach from=$template_keys item=keys}
            <h3>{$fields[$keys.body].caption}</h3>
            <div>
                <p class="sender">{$fields[$keys.sender].caption} <span>{$fields[$keys.sender].input}</span></p>
                <p class="subj">{$fields[$keys.subj].caption} <span>{$fields[$keys.subj].input}</span></p>
                <p>{$fields[$keys.body].input}</p>
            </div>
            {if $replacements[$keys.body]}
                <p class="vars">
                    <acronym title="{$replacements.hint|escape:'html'}">{$replacements.caption}</acronym><br/>
                    {foreach from=$replacements[$keys.body] key=k item=v name=freplacements}
                        <code>{$k}</code>
                        &nbsp; &ndash; &nbsp; {$v}{if $smarty.foreach.freplacements.last}.{else};<br/>{/if}

                    {/foreach}
                </p>
            {/if}
        {/foreach}
        <p class="submit"><input class="button submit" type="submit" name="sent" value="{$submit_title|escape:'html'}"/>
        </p>
        {$form_counts}
    </form>
{/pane}
{include file="footer.tpl" bottom_panes=true}
