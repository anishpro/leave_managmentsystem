
<div class="header is_stuck">
    <nav class="navbar top-navbar navbar-expand-md navbar-light"> 
        <div class="navbar-header"> 
            <a class="navbar-brand" href="index.php">  
                <!-- Logo icon -->
                <!-- <b><img src="#" alt="homepage" class="dark-logo" style="height: 22px; vertical-align: middle;"></b> -->
                <span class="align-middle text-white f-s-20 text-capitalize f-w-500">LeaveManagement</span> 
            </a>
        </div>
                        
        <div class="navbar-collapse">  
            <ul class="navbar-nav mr-auto mt-md-0">
                <li class="nav-item">
                    <a class="nav-link nav-toggler hidden-md-up text-muted" href="javascript:void(0)"><i class="bx bx-menu"></i></a>
                </li>

                <li class="nav-item">
                    <a class="nav-link sidebartoggler hidden-sm-down text-muted" href="javascript:void(0)"><i class="bx bx-menu"></i></a>
                </li>           
            </ul>

            <ul class="navbar-nav my-lg-0">
                <!-- Notification -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-muted text-muted" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> 
                        <span class="bx bxs-bell"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right mailbox">
                        <ul>
                            <li>
                                <div class="drop-title">Notifications</div>
                            </li>
                            <li>
                                <div class="message-center"> 
                                    
                                    
                                </div>
                            </li> 
                            <li>
                                <a class="nav-link text-center" href="javascript:void(0);"> 
                                    <h5 class="f-s-13 text-capitalize text-dark">Check all notifications <i class="fa fa-angle-right"></i></h5>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li> 

                <!-- Profile -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-muted" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="bx bxs-user-circle"></span> {{ucwords(session()->get('USERNAME'))}}
                    </a> 

                    <div class="dropdown-menu dropdown-menu-right"> 
                        <ul class="dropdown-user">
                            <li><a href="profile.php"><i class="bx bx-user f-s-16 align-middle"></i> Profile</a></li>
                            <li><a href="change_password.php"><i class="bx bx-lock f-s-16 align-middle"></i> Change Password</a></li>
                            <li role="separator" class="divider"></li>    
                            <li>
                                <a  href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="bx bx-log-out-circle f-s-16 align-middle"></i> {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</div><!-- /.header -->  
    
<div class="left-sidebar">
    <div class="scroll-sidebar">
        <nav class="sidebar-nav"> 
            <ul id="sidebarnav">
                <li class="nav-devider"></li>	  
                <li class="nav-label">
                    <a href="{{url('dashboard')}}" aria-expanded="false"> <!-- bx bx- -->
                        <i class="bx bx-home-circle"></i>
                        <span class="hide-menu">Dashboard </span> 
                    </a>
                </li> 
                @role('admin|super-admin|super-dev')
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="fas fa-hard-hat orange nav-icon"></i>
                        <p>
                            Development Mode
                            <i class="right fa fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview pl-2">
                        <li class="nav-item">
                            <router-link :to="{name:'roles'}" class="nav-link">
                                <i class="nav-icon green fas fa-users-cog"></i>
                                <p>Roles</p>
                            </router-link>
                        </li>
                        <div class="dropdown-divider"></div>
                        <li class="nav-item">
                            <router-link :to="{name:'permissions'}" class="nav-link">
                                <i class="nav-icon gray fas fa-key"></i>
                                <p>Permissions</p>
                            </router-link>
                        </li>
                        <div class="dropdown-divider"></div>
                    </ul>
                </li> 
                @endrole

                <?php
                    $sideMenu = showMenu();
                    //print_r($sideMenu);
                    $menuItem = '';

                    foreach ($sideMenu as $key => $value) {
                        //print_r($value['sub_menu']);
                        if ($value['sub_menu']) {
                            foreach ($value['sub_menu'] as $menu) {
                                
                                if(count($menu['sub_menu']) > 0){

                                    $menuItem .= '<li> 
                                                <a class="has-arrow" href="' . ($menu['menu_url'] ? url($menu['menu_url']) : 'javascript:void(0)') . '" aria-expanded="false"> 
                                                    <i class="'.$value['icon_class'].'"></i> 
                                                    <span class="hide-menu">'.$menu['menu_name'].'</span>
                                                </a>';
                                    $menuItem .= '<ul aria-expanded="false" class="collapse">';

                                        foreach ($menu['sub_menu'] as $subMenu) {
                                                                
                                            $menuItem .= '<li>
                                                        <a href="' . ($subMenu['menu_url'] ? url($subMenu['menu_url']) : 'javascript:void(0)') . '">'.$subMenu['menu_name'].'</a>
                                                    </li>';
                                            
                                            //print_r($subMenu['menu_name']);
                                        }

                                    $menuItem .= '</ul>';
                                    $menuItem .= '</li>'; 
                                }else{
                                    $menuItem .= '<li>
                                                    <a href="' . ($menu['menu_url'] ? url($menu['menu_url']) : 'javascript:void(0)') . '" aria-expanded="false">
                                                        <i class="bx bx-home-circle"></i>
                                                        <span class="hide-menu">'.$menu['menu_name'].'</span>
                                                    </a>
                                                </li>';
                                }
                                
                            }
                        }
                    }

                    echo $menuItem;

                ?>
                <?php 
                    if(session('ROLE') === 1){
                        echo'<li>
                                <a href="'.url('access_control').'" aria-expanded="false"> <!-- bx bx- -->
                                    <i class="bx bx-home-circle"></i>
                                    <span class="hide-menu">Access Control </span> 
                                </a>
                            </li>';
                    }
                ?>
                
            </ul>
        </nav><!-- /.sidebar-nav -->
    </div><!-- /.scroll-sidebar -->
</div><!-- left-sidebar  --> 