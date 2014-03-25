<?php if (Core::$devMode == true){ ?>
<div class="container-full">
<div class="devbar"><?php echo __("DEVWARNING"); ?></div>
</div>
<?php } ?>
<div class="container">
    <div class="navbar  navbar-inverse navbar-default">
            <div class="navbar-inner">
                    
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                  </button>
                  <a class="navbar-brand" href="/admin"><?php echo Core::$site['sitename']." <span class='version'>(".VERSION.")</span>"; ?></a>
                 <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

                    <ul class="nav navbar-nav">
                    <li <?php Menu::checkCurrent('dashboard'); ?>>
                        <a href="dashboard.php"><i class="fa fa-fw fa-home"></i> <?php echo __('DASHBOARD'); ?></a>
                    </li>
                    <li class="dropdown">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo __("CONTENT"); ?> <b class="caret"></b></a>
                          <ul class="dropdown-menu">
                            
                            
                            <?php if (User::hasPerms('pages')){ ?>
                            <li <?php Menu::checkCurrent('pages'); ?>>
                                <a href="pages.php?action=view"><i class="fa fa-fw fa-pencil"></i> <?php echo __('PAGES'); ?> </a>  
                            </li>
                            <?php } ?>
                            <?php if (User::hasPerms('blog')){ ?>
                            <li <?php Menu::checkCurrent('blog'); ?>>
                                <a href="blog.php?action=view"><i class="fa fa-fw fa-edit"></i> <?php echo __('BLOG'); ?></a>
                            </li>
                            <?php } ?>
                            <?php if (User::hasPerms('snippets')){ ?>
                            <li <?php Menu::checkCurrent('snippets'); ?>>
                                <a href="snippets.php?action=view"><i class="fa fa-fw fa-th"></i> <?php echo __('SNIPPETS'); ?></a>
                            </li>
                            <?php } ?>
                            <?php if (User::hasPerms('components')){ ?>
                            <li <?php Menu::checkCurrent('components'); ?>>
                                <a href="components.php?action=view"><i class="fa fa-fw fa-th-large"></i> <?php echo __('COMPONENTS'); ?></a>
                            </li>
                            <?php } ?>
                            <?php if (User::hasPerms('menu')){ ?>
                            <li <?php Menu::checkCurrent('menu'); ?>>
                                <a href="menu.php?action=view"><i class="fa fa-fw fa-align-justify"></i> <?php echo __('MENU'); ?></a>
                            </li>
                            <?php } ?>
                            <?php if (User::hasPerms('media')){ ?>
                            <li <?php Menu::checkCurrent('media'); ?>>
                                <a href="media.php?action=view"><i class="fa fa-fw fa-picture-o"></i> <?php echo __('MEDIA'); ?></a>
                            </li>
                            <?php } ?>
                            <?php if (User::hasPerms('files')){ ?>
                            <li <?php Menu::checkCurrent('files'); ?>>
                                <a href="files.php"><i class="fa fa-fw fa-folder-open"></i> <?php echo __('FILES'); ?></a>
                            </li>
                            <?php } ?>
                          </ul>
                        </li>

                        <?php 
                        if (Theme::hasOptions() or Theme::hasHooks()){
                        ?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo __("THEME"); ?> <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                             <?php 
                                if (Theme::hasOptions()){
                                ?>
                                <li <?php Menu::checkCurrent('theme'); ?>>
                                    <a href="theme.php?action=view"><i class="fa fa-fw fa-tasks"></i> <?php echo __('THEMEOPTIONS'); ?></a>
                                </li>
                            <?php } ?>
                            <?php 
                                if (Theme::hasHooks()){
                                ?>
                                <li <?php Menu::checkCurrent('themehooks'); ?>>
                                    <a href="themehooks.php?action=view"><i class="fa fa-fw fa-th"></i> <?php echo __('THEMEHOOKS'); ?></a>
                                </li>
                            <?php } ?>
                            </ul>
                        </li>
                        <?php
                        }
                        ?>


                        <li class="dropdown">
                          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo __("PLUGINS"); ?> <b class="caret"></b></a>
                              <ul class="dropdown-menu">
                                <?php if (User::hasPerms('plugins')){ ?>
                                <li <?php Menu::checkCurrent('plugins'); ?>>
                                    <a href="plugins.php"><i class="fa fa-tasks"></i> <?php echo __('PLUGINS'); ?></a>
                                </li>
                                <?php } ?>
                                
                                <?php Actions::executeAction('admin-add-sidebar');      ?>  
                            </ul>
                        </li>

                        <li class="dropdown">
                          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo __("SITE"); ?> <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                             <?php if (User::hasPerms('template')){ ?>
                                <li <?php Menu::checkCurrent('template'); ?>>
                                    <a href="template.php"><i class="fa fa-fw fa-tasks"></i> <?php echo __('THEME'); ?></a>
                                </li>
                                <?php  }  ?>
                                <?php if (User::hasPerms('users')){ ?>
                                <li <?php Menu::checkCurrent('users'); ?>>
                                        <a href="users.php?action=view"><i class="fa fa-fw fa-user"></i> <?php echo __('USERS'); ?></a>
                                </li>
                                <?php  }  ?>
                                
                                <?php if (User::hasPerms('settings')){ ?>
                                    <li <?php Menu::checkCurrent('settings'); ?>>
                                        <a href="settings.php?action=edit"><i class="fa fa-fw fa-cogs"></i> <?php echo __('SETTINGS'); ?></a>
                                    </li>
                                <?php  }  ?>
                                <?php if (User::hasPerms('routes')){ ?>
                                    <li <?php Menu::checkCurrent('routes'); ?>>
                                        <a href="routes.php?action=view"><i class="fa fa-fw fa-random"></i> <?php echo __('ROUTES'); ?></a>
                                    </li>
                                <?php  }  ?>
                                <?php if (User::hasPerms('customfields')){ ?>
                                    <li <?php Menu::checkCurrent('customfields'); ?>>
                                        <a href="customfields.php?action=view"><i class="fa fa-fw fa-ellipsis-v"></i> <?php echo __('CUSTOMFIELDS'); ?></a>
                                    </li>
                                <?php  }  ?>
                                <?php if (User::hasPerms('backups')){ ?>
                                <li <?php Menu::checkCurrent('backups'); ?>>
                                    <a href="backups.php"><i class="fa fa-fw fa-download"></i> <?php echo __('BACKUPS'); ?></a>
                                </li>
                                <?php  }  ?>
                                
                                <li <?php Menu::checkCurrent('about'); ?>>
                                    <a href="about.php"><i class="fa fa-fw fa-exclamation"></i> <?php echo __('ABOUT'); ?></a>
                                </li>  
                            </ul>
                        </li>

                      </ul>


                        <ul class="nav navbar-nav navbar-right">
                                <li>
                                <div class="btn-group btn-group-xs" style="top:15px;">
                                <?php 
                                if (Core::$site['debug']){
                                ?>
                                  <button type="button" class="btn btn-info"  data-placement="bottom" data-toggle="tooltip" title="Debug is On"><i class="fa fa-bug"></i></button>
                                <?php
                                } else {
                                ?>
                                <button type="button" class="btn btn-info" data-placement="bottom" data-toggle="tooltip" title="Debug is Off"><i class="fa fa-bug"></i></button>
                                <?php 
                                }  
                                if (Core::$site['maintenance']){
                                ?>

                                <button type="button" class="btn btn-info" data-placement="bottom" data-toggle="tooltip" title="Maintenace Mode On"><i class="fa fa-wrench"></i></button>
                                <?php } ?>
                                </div>
                                </li>
                                <li class="dropdown">
                                    <a class="dropdown-toggle" id="dLabel" role="button" data-toggle="dropdown" data-target="#" href="javascript:void();">
                                        Lang (<?php echo Lang::getCurrentLanguage(); ?>)
                                        <b class="caret"></b>
                                    </a>
                                    <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                                        <?php Lang::showLanguageDropdown(); ?>
                                    </ul>
                                </li>
                                
                            
                                <li class="dropdown">
                                
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo User::getGravatar(); ?> <?php echo User::getUsername(); ?> <b class="caret"></b></a>
                                  <ul class="dropdown-menu">
                                    <li>
                                        <a href="users.php?action=edit&id=<?php echo User::getUserID(); ?>"><?php echo __('WELCOME',array(":user"=>User::getUsername(),":role"=>User::getRole())); ?>&nbsp;<i class="icon-pencil"></i></a>
                                    </li>

                                    <li>
                                            <a href="<?php echo Core::$site['siteurl']; ?>" target="_blank" ><?php echo __('VIEWSITE'); ?></a>
                                    </li>
                                    <li class="last">
                                            <a href="index.php?auth=logout"><?php echo __('LOGOUT'); ?></a>
                                    </li>
                                    </ul>
                                    </li>
                        </ul>
                </div>
            </div>
    </div>
</div>
<div class="container mainbody">
<div class="row">