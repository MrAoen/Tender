<?php /* Smarty version 2.6.30, created on 2017-01-18 21:23:45
         compiled from footer.tpl */ ?>
<?php if ($this->_tpl_vars['bottom_panes']): ?>
		</div>
		<div class="shadow-bottom"><div class="shadow-rb">&nbsp;</div></div></div>
	</div>
	<div id="copy"><?php echo $this->_tpl_vars['copyright']; ?>
</div>
<?php endif; ?>
</body>
<?php if ($this->_tpl_vars['vjs_globals'] || $this->_tpl_vars['vjs_bottom'] != '' || $this->_tpl_vars['vjs_files']): ?>
<script language="javascript" type="text/javascript">
	<!--
<?php if ($this->_tpl_vars['vjs_globals']): ?>
	globals = {
<?php $_from = $this->_tpl_vars['vjs_globals']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['js_globals'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['js_globals']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
        $this->_foreach['js_globals']['iteration']++;
?>
		'<?php echo $this->_tpl_vars['k']; ?>
' : <?php echo $this->_tpl_vars['v']; ?>
<?php if (! ($this->_foreach['js_globals']['iteration'] == $this->_foreach['js_globals']['total'])): ?>,<?php endif; ?>

<?php endforeach; endif; unset($_from); ?>
	};
<?php endif; ?>
<?php if ($this->_tpl_vars['vjs_bottom'] != ''): ?>
	<?php echo $this->_tpl_vars['vjs_bottom']; ?>

<?php endif; ?>
<?php if ($this->_tpl_vars['vjs_files']): ?>
	wload();
<?php endif; ?>
	//-->
</script>
<?php endif; ?>
</html>