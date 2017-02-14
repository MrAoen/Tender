<?php /* Smarty version 2.6.30, created on 2017-01-18 21:39:18
         compiled from header.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'send_headers', 'header.tpl', 1, false),array('function', 'menu_right', 'header.tpl', 51, false),array('function', 'menu_left', 'header.tpl', 52, false),array('function', 'sys_alerts', 'header.tpl', 59, false),array('block', 'pane', 'header.tpl', 58, false),)), $this); ?>
<?php echo $this->_plugins['function']['send_headers'][0][0]->send_headers(array(), $this);?>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=<?php echo $this->_tpl_vars['all']->io->lang_charset; ?>
" />
	<title><?php echo $this->_tpl_vars['title']; ?>
</title>
	<meta name="robots" content="DISALLOW" />
	<meta http-equiv="expires" content="Thu, 01 Jan 1970 02:00:00 GMT" />
	<meta http-equiv="cache-control" content="no-cache" />
	<meta http-equiv="pragma" content="no-cache" />
	<base href="<?php echo $this->_tpl_vars['all']->admin_abs_home; ?>
" />
	<link rel="stylesheet" type="text/css" href="css/all.css" />
<?php if ($this->_tpl_vars['css']): ?>
	<style type="text/css">
<?php $_from = $this->_tpl_vars['css']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['n'] => $this->_tpl_vars['p']):
?>
		<?php echo $this->_tpl_vars['n']; ?>
 {
<?php $_from = $this->_tpl_vars['p']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>
			<?php echo $this->_tpl_vars['k']; ?>
: <?php echo $this->_tpl_vars['v']; ?>
;
<?php endforeach; endif; unset($_from); ?>
		}
<?php endforeach; endif; unset($_from); ?>
	</style>
<?php endif; ?>
<?php $_from = $this->_tpl_vars['vjs_files']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
	<script language="javascript" type="text/javascript" src="<?php echo $this->_tpl_vars['v']; ?>
"></script>
<?php endforeach; endif; unset($_from); ?>
<?php if ($this->_tpl_vars['vjs_top'] != ''): ?>
	<script language="javascript" type="text/javascript">
		<!--
		<?php echo $this->_tpl_vars['vjs_top']; ?>

		//-->
	</script>
<?php endif; ?>
</head>
<body>
<?php if ($this->_tpl_vars['top_panes']): ?>
	<div id="page">
		<div id="top">
			<iframe id="topinfobg" frameborder="0"></iframe>
			<div id="topinfopane" onmouseover="topX()" onmouseout="topH()">
<?php $_from = $this->_tpl_vars['admin_info']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
				<p><?php echo $this->_tpl_vars['v']; ?>
</p>
<?php endforeach; endif; unset($_from); ?>
			</div>
			<div id="menu-top" class="ctools">
				<span id="localtime"><?php  echo time();  ?></span>
				<a href="#" onclick="return false" onmouseover="topC(1)" onmouseout="topH()"><?php echo $this->_tpl_vars['admin_name']; ?>
</a>
				<a id="logout" href="<?php echo $this->_tpl_vars['logout']['href']; ?>
"><u><?php echo $this->_tpl_vars['logout']['title']; ?>
</u></a>
			</div>
			<p id="status"><?php echo $this->_tpl_vars['all']->tpl->caption; ?>
</p>
		</div>
		<div id="menu">
			<div id="menu-right"><?php echo $this->_plugins['function']['menu_right'][0][0]->menu_right(array('tab' => 4), $this);?>

			</div><?php echo $this->_plugins['function']['menu_left'][0][0]->menu_left(array('tab' => 3), $this);?>

		</div>
		<div id="submenu"><?php echo $this->_tpl_vars['submenu']; ?>

		</div>
		<div id="content"><div class="shadow-right">
<?php if ($this->_tpl_vars['all']->tpl->vsys_alerts): ?>
<?php $this->_tag_stack[] = array('pane', array('params' => '"id":"alert"','tab' => 3)); $_block_repeat=true;$this->_plugins['block']['pane'][0][0]->pane($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<?php echo $this->_plugins['function']['sys_alerts'][0][0]->sys_alerts(array('tab' => 5), $this);?>

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['pane'][0][0]->pane($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
<?php endif; ?>
<?php endif; ?>