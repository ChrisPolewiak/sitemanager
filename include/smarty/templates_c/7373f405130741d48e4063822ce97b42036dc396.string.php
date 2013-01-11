<?php /* Smarty version Smarty-3.0.8, created on 2012-10-21 15:09:39
         compiled from "string:" */ ?>
<?php /*%%SmartyHeaderCode:5421255085083f4130e8a71-55118195%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '7373f405130741d48e4063822ce97b42036dc396' => 
    array (
      0 => 'string:',
      1 => 0,
      2 => 'string',
    ),
  ),
  'nocache_hash' => '5421255085083f4130e8a71-55118195',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<table bgcolor="#000000" width="100%" cellspacing="0" cellpadding="0">
    <tbody>
        <tr>
            <td><a href="https://www.caimanspace.com"><img src="/img/logo.jpg" alt="" height="30" border="0/" /></a></td>
        </tr>
    </tbody>
</table>
<h3>Potwierdzenie rejestracji konta</h3>
<p><b>Witaj</b> <br />
<br />
Poniższa wiadomość stanowi potwierdzenie rejestracji konta w serwisie <b><a href="https://www.caimanspace.com">CaimanSpace.com</a></b>.</p>
<p>Podane przez Ciebie dane:</p>
<table>
    <tbody>
        <tr>
            <td><b>Identyfikator konta</b></td>
            <td><?php echo $_smarty_tpl->getVariable('template')->value['redfrog_user__username'];?>
</td>
        </tr>
        <tr>
            <td><b>Nazwa witryny</b></td>
            <td><?php echo $_smarty_tpl->getVariable('template')->value['redfrog_site__companyname'];?>
</td>
        </tr>
        <tr>
            <td><b>Adres witryny</b></td>
            <td>http://<?php echo $_smarty_tpl->getVariable('template')->value['redfrog_siteurl__name'];?>
</td>
        </tr>
    </tbody>
</table>
<p>&nbsp;</p>
<p>Pozdrawiamy,<br />
Zespoł CaimanSpace.com</p>