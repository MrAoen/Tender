{send_headers}
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset={$all->io->lang_charset}"/>
    <title>{$title}</title>
    <meta name="robots" content="DISALLOW"/>
    <meta http-equiv="expires" content="Thu, 01 Jan 1970 02:00:00 GMT"/>
    <meta http-equiv="cache-control" content="no-cache"/>
    <meta http-equiv="pragma" content="no-cache"/>
    <base href="{$all->admin_abs_home}"/>
    <link rel="stylesheet" type="text/css" href="css/all.css"/>
    {if $css}
        <style type="text/css">
            {foreach from=$css key=n item=p}
            {$n}
            {
            ldelim}
            {foreach from=$p key=k item=v}
            {$k}
            :
            {$v}
            ;
            {/foreach}
            {rdelim
            }
            {/foreach}
        </style>
    {/if}
    {foreach from=$vjs_files item=v}
        <script language="javascript" type="text/javascript" src="{$v}"></script>
    {/foreach}
    {if $vjs_top ne ''}
        <script language="javascript" type="text/javascript">
            <!--
            {$vjs_top}
            //-->
        </script>
    {/if}
</head>
<body>
{if $top_panes}
<div id="page">
    <div id="top">
        <iframe id="topinfobg" frameborder="0"></iframe>
        <div id="topinfopane" onmouseover="topX()" onmouseout="topH()">
            {foreach from=$admin_info item=v}
                <p>{$v}</p>
            {/foreach}
        </div>
        <div id="menu-top" class="ctools">
            <span id="localtime">{php} echo time(); {/php}</span>
            <a href="#" onclick="return false" onmouseover="topC(1)" onmouseout="topH()">{$admin_name}</a>
            <a id="logout" href="{$logout.href}"><u>{$logout.title}</u></a>
        </div>
        <p id="status">{$all->tpl->caption}</p>
    </div>
    <div id="menu">
        <div id="menu-right">{menu_right tab=4}
        </div>{menu_left tab=3}
    </div>
    <div id="submenu">{$submenu}
    </div>
    <div id="content">
        <div class="shadow-right">
            {if $all->tpl->vsys_alerts}
                {pane params='"id":"alert"' tab=3}
                {sys_alerts tab=5}
                {/pane}
            {/if}
            {/if}
