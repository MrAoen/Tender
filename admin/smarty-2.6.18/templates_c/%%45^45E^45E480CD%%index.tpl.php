<?php /* Smarty version 2.6.30, created on 2017-01-18 21:24:11
         compiled from index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'pane', 'index.tpl', 3, false),array('function', 'tree', 'index.tpl', 13, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl", 'smarty_include_vars' => array('top_panes' => true)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php if ($this->_tpl_vars['is_intro']): ?>
<?php $this->_tag_stack[] = array('pane', array('title' => $this->_tpl_vars['pane_title'],'tab' => 3)); $_block_repeat=true;$this->_plugins['block']['pane'][0][0]->pane($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
					<p><?php echo $this->_tpl_vars['auth_info']; ?>
</p>
<?php if ($this->_tpl_vars['lastin_info'] != ''): ?>
					<p><?php echo $this->_tpl_vars['lastin_info']; ?>
</p>
<?php endif; ?>
					<p><?php echo $this->_tpl_vars['logout_notify']; ?>
</p>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['pane'][0][0]->pane($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
<?php endif; ?>
<?php if ($this->_tpl_vars['is_stats']): ?>
<?php $this->_tag_stack[] = array('pane', array('title' => $this->_tpl_vars['stats_title'],'params' => '"class":"list index"','tab' => 3)); $_block_repeat=true;$this->_plugins['block']['pane'][0][0]->pane($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<?php echo $this->_plugins['function']['tree'][0][0]->tree(array('cols' => '"100%", "1", "1"','headers' => $this->_tpl_vars['list_headers'],'values' => $this->_tpl_vars['list_values'],'tab' => 5), $this);?>

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['pane'][0][0]->pane($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
<?php endif; ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.tpl", 'smarty_include_vars' => array('bottom_panes' => true)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>