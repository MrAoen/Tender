<?php
$smarty =& $caller->tpl->smarty;
$login = (isset($_POST['login']) ? substr($_POST['login'], 0, 255) : '');
$password = (isset($_POST['password']) ? substr($_POST['password'], 0, 255) : '');
if ($login != '' || $password != '') {
    $t = time();
    usleep(rand(1200000, 2000000));
    if (time() - $t < 1) {
        sleep(rand(1, 2));
    }
    if ($caller->auth->login($login, sha1($password), false)) {
        $r = $caller->session_get_var('login_redirect');
        if (empty($r) || preg_match('/(?:admin|index)\/$/', $r)) {
            $caller->redirect($caller->auth->admin['typeid'] == 30 ? 'consultations/' : 'index/intro/');
        } else {
            $caller->session_set_var('login_redirect', '');
            $caller->redirect($r, 0);
        }
    } else {
        $caller->tpl->add_sys_alert('error', 301);
    }
}
$smarty->assign('form_action', $caller->uri('login/'));
$smarty->assign('logo_alt', $caller->io->output(302));
$smarty->assign('pane_title', $caller->io->output(303));
$smarty->assign('login_title', $caller->io->output(304));
$smarty->assign('password_title', $caller->io->output(305));
$smarty->assign('submit_title', $caller->io->output(306));
$caller->tpl->add_js_bottom('document.forms.auth.login.focus();');
$smarty->display('login.tpl');
?>