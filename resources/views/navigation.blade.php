
<div class="header  is_stuck">
    <nav class="navbar top-navbar navbar-expand-md ">
        <div class="navbar-header">
            <router-link :to="{name:'dashboard'}" class="navbar-brand">
                <span class="align-middle text-white f-s-20 text-capitalize f-w-500">WHO Nepal</span>
            </router-link>
        </div>

        <div class="navbar-collapse ">
            <ul class="navbar-nav mr-auto mt-md-0">
                <li class="nav-item">
                    <a class="nav-link nav-toggler hidden-md-up text-muted" href="javascript:void(0)"><i class="bx bx-menu"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link sidebartoggler hidden-sm-down text-muted" href="javascript:void(0)"><i class="bx bx-menu"></i></a>
                </li>
            </ul>
            <ul class="navbar-nav my-lg-0 d-flex align-items-center align-center">
                <li class="nav-item">
                    <a class="nav-link  pt-0 pb-0" flow="down" tooltip="Logout"  href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bx bx-log-out"></i>
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
                <!-- Notification -->
                <li class="nav-item dropdown">
                    <a class="nav-link pt-0 pb-0 dropdown-toggle text-muted text-muted"  href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
                <li class="nav-item d-flex align-items-center text-center">
                    <router-link class="nav-link pt-0 pb-0 text-muted " flow="down" tooltip="Profile"  :to="{name:'profile'}" >
                        <i class="bx bxs-user-circle"></i>
                    </router-link>
                    <div class="profile-content">
                        <h5>{{auth()->user()->name}}</h5>
                        <h6>{{auth()->user()->roles[0]->name}}</h6>
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
                    <router-link :to="{name:'dashboard'}" aria-expanded="false">
                        <i class="bx bx-home-circle"></i>
                        <span class="hide-menu">Dashboard </span>
                    </router-link>
                </li>
                @role('super-admin')

                <li>
                    <a href="#" class="has-arrow" aria-expanded="false">
                        <i class="fa-solid fa-screwdriver-wrench"></i>
                       <span class="hide-menu">
                            Settings
                        </span>
                    </a>
                    <ul aria-expanded="false" class="collapse">
                        <li class="nav-item">
                            <router-link :to="{name:'groups'}" class="nav-link ">
                                <i class="fa-solid fa-people-roof"></i>  Groups
                            </router-link>
                        </li>
                        <div class="dropdown-divider"></div>

                        <li class="nav-item">
                            <router-link :to="{name:'duty-stations'}" class="nav-link ">
                                <i class="fa-solid fa-house-medical-flag"></i> Duty Stations
                            </router-link>
                        </li>
                        <div class="dropdown-divider"></div>
                        <li class="nav-item">
                            <router-link :to="{name:'designations'}" class="nav-link ">
                                <i class="fa-solid fa-crown"></i> Designations
                            </router-link>
                        </li>
                        <div class="dropdown-divider"></div>
                        <li class="nav-item">
                            <router-link :to="{name:'leave-types'}" class="nav-link ">
                                <i class="fa-solid fa-person-walking-arrow-right"></i> Leave Types
                            </router-link>
                        </li>
                        <div class="dropdown-divider"></div>
                        <li class="nav-item">
                            <router-link :to="{name:'contract-types'}" class="nav-link ">
                                <i class="fa-solid fa-file-contract"></i> Contract Types
                            </router-link>
                        </li>
                        <div class="dropdown-divider"></div>

                        <li class="nav-item">
                            <router-link :to="{name:'contract-leaves'}" class="nav-link ">
                                <i class="fa-solid fa-scale-balanced"></i> Map Contract To Leave
                            </router-link>
                        </li>
                        <div class="dropdown-divider"></div>

                        <li class="nav-item">
                            <router-link :to="{name:'public-holidays'}" class="nav-link ">
                                <i class="fa-solid fa-bell"></i> Public Holidays
                            </router-link>
                        </li>


                    </ul>
                </li>

                @endrole
                @role('admin|super-admin|super-dev|supervisor')
                <li>
                    <a href="#" class="has-arrow" aria-expanded="false">
                        <i class="fa fa-cogs orange nav-icon"></i>
                        <span class="hide-menu">
                            Development Mode
                        </span>
                    </a>
                    <ul aria-expanded="false" class="collapse">
                        <li class="nav-item">
                            <router-link :to="{name:'roles'}" class="nav-link ">
                                <i class="nav-icon green fa fa-cog"></i>  Roles
                            </router-link>
                        </li>
                        <div class="dropdown-divider"></div>
                        <li class="nav-item">
                            <router-link :to="{name:'permissions'}" class="nav-link ">

                                <i class="nav-icon  fa fa-key"></i> Permissions
                            </router-link>
                        </li>
                    </ul>
                </li>
                <li >
                    <router-link :to="{name:'users'}" class="nav-link">
                        <i class="nav-icon  fa fa-users"></i>
                        <span class="hide-menu">
                            Users
                        </span>
                    </router-link>
                </li>

                @endrole



            </ul>
        </nav><!-- /.sidebar-nav -->
    </div><!-- /.scroll-sidebar -->
</div><!-- left-sidebar  -->
