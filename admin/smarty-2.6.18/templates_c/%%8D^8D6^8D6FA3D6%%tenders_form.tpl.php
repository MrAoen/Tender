<?php /* Smarty version 2.6.30, created on 2017-02-03 19:30:16
         compiled from tenders_form.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'pane', 'tenders_form.tpl', 2, false),array('modifier', 'escape', 'tenders_form.tpl', 6, false),array('modifier', 'replace', 'tenders_form.tpl', 15, false),array('function', 'options_ext', 'tenders_form.tpl', 30, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl", 'smarty_include_vars' => array('top_panes' => true)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $this->_tag_stack[] = array('pane', array('title' => $this->_tpl_vars['pane_title'],'tab' => 3)); $_block_repeat=true;$this->_plugins['block']['pane'][0][0]->pane($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<?php if ($this->_tpl_vars['last_change_info']): ?>
					<p class="first-half"><?php echo $this->_tpl_vars['last_change_info']; ?>
</p>
<?php endif; ?>
					<form name="mform" action="<?php echo ((is_array($_tmp=$this->_tpl_vars['form_action'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" method="post" onsubmit="return VF('mform', 1<?php if ($this->_tpl_vars['js_validation'] != ''): ?>, '<?php echo ((is_array($_tmp=$this->_tpl_vars['js_validation'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'quotes') : smarty_modifier_escape($_tmp, 'quotes')); ?>
'<?php endif; ?>)" enctype="multipart/form-data">
					<table class="params">
						<tr>
							<td><?php echo $this->_tpl_vars['fields']['number']['caption']; ?>
</td>
							<td><?php echo $this->_tpl_vars['fields']['number']['input']; ?>
</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td class="last">
								<?php echo ((is_array($_tmp=$this->_tpl_vars['fields']['iscomplete']['before'])) ? $this->_run_mod_handler('replace', true, $_tmp, '<label', '<label class="multiline"') : smarty_modifier_replace($_tmp, '<label', '<label class="multiline"')); ?>
<?php echo $this->_tpl_vars['fields']['iscomplete']['input']; ?>
<?php echo $this->_tpl_vars['fields']['iscomplete']['caption']; ?>
<?php echo $this->_tpl_vars['fields']['iscomplete']['after']; ?>

							</td>
						</tr>
						<tr>
							<td><?php echo $this->_tpl_vars['fields']['startts']['caption']; ?>
</td>
							<td class="calendar-date-row"><?php echo $this->_tpl_vars['fields']['startts']['input_d']; ?>
<?php echo $this->_tpl_vars['fields']['startts']['input_m']; ?>
<?php echo $this->_tpl_vars['fields']['startts']['input_y']; ?>
<?php echo $this->_tpl_vars['fields']['startts']['button']; ?>
<?php echo $this->_tpl_vars['fields']['startts']['calendar']; ?>
<?php echo $this->_tpl_vars['fields']['startts']['input_h']; ?>
<?php echo $this->_tpl_vars['fields']['startts']['input_i']; ?>
</td>
						</tr>
						<tr>
							<td><?php echo $this->_tpl_vars['fields']['length']['caption']; ?>
</td>
							<td><?php echo $this->_tpl_vars['fields']['length']['input']; ?>
 мин</td>
						</tr>
						<tr>
							<td><?php echo $this->_tpl_vars['fields']['loadingcityid']['caption']; ?>
</td>
							<td>
								<?php echo $this->_tpl_vars['fields']['loadingcityid']['before']; ?>

									<?php echo $this->_plugins['function']['options_ext'][0][0]->options_ext(array('options' => $this->_tpl_vars['fields']['loadingcityid']['options'],'selected' => $this->_tpl_vars['fields']['loadingcityid']['selected'],'tab' => 9), $this);?>

								<?php echo $this->_tpl_vars['fields']['loadingcityid']['after']; ?>

							</td>
						</tr>
						<tr>
							<td><?php echo $this->_tpl_vars['fields']['loadingaddress']['caption']; ?>
</td>
							<td><?php echo $this->_tpl_vars['fields']['loadingaddress']['input']; ?>
</td>
						</tr>
						<tr>
							<td><?php echo $this->_tpl_vars['fields']['loadingts']['caption']; ?>
</td>
							<td class="calendar-date-row"><?php echo $this->_tpl_vars['fields']['loadingts']['input_d']; ?>
<?php echo $this->_tpl_vars['fields']['loadingts']['input_m']; ?>
<?php echo $this->_tpl_vars['fields']['loadingts']['input_y']; ?>
<?php echo $this->_tpl_vars['fields']['loadingts']['button']; ?>
<?php echo $this->_tpl_vars['fields']['loadingts']['calendar']; ?>
<?php echo $this->_tpl_vars['fields']['loadingts']['input_h']; ?>
<?php echo $this->_tpl_vars['fields']['loadingts']['input_i']; ?>
</td>
						</tr>
						<tr>
							<td colspan="2"><h2>Точки доставки</h2></td>
						</tr>
<?php $_from = $this->_tpl_vars['fields']['course_keys']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k']):
?>
						<tr id="<?php echo $this->_tpl_vars['k']['coursecityid']; ?>
">
							<td><?php echo $this->_tpl_vars['fields'][$this->_tpl_vars['k']['coursecityid']]['caption']; ?>
</td>
							<td>
								<?php echo $this->_tpl_vars['fields'][$this->_tpl_vars['k']['coursecityid']]['before']; ?>

									<?php echo $this->_plugins['function']['options_ext'][0][0]->options_ext(array('options' => $this->_tpl_vars['fields'][$this->_tpl_vars['k']['coursecityid']]['options'],'selected' => $this->_tpl_vars['fields'][$this->_tpl_vars['k']['coursecityid']]['selected'],'tab' => 9), $this);?>

								<?php echo $this->_tpl_vars['fields'][$this->_tpl_vars['k']['coursecityid']]['after']; ?>

							</td>
						</tr>
						<tr id="<?php echo $this->_tpl_vars['k']['courseaddress']; ?>
">
							<td><?php echo $this->_tpl_vars['fields'][$this->_tpl_vars['k']['courseaddress']]['caption']; ?>
</td>
							<td><?php echo $this->_tpl_vars['fields'][$this->_tpl_vars['k']['courseaddress']]['input']; ?>
</td>
						</tr>
						<tr id="<?php echo $this->_tpl_vars['k']['coursets']; ?>
" class="hr-bottom">
							<td><?php echo $this->_tpl_vars['fields'][$this->_tpl_vars['k']['coursets']]['caption']; ?>
</td>
							<td class="calendar-date-row"><?php echo $this->_tpl_vars['fields'][$this->_tpl_vars['k']['coursets']]['input_d']; ?>
<?php echo $this->_tpl_vars['fields'][$this->_tpl_vars['k']['coursets']]['input_m']; ?>
<?php echo $this->_tpl_vars['fields'][$this->_tpl_vars['k']['coursets']]['input_y']; ?>
<?php echo $this->_tpl_vars['fields'][$this->_tpl_vars['k']['coursets']]['button']; ?>
<?php echo $this->_tpl_vars['fields'][$this->_tpl_vars['k']['coursets']]['calendar']; ?>
<?php echo $this->_tpl_vars['fields'][$this->_tpl_vars['k']['coursets']]['input_h']; ?>
<?php echo $this->_tpl_vars['fields'][$this->_tpl_vars['k']['coursets']]['input_i']; ?>
</td>
						</tr>
<?php endforeach; endif; unset($_from); ?>
						<tr id="add_course">
							<td class="ptools" colspan="4"><a href="#" onclick="return addCourse()">добавить точку доставки</a></td>
						</tr>
						<tr class="spacer-top">
							<td><?php echo $this->_tpl_vars['fields']['volume']['caption']; ?>
</td>
							<td><?php echo $this->_tpl_vars['fields']['volume']['input']; ?>
 т</td>
						</tr>
						<tr>
							<td><?php echo $this->_tpl_vars['fields']['pricestart']['caption']; ?>
</td>
							<td><?php echo $this->_tpl_vars['fields']['pricestart']['input']; ?>
 Тенге</td>
						</tr>
						<tr>
							<td><?php echo $this->_tpl_vars['fields']['pricewin']['caption']; ?>
</td>
							<td><?php echo $this->_tpl_vars['fields']['pricewin']['input']; ?>
 Тенге</td>
						</tr>
<?php $_from = $this->_tpl_vars['fields']['body_keys']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k']):
?>
						<tr>
							<td><?php echo $this->_tpl_vars['fields'][$this->_tpl_vars['k']]['caption']; ?>
</td>
							<td><?php echo $this->_tpl_vars['fields'][$this->_tpl_vars['k']]['input']; ?>
</td>
						</tr>
<?php endforeach; endif; unset($_from); ?>
						<tr>
							<td><?php echo $this->_tpl_vars['fields']['lastuserid']['caption']; ?>
</td>
							<td>
								<?php echo $this->_tpl_vars['fields']['lastuserid']['before']; ?>

									<?php echo $this->_plugins['function']['options_ext'][0][0]->options_ext(array('options' => $this->_tpl_vars['fields']['lastuserid']['options'],'selected' => $this->_tpl_vars['fields']['lastuserid']['selected'],'tab' => 9), $this);?>

								<?php echo $this->_tpl_vars['fields']['lastuserid']['after']; ?>

							</td>
						</tr>
<?php if ($this->_tpl_vars['page_action'] != 'add'): ?>
<?php $_from = $this->_tpl_vars['readonly_fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>
						<tr class="readonly<?php if ($this->_tpl_vars['v']['multiline']): ?> spacer-top<?php endif; ?>">
							<td class="top readonly-wide"><acronym title="<?php echo ((is_array($_tmp=$this->_tpl_vars['v']['hint'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
"><?php echo $this->_tpl_vars['v']['caption']; ?>
</acronym>:</td>
							<td><?php echo $this->_tpl_vars['v']['value']; ?>
</td>
						</tr>
<?php endforeach; endif; unset($_from); ?>
<?php if ($this->_tpl_vars['proplist']['items']): ?>
						<tr class="readonly spacer-top proplist">
							<td class="top readonly-wide"><acronym title="<?php echo ((is_array($_tmp=$this->_tpl_vars['proplist']['hint'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
"><?php echo $this->_tpl_vars['proplist']['caption']; ?>
</acronym>:</td>
							<td>
								<table>
									<tr>
										<th>Перевозчик</td>
										<th>Время</td>
										<th>Цена</td>
									</tr>
<?php $_from = $this->_tpl_vars['proplist']['items']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>
									<tr>
										<td><?php echo $this->_tpl_vars['v']['company']; ?>
</td>
										<td><?php echo $this->_tpl_vars['v']['date']; ?>
</td>
										<td><?php echo $this->_tpl_vars['v']['price']; ?>
 Тенге</td>
									</tr>
<?php endforeach; endif; unset($_from); ?>
								</table>
							</td>
						</tr>
<?php endif; ?>
<?php endif; ?>
						<tr class="spacer-top">
							<td class="top"><?php echo $this->_tpl_vars['fields']['srvnotes']['caption']; ?>
</td>
							<td><?php echo $this->_tpl_vars['fields']['srvnotes']['input']; ?>
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