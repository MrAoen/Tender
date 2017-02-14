{include file="header.tpl" top_panes=false}
<form name="auth" action="{$form_action|escape:'html'}" method="post">
    <div id="logo">{html_image file='img/logo.png' alt=$logo_alt}</div>
    <div id="content" class="login">
        <div class="shadow-right">
            {pane title=$pane_title tab=3}
            {sys_alerts tab=5}
                <table class="params">
                    <tr>
                        <td width="35%"><label for="llogin">{$login_title}</label></td>
                        <td width="65%"><input class="text" type="text" id="llogin" name="login" size="32"
                                               maxlength="50" tabindex="1"/></td>
                    </tr>
                    <tr>
                        <td><label for="lpassword">{$password_title}</label></td>
                        <td><input class="password" type="password" id="lpassword" name="password" size="32"
                                   maxlength="50" tabindex="2"/></td>
                    </tr>
                </table>
                <div class="ptools">
                    <span><input class="button" type="submit" value="{$submit_title|escape:'html'}"
                                 tabindex="3"/></span>
                </div>
            {/pane}
        </div>
        <div class="shadow-bottom">
            <div class="shadow-rb">&nbsp;</div>
        </div>
    </div>
</form>
{include file="footer.tpl" bottom_panes=false}
