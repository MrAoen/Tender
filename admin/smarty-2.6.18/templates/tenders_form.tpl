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
                <td>{$fields.number.caption}</td>
                <td>{$fields.number.input}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td class="last">
                    {$fields.iscomplete.before|replace:'<label':'<label class="multiline"'}{$fields.iscomplete.input}{$fields.iscomplete.caption}{$fields.iscomplete.after}
                </td>
            </tr>
            <tr>
                <td>{$fields.startts.caption}</td>
                <td class="calendar-date-row">{$fields.startts.input_d}{$fields.startts.input_m}{$fields.startts.input_y}{$fields.startts.button}{$fields.startts.calendar}{$fields.startts.input_h}{$fields.startts.input_i}</td>
            </tr>
            <tr>
                <td>{$fields.length.caption}</td>
                <td>{$fields.length.input} мин</td>
            </tr>
            <tr>
                <td>{$fields.loadingcityid.caption}</td>
                <td>
                    {$fields.loadingcityid.before}
                    {options_ext options=$fields.loadingcityid.options selected=$fields.loadingcityid.selected tab=9}
                    {$fields.loadingcityid.after}
                </td>
            </tr>
            <tr>
                <td>{$fields.loadingaddress.caption}</td>
                <td>{$fields.loadingaddress.input}</td>
            </tr>
            <tr>
                <td>{$fields.loadingts.caption}</td>
                <td class="calendar-date-row">{$fields.loadingts.input_d}{$fields.loadingts.input_m}{$fields.loadingts.input_y}{$fields.loadingts.button}{$fields.loadingts.calendar}{$fields.loadingts.input_h}{$fields.loadingts.input_i}</td>
            </tr>
            <tr>
                <td colspan="2"><h2>Точки доставки</h2></td>
            </tr>
            {foreach from=$fields.course_keys item=k}
                <tr id="{$k.coursecityid}">
                    <td>{$fields[$k.coursecityid].caption}</td>
                    <td>
                        {$fields[$k.coursecityid].before}
                        {options_ext options=$fields[$k.coursecityid].options selected=$fields[$k.coursecityid].selected tab=9}
                        {$fields[$k.coursecityid].after}
                    </td>
                </tr>
                <tr id="{$k.courseaddress}">
                    <td>{$fields[$k.courseaddress].caption}</td>
                    <td>{$fields[$k.courseaddress].input}</td>
                </tr>
                <tr id="{$k.coursets}" class="hr-bottom">
                    <td>{$fields[$k.coursets].caption}</td>
                    <td class="calendar-date-row">{$fields[$k.coursets].input_d}{$fields[$k.coursets].input_m}{$fields[$k.coursets].input_y}{$fields[$k.coursets].button}{$fields[$k.coursets].calendar}{$fields[$k.coursets].input_h}{$fields[$k.coursets].input_i}</td>
                </tr>
            {/foreach}
            <tr id="add_course">
                <td class="ptools" colspan="4"><a href="#" onclick="return addCourse()">добавить точку доставки</a></td>
            </tr>
            <tr class="spacer-top">
                <td>{$fields.volume.caption}</td>
                <td>{$fields.volume.input} т</td>
            </tr>
            <tr>
                <td>{$fields.pricestart.caption}</td>
                <td>{$fields.pricestart.input} Тенге</td>
            </tr>
            <tr>
                <td>{$fields.pricewin.caption}</td>
                <td>{$fields.pricewin.input} Тенге</td>
            </tr>
            {foreach from=$fields.body_keys item=k}
                <tr>
                    <td>{$fields.$k.caption}</td>
                    <td>{$fields.$k.input}</td>
                </tr>
            {/foreach}
            <tr>
                <td>{$fields.lastuserid.caption}</td>
                <td>
                    {$fields.lastuserid.before}
                    {options_ext options=$fields.lastuserid.options selected=$fields.lastuserid.selected tab=9}
                    {$fields.lastuserid.after}
                </td>
            </tr>
            {if $page_action ne 'add'}
                {foreach from=$readonly_fields key=k item=v}
                    <tr class="readonly{if $v.multiline} spacer-top{/if}">
                        <td class="top readonly-wide"><acronym title="{$v.hint|escape:'html'}">{$v.caption}</acronym>:
                        </td>
                        <td>{$v.value}</td>
                    </tr>
                {/foreach}
                {if $proplist.items}
                    <tr class="readonly spacer-top proplist">
                        <td class="top readonly-wide"><acronym
                                    title="{$proplist.hint|escape:'html'}">{$proplist.caption}</acronym>:
                        </td>
                        <td>
                            <table>
                                <tr>
                                    <th>Перевозчик
                                    </td>
                                    <th>Время
                                    </td>
                                    <th>Цена
                                    </td>
                                </tr>
                                {foreach from=$proplist.items key=k item=v}
                                    <tr>
                                        <td>{$v.company}</td>
                                        <td>{$v.date}</td>
                                        <td>{$v.price} Тенге</td>
                                    </tr>
                                {/foreach}
                            </table>
                        </td>
                    </tr>
                {/if}
            {/if}
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
