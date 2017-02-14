<?php /* Smarty version 2.6.30, created on 2017-01-26 18:08:16
         compiled from login.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'login.tpl', 2, false),array('function', 'html_image', 'login.tpl', 3, false),array('function', 'sys_alerts', 'login.tpl', 6, false),array('block', 'pane', 'login.tpl', 5, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl", 'smarty_include_vars' => array('top_panes' => false)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
			<form name="auth" action="<?php echo ((is_array($_tmp=$this->_tpl_vars['form_action'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" method="post">
			<div id="logo"><?php echo smarty_function_html_image(array('file' => 'img/logo.png','alt' => $this->_tpl_vars['logo_alt']), $this);?>
</div>
			<div id="content" class="login"><div class="shadow-right">
<?php $this->_tag_stack[] = array('pane', array('title' => $this->_tpl_vars['pane_title'],'tab' => 3)); $_block_repeat=true;$this->_plugins['block']['pane'][0][0]->pane($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<?php echo $this->_plugins['function']['sys_alerts'][0][0]->sys_alerts(array('tab' => 5), $this);?>

					<table class="params">
						<tr>
							<td width="35%"><label for="llogin"><?php echo $this->_tpl_vars['login_title']; ?>
</label></td>
							<td width="65%"><input class="text" type="text" id="llogin" name="login" size="32" maxlength="50" tabindex="1" /></td>
						</tr>
						<tr>
							<td><label for="lpassword"><?php echo $this->_tpl_vars['password_title']; ?>
</label></td>
							<td><input class="password" type="password" id="lpassword" name="password" size="32" maxlength="50" tabindex="2" /></td>
						</tr>
					</table>
					<div class="ptools">
						<span><input class="button" type="submit" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['submit_title'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" tabindex="3" /></span>
					</div>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['pane'][0][0]->pane($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
			</div>
			<div class="shadow-bottom"><div class="shadow-rb">&nbsp;</div></div></div>
			</form>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.tpl", 'smarty_include_vars' => array('bottom_panes' => false)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>