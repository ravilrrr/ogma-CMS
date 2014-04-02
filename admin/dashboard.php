<?php 

 /**
 *	ogmaCMS Dashboard Admin Page
 *
 *	@package ogmaCMS
 *	@author Mike Swan / n00dles101
 *	@copyright 2013 Mike Swan / n00dles101
 *	@since 1.0.0
 *
 */


	include "template/head.inc.php";
	include "template/navbar.inc.php";

?>
		<div class="col-md-12">
			<legend><?php echo __('WELCOMEBACK',array(":user"=>User::getUsername())); ?></legend>
		
			<div class="col-md-6">
				<div class="panel panel-primary">
	        	<div class="panel-heading"><?php echo __("QUICKSHORTCUTS"); ?></div>
	        	<div class="panel-body">		
						<div class="shortcuts">
							<?php if (User::hasPerms('pages')){ ?>
							<a href="pages.php?action=view" class="shortcut btn-primary">
								<i class="shortcut-icon fa fa-fw fa-pencil"></i>
								<span class="shortcut-label"><?php echo __('PAGES'); ?></span>
							</a>
							<?php } ?>

							<?php if (User::hasPerms('blog')){ ?>
							<a href="blog.php?action=view" class="shortcut btn-primary">
								<i class="shortcut-icon fa fa-fw fa-edit"></i>
								<span class="shortcut-label"><?php echo __('BLOG'); ?></span>
							</a>
							<?php } ?>
							<?php if (User::hasPerms('snippets')){ ?>
							<a href="snippets.php?action=view" class="shortcut btn-primary">
								<i class="shortcut-icon fa fa-fw fa-th"></i>
								<span class="shortcut-label"><?php echo __('SNIPPETS'); ?></span>	
							</a>
							<?php } ?>
							<?php if (User::hasPerms('components')){ ?>
							<a href="components.php?action=view" class="shortcut btn-primary">
								<i class="shortcut-icon fa fa-fw fa-th-large"></i>
								<span class="shortcut-label"><?php echo __('COMPONENTS'); ?></span>								
							</a>
							<?php } ?>
							<?php if (User::hasPerms('menu')){ ?>
							<a href="menu.php?action=view" class="shortcut btn-primary">
								<i class="shortcut-icon fa fa-fw fa-align-justify"></i>
								<span class="shortcut-label"><?php echo __('MENU'); ?></span>								
							</a>
							<?php } ?>
							<?php if (User::hasPerms('media')){ ?>
							<a href="media.php?action=view" class="shortcut btn-primary">
								<i class="shortcut-icon fa fa-fw fa-picture-o"></i>
								<span class="shortcut-label"><?php echo __('MEDIA'); ?></span>								
							</a>
							<?php } ?>
							<?php if (User::hasPerms('files')){ ?>
							<a href="files.php" class="shortcut btn-primary">
								<i class="shortcut-icon fa fa-fw fa-folder-open"></i>
								<span class="shortcut-label"><?php echo __('FILES'); ?></span>
							</a>
							<?php } ?>
							<?php if (User::hasPerms('menu')){ ?>
							<a href="menu.php?action=view" class="shortcut btn-primary">
								<i class="shortcut-icon fa fa-fw fa-tasks"></i>
								<span class="shortcut-label"><?php echo __('TEMPLATES'); ?></span>								
							</a>
							<?php } ?>
							
                            <?php 
								if (Theme::hasOptions()){
                        	?>
                            <a href="theme.php?action=view" class="shortcut btn-primary">
								<i class="shortcut-icon fa fa-fw fa-tasks"></i>
								<span class="shortcut-label"><?php echo __('THEMEOPTIONS'); ?></span>
							</a>
                            <?php } ?>

                            <?php Actions::executeAction('admin-add-to-dashboard'); ?>						
                            
						</div> <!-- /shortcuts -->	
					</div>
				</div>
				



	      		
		</div>
		<div class="col-md-6">
			
			<div class="panel panel-primary">
	        	<div class="panel-heading"><?php echo __("QUICKLINKS"); ?></div>
	        	<div class="panel-body">
	        		<p>
	        			<?php if (User::hasPerms('pages')){ ?>
	        			<a href="pages.php?action=create"><?php echo __("CREATEPAGE"); ?></a><br/>
	                    <?php } ?>

	                    <?php if (User::hasPerms('pages')){ ?>
	                    <a href="blog.php?action=create"><?php echo __("CREATEBLOG"); ?></a><br/>
	                    <?php } ?>
	                    <?php if (User::hasPerms('pages')){ ?>
	                    <a href="snippets.php?action=create"><?php echo __("CREATESNIPPET"); ?></a><br/>
	                    <?php } ?>
	                    <?php if (User::hasPerms('pages')){ ?>
	                    <a href="components.php?action=create"><?php echo __("CREATECOMPONENT"); ?></a><br/>
	                    <?php } ?>
	                  </p>
	        	</div>
        	</div>

        	<div class="panel panel-primary">
	        	<div class="panel-heading"><?php echo __("RECENTUPDATES"); ?></div>
	        	<div class="panel-body">
					
					<table class="table">
						<thead>
							<tr>
								<th>Date</th>
								<th>Desc</th>
								<th>User</th>
							</tr>						
						</thead>
						
						<tbody>
						<?php 
							$logs = Debug::getUpdateLog(5);
							foreach ($logs as $key => $value) {
							
								echo '<tr>';
								echo '	<td class="who">'.Core::date($value['time'],true).'</td>';
								echo '	<td class="full"><a href="#">'.$value['desc'].'</a></td>';
								echo '	<td class="who">'.$value['user'].'</td>';
								echo '</tr>';
							}

						?>
						</tbody>
					</table>	

				</div>

			</div>

			<?php Actions::executeAction('admin-add-widget'); ?>
		</div>
		</div>
	</div>
</div>
<?php 
include "template/footer.inc.php"; 
?>