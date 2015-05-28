<?php /* Smarty version 3.1.22-dev/7, created on 2015-05-26 10:32:42
         compiled from "./templates/user-flag-watch.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:227295909556475faa24262_76583870%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'aa580ff95f8487a86ad67dd7396717177408aa4f' => 
    array (
      0 => './templates/user-flag-watch.tpl',
      1 => 1432646597,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '227295909556475faa24262_76583870',
  'tpl_function' => 
  array (
  ),
  'variables' => 
  array (
    'flag_id' => 0,
    'game_id' => 0,
    'user_id' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.22-dev/7',
  'unifunc' => 'content_556475faa2e566_39495154',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_556475faa2e566_39495154')) {
function content_556475faa2e566_39495154 ($_smarty_tpl) {
?>
<?php
$_smarty_tpl->properties['nocache_hash'] = '227295909556475faa24262_76583870';
?>
{
    "flag_id":<?php echo json_encode($_smarty_tpl->tpl_vars['flag_id']->value);?>
,
    "game_id":<?php echo json_encode($_smarty_tpl->tpl_vars['game_id']->value);?>
,
    "user_id":<?php echo json_encode($_smarty_tpl->tpl_vars['user_id']->value);?>

}<?php }
}
?>