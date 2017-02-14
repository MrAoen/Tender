{foreach from=$img_fields item=img key=img_i}
    <tr id="img_{$img_i}_lf"{if $img_i ne 0} class="hr-top"{/if}>
        <td>{$img.number}.</td>
        <td>{$img.img_l.input_caption}</td>
        <td>
            {$img.img_l.before}
            {$img.img_l.max_file_size}
            {$img.img_l.input_file}
            {$img.img_l.clear}
            {$img.img_l.input_fictive}
            {$img.img_l.browse}
            {$img.img_l.after}
        </td>
    </tr>
    <tr id="img_{$img_i}_ls">
        <td colspan="2">&nbsp;</td>
        <td class="file-field{if not $img.make_main_img and $img.img_l.existing_file eq '' and $img.img_l.temp_file eq ''} last{/if}">{$img.stamp_img_l.before|replace:'<label':'<label class="multiline"'}{$img.stamp_img_l.input}{$img.stamp_img_l.caption}{$img.stamp_img_l.after}</td>
    </tr>
    {if $img.make_main_img}
        <tr id="img_{$img_i}_mm">
            <td colspan="2">&nbsp;</td>
            <td class="file-field{if $img.img_l.existing_file eq '' and $img.img_l.temp_file eq ''} last{/if}">{$img.make_main_img.before|replace:'<label':'<label class="multiline"'}{$img.make_main_img.input}{$img.make_main_img.caption}{$img.make_main_img.after}</td>
        </tr>
    {/if}
    {if $img.img_l.existing_file ne ''}
        <tr id="img_{$img_i}_le">
            <td colspan="2">&nbsp;</td>
            <td class="existing-file{if $img.img_l.temp_file eq ''} last{/if}">{$img.img_l.remove_existing}{$img.img_l.existing_file}</td>
        </tr>
    {/if}
    {if $img.img_l.temp_file ne ''}
        <tr id="img_{$img_i}_lt">
            <td colspan="2">&nbsp;</td>
            <td class="temp-file">{$img.img_l.remove_temp}{$img.img_l.temp_file}</td>
        </tr>
    {/if}
    <tr id="img_{$img_i}_sf">
        <td>&nbsp;</td>
        <td>{$img.img_s.input_caption}</td>
        <td>
            {$img.img_s.before}
            {$img.img_s.max_file_size}
            {$img.img_s.input_file}
            {$img.img_s.clear}
            {$img.img_s.input_fictive}
            {$img.img_s.browse}
            {$img.img_s.after}
        </td>
    </tr>
    <tr id="img_{$img_i}_ss">
        <td colspan="2">&nbsp;</td>
        <td class="file-field{if $img.img_s.existing_file eq '' and $img.img_s.temp_file eq ''} last{/if}">{$img.stamp_img_s.before|replace:'<label':'<label class="multiline"'}{$img.stamp_img_s.input}{$img.stamp_img_s.caption}{$img.stamp_img_s.after}</td>
    </tr>
    {if $img.img_s.existing_file ne ''}
        <tr id="img_{$img_i}_se">
            <td colspan="2">&nbsp;</td>
            <td class="existing-file{if $img.img_s.temp_file eq ''} last{/if}">{$img.img_s.remove_existing}{$img.img_s.existing_file}</td>
        </tr>
    {/if}
    {if $img.img_s.temp_file ne ''}
        <tr id="img_{$img_i}_st">
            <td colspan="2">&nbsp;</td>
            <td class="temp-file last">{$img.img_s.remove_temp}{$img.img_s.temp_file}</td>
        </tr>
    {/if}
    {foreach from=$img_title_keys item=k name=img_ttl}
        <tr id="img_{$img_i}_{$k}"{if $smarty.foreach.img_ttl.last} class="hr-bottom"{/if}>
            <td>&nbsp;</td>
            <td>{$img.$k.caption}</td>
            <td>{$img.$k.input}</td>
        </tr>
    {/foreach}
{/foreach}
<tr id="add_img">
    <td class="ptools" colspan="3"><a href="#" onclick="return addImg()">{$add_img_link_title}</a></td>
</tr>
