@php
$currentAction = \Route::currentRouteAction();
$list=list($controller, $method) = explode('@', $currentAction);
$controller = preg_replace('/.*\\\/', '', $controller);
@endphp
<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <div class="row dropdown profile-element" style="margin-right: 0px; padding: 15px;">
                    <a href="">
                        <img src="{{ URL::asset('assets/admin/img/logo.png') }}" />
                    </a>
                </div>
                <div class="logo-element">
                    <img src="{{ URL::asset('assets/admin/img/logo.png') }}" />
                </div>
            </li>
            <li class="{{$controller=='DashboardController' ? 'active' : ''}}">
                <a href="{{ url('admin/dashboard') }}"><i class="fa fa-th-large"></i> <span class="nav-label">Dashboard</span></a>
            </li>

            <?php
            if(Session::get('admin_details'))
            {
                $menus = get_user_menu_new(Session::get('admin_details')['id']);
                
                
                if($menus)
                {
                    foreach($menus as $menu)
                    {
                        
                        if(!isset($menu['sub_menus']))
                        {     
                           
                            ?>
                            <li class="{{$controller==$menu['controller_name'] ? 'active' : ''}}">
                                <a href="{{ url('admin/'.$menu['url']) }}"><i class="<?php echo (!empty($menu['icon'])? $menu['icon']:'');?>"></i> <span class="nav-label">{{ $menu['name'] }}</span></a>
                            </li>
                            <?php
                        }
                        else
                        {
                            $active_controller="";
                            foreach($menu['sub_menus'] as $submenu_check)
                            {
                            
                                if($controller==$submenu_check['controller_name'])
                                {
                                    $active_controller="active";
                                }
                            }
                            ?>
                           
                            <li class="{{ $active_controller}}">
                                <a href=""><i class="<?php echo (!empty($menu['icon'])? $menu['icon']:'');?>"></i> <span class="nav-label">{{ $menu['name'] }}</span><span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level collapse">
                                    <?php
                                        foreach($menu['sub_menus'] as $submenu)
                                        {
                                            ?>
                                            <li class="{{$controller==$submenu['controller_name'] ? 'active' : ''}}">
                                                @if(isset($submenu['url']) && $submenu['url']=='#') 
                                                    <a href="javascript:void(0);">{{ $submenu['name'] }}</a>
                                                @else
                                                    <a href="{{ url('admin/'.$submenu['url']) }}">{{ $submenu['name'] }}</a>
                                                @endif 

                                                @if(isset($submenu['nes_sub_menus'])) 
                                                <ul class="nav nav-second-level collapse">
                                                <?php
                                                    foreach($submenu['nes_sub_menus'] as $nessubmenu)
                                                    {
                                                        ?>
                                                        <li class="{{$controller==$nessubmenu['controller_name'] ? 'active' : ''}}"><a href="{{ url('admin/'.$nessubmenu['url']) }}">{{ $nessubmenu['name'] }}</a></li>
                                                        <?php 
                                                    }
                                                ?>   
                                                </ul>
                                                @endif
                                            </li>
                                            <?php
                                        }
                                    ?>
                                </ul>
                            </li>
                            <?php 
                        }
                    }
                }
            }
            ?>

            
        </ul>
    </div>
</nav>