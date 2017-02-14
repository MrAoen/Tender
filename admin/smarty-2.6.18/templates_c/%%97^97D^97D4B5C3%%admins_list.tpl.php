<?php /* Smarty version 2.6.30, created on 2017-01-26 18:20:54
         compiled from admins_list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'pane', 'admins_list.tpl', 2, false),array('function', 'tree', 'admins_list.tpl', 3, false),array('modifier', 'escape', 'admins_list.tpl', 5, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl", 'smarty_include_vars' => array('top_panes' => true)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $this->_tag_stack[] = array('pane', array('title' => $this->_tpl_vars['pane_title'],'params' => '"class":"list"','tab' => 3)); $_block_repeat=true;$this->_plugins['block']['pane'][0][0]->pane($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<?php echo $this->_plugins['function']['tree'][0][0]->tree(array('cols' => '"40%", "30%", "30%", "1"','headers' => $this->_tpl_vars['list_headers'],'values' => $this->_tpl_vars['list_values'],'tab' => 5), $this);?>

					<div class="ptools">
						<a href="<?php echo ((is_array($_tmp=$this->_tpl_vars['pane_tools']['add']['href'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
"><?php echo $this->_tpl_vars['pane_tools']['add']['content']; ?>
</a>
					</div>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['pane'][0][0]->pane($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.tpl", 'smarty_include_vars' => array('bottom_panes' => true)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>