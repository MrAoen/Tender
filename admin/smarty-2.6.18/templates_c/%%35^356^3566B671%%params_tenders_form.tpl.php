<?php /* Smarty version 2.6.30, created on 2017-01-26 18:20:43
         compiled from params_tenders_form.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'pane', 'params_tenders_form.tpl', 2, false),array('modifier', 'escape', 'params_tenders_form.tpl', 6, false),array('modifier', 'replace', 'params_tenders_form.tpl', 10, false),array('function', 'options_ext', 'params_tenders_form.tpl', 72, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl", 'smarty_include_vars' => array('top_panes' => true)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $this->_tag_stack[] = array('pane', array('title' => $this->_tpl_vars['pane_title'],'tab' => 3)); $_block_repeat=true;$this->_plugins['block']['pane'][0][0]->pane($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<?php if ($this->_tpl_vars['last_change_info'] != ''): ?>
					<p class="first-half"><?php echo $this->_tpl_vars['last_change_info']; ?>
</p>
<?php endif; ?>
					<form name="mform" action="<?php echo ((is_array($_tmp=$this->_tpl_vars['form_action'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" method="post" onsubmit="return VF('mform', 1<?php if ($this->_tpl_vars['js_validation'] != ''): ?>, '<?php echo ((is_array($_tmp=$this->_tpl_vars['js_validation'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'quotes') : smarty_modifier_escape($_tmp, 'quotes')); ?>
'<?php endif; ?>)">
					<table class="params params-narrow">
						<tr>
							<td class="top spacer-right">Отображать поля в списке:</td>
							<td><?php echo ((is_array($_tmp=$this->_tpl_vars['fields']['number']['before'])) ? $this->_run_mod_handler('replace', true, $_tmp, '<label', '<label class="multiline"') : smarty_modifier_replace($_tmp, '<label', '<label class="multiline"')); ?>
<?php echo $this->_tpl_vars['fields']['number']['input']; ?>
<?php echo $this->_tpl_vars['fields']['number']['caption']; ?>
<?php echo $this->_tpl_vars['fields']['number']['after']; ?>
</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td><?php echo ((is_array($_tmp=$this->_tpl_vars['fields']['startts']['before'])) ? $this->_run_mod_handler('replace', true, $_tmp, '<label', '<label class="multiline"') : smarty_modifier_replace($_tmp, '<label', '<label class="multiline"')); ?>
<?php echo $this->_tpl_vars['fields']['startts']['input']; ?>
<?php echo $this->_tpl_vars['fields']['startts']['caption']; ?>
<?php echo $this->_tpl_vars['fields']['startts']['after']; ?>
</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td><?php echo ((is_array($_tmp=$this->_tpl_vars['fields']['length']['before'])) ? $this->_run_mod_handler('replace', true, $_tmp, '<label', '<label class="multiline"') : smarty_modifier_replace($_tmp, '<label', '<label class="multiline"')); ?>
<?php echo $this->_tpl_vars['fields']['length']['input']; ?>
<?php echo $this->_tpl_vars['fields']['length']['caption']; ?>
<?php echo $this->_tpl_vars['fields']['length']['after']; ?>
</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td><?php echo ((is_array($_tmp=$this->_tpl_vars['fields']['loadingcity']['before'])) ? $this->_run_mod_handler('replace', true, $_tmp, '<label', '<label class="multiline"') : smarty_modifier_replace($_tmp, '<label', '<label class="multiline"')); ?>
<?php echo $this->_tpl_vars['fields']['loadingcity']['input']; ?>
<?php echo $this->_tpl_vars['fields']['loadingcity']['caption']; ?>
<?php echo $this->_tpl_vars['fields']['loadingcity']['after']; ?>
</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td><?php echo ((is_array($_tmp=$this->_tpl_vars['fields']['loadingaddress']['before'])) ? $this->_run_mod_handler('replace', true, $_tmp, '<label', '<label class="multiline"') : smarty_modifier_replace($_tmp, '<label', '<label class="multiline"')); ?>
<?php echo $this->_tpl_vars['fields']['loadingaddress']['input']; ?>
<?php echo $this->_tpl_vars['fields']['loadingaddress']['caption']; ?>
<?php echo $this->_tpl_vars['fields']['loadingaddress']['after']; ?>
</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td><?php echo ((is_array($_tmp=$this->_tpl_vars['fields']['loadingts']['before'])) ? $this->_run_mod_handler('replace', true, $_tmp, '<label', '<label class="multiline"') : smarty_modifier_replace($_tmp, '<label', '<label class="multiline"')); ?>
<?php echo $this->_tpl_vars['fields']['loadingts']['input']; ?>
<?php echo $this->_tpl_vars['fields']['loadingts']['caption']; ?>
<?php echo $this->_tpl_vars['fields']['loadingts']['after']; ?>
</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td><?php echo ((is_array($_tmp=$this->_tpl_vars['fields']['course']['before'])) ? $this->_run_mod_handler('replace', true, $_tmp, '<label', '<label class="multiline"') : smarty_modifier_replace($_tmp, '<label', '<label class="multiline"')); ?>
<?php echo $this->_tpl_vars['fields']['course']['input']; ?>
<?php echo $this->_tpl_vars['fields']['course']['caption']; ?>
<?php echo $this->_tpl_vars['fields']['course']['after']; ?>
</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td><?php echo ((is_array($_tmp=$this->_tpl_vars['fields']['volume']['before'])) ? $this->_run_mod_handler('replace', true, $_tmp, '<label', '<label class="multiline"') : smarty_modifier_replace($_tmp, '<label', '<label class="multiline"')); ?>
<?php echo $this->_tpl_vars['fields']['volume']['input']; ?>
<?php echo $this->_tpl_vars['fields']['volume']['caption']; ?>
<?php echo $this->_tpl_vars['fields']['volume']['after']; ?>
</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td><?php echo ((is_array($_tmp=$this->_tpl_vars['fields']['body']['before'])) ? $this->_run_mod_handler('replace', true, $_tmp, '<label', '<label class="multiline"') : smarty_modifier_replace($_tmp, '<label', '<label class="multiline"')); ?>
<?php echo $this->_tpl_vars['fields']['body']['input']; ?>
<?php echo $this->_tpl_vars['fields']['body']['caption']; ?>
<?php echo $this->_tpl_vars['fields']['body']['after']; ?>
</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td><?php echo ((is_array($_tmp=$this->_tpl_vars['fields']['pricestart']['before'])) ? $this->_run_mod_handler('replace', true, $_tmp, '<label', '<label class="multiline"') : smarty_modifier_replace($_tmp, '<label', '<label class="multiline"')); ?>
<?php echo $this->_tpl_vars['fields']['pricestart']['input']; ?>
<?php echo $this->_tpl_vars['fields']['pricestart']['caption']; ?>
<?php echo $this->_tpl_vars['fields']['pricestart']['after']; ?>
</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td><?php echo ((is_array($_tmp=$this->_tpl_vars['fields']['pricewin']['before'])) ? $this->_run_mod_handler('replace', true, $_tmp, '<label', '<label class="multiline"') : smarty_modifier_replace($_tmp, '<label', '<label class="multiline"')); ?>
<?php echo $this->_tpl_vars['fields']['pricewin']['input']; ?>
<?php echo $this->_tpl_vars['fields']['pricewin']['caption']; ?>
<?php echo $this->_tpl_vars['fields']['pricewin']['after']; ?>
</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td><?php echo ((is_array($_tmp=$this->_tpl_vars['fields']['pricecurrent']['before'])) ? $this->_run_mod_handler('replace', true, $_tmp, '<label', '<label class="multiline"') : smarty_modifier_replace($_tmp, '<label', '<label class="multiline"')); ?>
<?php echo $this->_tpl_vars['fields']['pricecurrent']['input']; ?>
<?php echo $this->_tpl_vars['fields']['pricecurrent']['caption']; ?>
<?php echo $this->_tpl_vars['fields']['pricecurrent']['after']; ?>
</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td><?php echo ((is_array($_tmp=$this->_tpl_vars['fields']['lastuser']['before'])) ? $this->_run_mod_handler('replace', true, $_tmp, '<label', '<label class="multiline"') : smarty_modifier_replace($_tmp, '<label', '<label class="multiline"')); ?>
<?php echo $this->_tpl_vars['fields']['lastuser']['input']; ?>
<?php echo $this->_tpl_vars['fields']['lastuser']['caption']; ?>
<?php echo $this->_tpl_vars['fields']['lastuser']['after']; ?>
</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td><?php echo ((is_array($_tmp=$this->_tpl_vars['fields']['propositions']['before'])) ? $this->_run_mod_handler('replace', true, $_tmp, '<label', '<label class="multiline"') : smarty_modifier_replace($_tmp, '<label', '<label class="multiline"')); ?>
<?php echo $this->_tpl_vars['fields']['propositions']['input']; ?>
<?php echo $this->_tpl_vars['fields']['propositions']['caption']; ?>
<?php echo $this->_tpl_vars['fields']['propositions']['after']; ?>
</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td><?php echo ((is_array($_tmp=$this->_tpl_vars['fields']['iscomplete']['before'])) ? $this->_run_mod_handler('replace', true, $_tmp, '<label', '<label class="multiline"') : smarty_modifier_replace($_tmp, '<label', '<label class="multiline"')); ?>
<?php echo $this->_tpl_vars['fields']['iscomplete']['input']; ?>
<?php echo $this->_tpl_vars['fields']['iscomplete']['caption']; ?>
<?php echo $this->_tpl_vars['fields']['iscomplete']['after']; ?>
</td>
						</tr>
						<tr>
							<td class="readonly-wide"><?php echo $this->_tpl_vars['fields']['ones_per_page']['caption']; ?>
</td>
							<td>
								<?php echo $this->_tpl_vars['fields']['ones_per_page']['before']; ?>

									<?php echo $this->_plugins['function']['options_ext'][0][0]->options_ext(array('options' => $this->_tpl_vars['fields']['ones_per_page']['options'],'selected' => $this->_tpl_vars['fields']['ones_per_page']['selected'],'tab' => 9), $this);?>

								<?php echo $this->_tpl_vars['fields']['ones_per_page']['after']; ?>

							</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td><input class="button submit" type="submit" name="sent" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['submit_title'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" /></td>
						</tr>
					</table>
					<?php echo $this->_tpl_vars['form_counts']; ?>

					</form>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['pane'][0][0]->pane($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.tpl", 'smarty_include_vars' => array('bottom_panes' => true)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>