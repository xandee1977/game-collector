<?php /* Smarty version 3.1.22-dev/7, created on 2015-05-26 10:01:58
         compiled from "./templates/result.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:3474209955646ec6160f64_62852581%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '91c8dcbb6ef79ab4e28291d3ab4a2bddac3602ce' => 
    array (
      0 => './templates/result.tpl',
      1 => 1432640844,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '3474209955646ec6160f64_62852581',
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
  'unifunc' => 'content_55646ec6aa0130_05911503',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_55646ec6aa0130_05911503')) {
function content_55646ec6aa0130_05911503 ($_smarty_tpl) {
?>
<?php
$_smarty_tpl->properties['nocache_hash'] = '3474209955646ec6160f64_62852581';
?>
{"status":"<?php echo $_smarty_tpl->tpl_vars['status']->value;?>
", "message":"<?php echo $_smarty_tpl->tpl_vars['message']->value;?>
", "data":<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['dataPartial']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0);
?>
}<?php }
}
?>