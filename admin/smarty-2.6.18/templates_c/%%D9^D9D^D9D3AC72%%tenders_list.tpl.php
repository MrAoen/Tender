<?php /* Smarty version 2.6.30, created on 2017-01-18 21:24:13
         compiled from tenders_list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'pane', 'tenders_list.tpl', 2, false),array('function', 'tree', 'tenders_list.tpl', 3, false),array('function', 'pagination_pane', 'tenders_list.tpl', 4, false),array('modifier', 'escape', 'tenders_list.tpl', 6, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl", 'smarty_include_vars' => array('top_panes' => true)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $this->_tag_stack[] = array('pane', array('title' => $this->_tpl_vars['pane_title'],'params' => '"class":"list"','tab' => 3)); $_block_repeat=true;$this->_plugins['block']['pane'][0][0]->pane($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
					<div id="scroller"><?php echo $this->_plugins['function']['tree'][0][0]->tree(array('cols' => $this->_tpl_vars['list_cols_width'],'headers' => $this->_tpl_vars['list_headers'],'values' => $this->_tpl_vars['list_values'],'tab' => 6), $this);?>

					</div><?php echo $this->_plugins['function']['pagination_pane'][0][0]->pagination_pane(array('pagination' => $this->_tpl_vars['pagination'],'tab' => 5), $this);?>

					<div class="ptools<?php if ($this->_tpl_vars['pagination']['pages_count'] > 1): ?> second<?php endif; ?>">
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