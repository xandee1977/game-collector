<?php /* Smarty version 3.1.22-dev/7, created on 2015-05-08 16:38:36
         compiled from "./templates/game-list.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:630112558554d10bcec80e5_08272482%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b6c17ba5a2b0916edb680bcd2277256f6cc69cfd' => 
    array (
      0 => './templates/game-list.tpl',
      1 => 1431113911,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '630112558554d10bcec80e5_08272482',
  'tpl_function' => 
  array (
  ),
  'variables' => 
  array (
    'games' => 0,
    'game' => 0,
    'currentItem' => 0,
    'lastItem' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.22-dev/7',
  'unifunc' => 'content_554d10bceea424_11600708',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_554d10bceea424_11600708')) {
function content_554d10bceea424_11600708 ($_smarty_tpl) {
?>
<?php
$_smarty_tpl->properties['nocache_hash'] = '630112558554d10bcec80e5_08272482';
?>
[
<?php $_smarty_tpl->tpl_vars["currentItem"] = new Smarty_Variable(0, null, 0);?>
<?php $_smarty_tpl->tpl_vars["lastItem"] = new Smarty_Variable(count($_smarty_tpl->tpl_vars['games']->value), null, 0);?>
<?php  $_smarty_tpl->tpl_vars['game'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['game']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['games']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['game']->key => $_smarty_tpl->tpl_vars['game']->value) {
$_smarty_tpl->tpl_vars['game']->_loop = true;
?>
    {
        "game_id":<?php echo json_encode($_smarty_tpl->tpl_vars['game']->value["game_id"]);?>
,
        "game_title":<?php echo json_encode($_smarty_tpl->tpl_vars['game']->value["game_title"]);?>
,
        "game_desc":<?php echo json_encode($_smarty_tpl->tpl_vars['game']->value["game_desc"]);?>
,
        "game_developer":<?php echo json_encode($_smarty_tpl->tpl_vars['game']->value["game_developer"]);?>
,
        "game_type_id":<?php echo json_encode($_smarty_tpl->tpl_vars['game']->value["game_type_id"]);?>
,
        "system_id":<?php echo json_encode($_smarty_tpl->tpl_vars['game']->value["system_id"]);?>

    }
    <?php $_smarty_tpl->tpl_vars["currentItem"] = new Smarty_Variable($_smarty_tpl->tpl_vars['currentItem']->value+1, null, 0);?>    
    <?php if ($_smarty_tpl->tpl_vars['currentItem']->value<$_smarty_tpl->tpl_vars['lastItem']->value) {?>,<?php }?>
<?php } ?>
]<?php }
}
?>