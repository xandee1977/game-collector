<?php /* Smarty version 3.1.22-dev/7, created on 2015-05-08 16:37:38
         compiled from "./templates/result.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:951910646554d1082aab028_67467762%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e1b6f5b36438a6b83af20d55bab2da44acb5a16c' => 
    array (
      0 => './templates/result.tpl',
      1 => 1431111705,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '951910646554d1082aab028_67467762',
  'tpl_function' => 
  array (
  ),
  'variables' => 
  array (
    'status' => 0,
    'message' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.22-dev/7',
  'unifunc' => 'content_554d1082ab6478_27197728',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_554d1082ab6478_27197728')) {
function content_554d1082ab6478_27197728 ($_smarty_tpl) {
?>
<?php
$_smarty_tpl->properties['nocache_hash'] = '951910646554d1082aab028_67467762';
?>
{"status":"<?php echo $_smarty_tpl->tpl_vars['status']->value;?>
", "message":"<?php echo $_smarty_tpl->tpl_vars['message']->value;?>
", "data":<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['dataPartial']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0);
?>
}<?php }
}
?>