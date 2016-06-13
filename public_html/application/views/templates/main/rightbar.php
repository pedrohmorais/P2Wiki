<?
$nome = $this->session->userdata('mw_nome') ? $this->session->userdata('mw_nome') : 'Usuário';
?>
<style type="text/css">
.navbar-inverse {
background-color: #036DAF !important;
border-bottom:2px solid #F3B00D  !important;
}
</style>
<!-- Navbar -->
<div class="navbar navbar-inverse" role="navigation">
  <div class="navbar-header">
	<a class="navbar-brand" href="<?=site_url();?>">Meu Web</a>
	<a class="sidebar-toggle" title="Recolher menu"><i class="icon-paragraph-justify2"></i></a>
	<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-icons"><span class="sr-only">Toggle navbar</span><i class="icon-grid3"></i></button>
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar"><span class="sr-only">Toggle navigation</span><i class="icon-paragraph-justify2"></i></button>
  </div>
  <ul class="nav navbar-nav navbar-right collapse" id="navbar-icons">
   
	<li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown"><i class="icon-people"></i><span class="label label-default">2</span></a>
      <div class="popup dropdown-menu dropdown-menu-right">
        <div class="popup-header"><a href="#" class="pull-left"><i class="icon-spinner7"></i></a><span>Activity</span><a href="#" class="pull-right"><i class="icon-paragraph-justify"></i></a></div>
        <ul class="activity">
          <li> <i class="icon-cart-checkout text-success"></i>
            <div> <a href="#">Thiago</a> ordered 2 copies of <a href="#">OEM license</a> <span>14 minutes ago</span> </div>
          </li>
          <li> <i class="icon-heart text-danger"></i>
            <div> Your <a href="#">latest post</a> was liked by <a href="#">Audrey Mall</a> <span>35 minutes ago</span> </div>
          </li>
          <li> <i class="icon-checkmark3 text-success"></i>
            <div> Mail server was updated. See <a href="#">changelog</a> <span>About 2 hours ago</span> </div>
          </li>
          <li> <i class="icon-paragraph-justify2 text-warning"></i>
            <div> There are <a href="#">6 new tasks</a> waiting for you. Don't forget! <span>About 3 hours ago</span> </div>
          </li>
        </ul>
      </div>
    </li>
	 
    <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown"><i class="icon-paragraph-justify2"></i><span class="label label-default">6</span></a>
      <div class="popup dropdown-menu dropdown-menu-right">
        <div class="popup-header"><a href="#" class="pull-left"><i class="icon-spinner7"></i></a><span>Messages</span><a href="#" class="pull-right"><i class="icon-new-tab"></i></a></div>
        <ul class="popup-messages">
          <li class="unread"><a href="#"><img src="<?=site_url();?>application/third_party/images/demo/users/face1.png" alt="" class="user-face"><strong>Thaigo Albanez <i class="icon-attachment2"></i></strong><span>Aliquam interdum convallis massa...</span></a></li>
          <li><a href="#"><img src="<?=site_url();?>application/third_party/images/demo/users/face2.png" alt="" class="user-face"><strong>Jason Goldsmith <i class="icon-attachment2"></i></strong><span>Aliquam interdum convallis massa...</span></a></li>
          <li><a href="#"><img src="<?=site_url();?>application/third_party/images/demo/users/face3.png" alt="" class="user-face"><strong>Angel Novator</strong><span>Aliquam interdum convallis massa...</span></a></li>
          <li><a href="#"><img src="<?=site_url();?>application/third_party/images/demo/users/face4.png" alt="" class="user-face"><strong>Monica Bloomberg</strong><span>Aliquam interdum convallis massa...</span></a></li>
          <li><a href="#"><img src="<?=site_url();?>application/third_party/images/demo/users/face5.png" alt="" class="user-face"><strong>Patrick Winsleur</strong><span>Aliquam interdum convallis massa...</span></a></li>
        </ul>
      </div>
    </li>
	
    <li class="dropdown"><a data-toggle="dropdown" class="dropdown-toggle"><i class="icon-grid"></i></a>
      <div class="popup dropdown-menu dropdown-menu-right">
        <div class="popup-header"><a href="#" class="pull-left"><i class="icon-spinner7"></i></a><span>Tasks list</span><a href="#" class="pull-right"><i class="icon-new-tab"></i></a></div>
        <table class="table table-hover">
          <thead>
            <tr>
              <th>Description</th>
              <th>Category</th>
              <th class="text-center">Priority</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><span class="status status-success item-before"></span> <a href="#">Frontpage fixes</a></td>
              <td><span class="text-smaller text-semibold">Bugs</span></td>
              <td class="text-center"><span class="label label-success">87%</span></td>
            </tr>
            <tr>
              <td><span class="status status-danger item-before"></span> <a href="#">CSS compilation</a></td>
              <td><span class="text-smaller text-semibold">Bugs</span></td>
              <td class="text-center"><span class="label label-danger">18%</span></td>
            </tr>
            <tr>
              <td><span class="status status-info item-before"></span> <a href="#">Responsive layout changes</a></td>
              <td><span class="text-smaller text-semibold">Layout</span></td>
              <td class="text-center"><span class="label label-info">52%</span></td>
            </tr>
            <tr>
              <td><span class="status status-success item-before"></span> <a href="#">Add categories filter</a></td>
              <td><span class="text-smaller text-semibold">Content</span></td>
              <td class="text-center"><span class="label label-success">100%</span></td>
            </tr>
            <tr>
              <td><span class="status status-success item-before"></span> <a href="#">Media grid padding issue</a></td>
              <td><span class="text-smaller text-semibold">Bugs</span></td>
              <td class="text-center"><span class="label label-success">100%</span></td>
            </tr>
          </tbody>
        </table>
      </div>
    </li>
    <li class="user dropdown"><a class="dropdown-toggle" data-toggle="dropdown"><span><?=$nome;?> </span> <i class="caret"></i></a>
      <ul class="dropdown-menu dropdown-menu-right icons-right">
        <?php
		if($this->Login->loggedUser()->nivel_acesso>3)
		{
		?>
			<li><a href="<?=site_url()?>user"><i class="icon-users"></i> Usuários</a></li>
			<li><a href="<?=site_url()?>configuration"><i class="icon-cog"></i> Configurações</a></li>
		<?php
		}
		?>
			<li><a href="<?=site_url()?>user/edituser/<?=$this->session->userdata('mw_user_id')?>"><i class="icon-user"></i>Meu perfil</a></li>
			<li><a href="<?=site_url()?>session/logout"><i class="icon-exit"></i> Sair</a></li>
      </ul>
    </li>
  </ul>
</div>