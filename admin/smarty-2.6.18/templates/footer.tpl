{if $bottom_panes}
    </div>
    <div class="shadow-bottom">
        <div class="shadow-rb">&nbsp;</div>
    </div>
    </div>
    </div>
    <div id="copy">{$copyright}</div>
{/if}
</body>
{if $vjs_globals or $vjs_bottom ne '' or $vjs_files}
    <script language="javascript" type="text/javascript">
        <!--
        {if $vjs_globals}
        globals = {ldelim}
            {foreach from=$vjs_globals key=k item=v name=js_globals}
            '{$k}': {$v}{if not $smarty.foreach.js_globals.last},{/if}

            {/foreach}
            {rdelim};
        {/if}
        {if $vjs_bottom ne ''}
        {$vjs_bottom}
        {/if}
        {if $vjs_files}
        wload();
        {/if}
        //-->
    </script>
{/if}
</html>