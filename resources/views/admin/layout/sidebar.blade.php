

<div class="theme-setting-wrapper">
          <div id="settings-trigger"><i class="typcn typcn-cog-outline"></i></div>
          <div id="theme-settings" class="settings-panel">
            <i class="settings-close typcn typcn-delete-outline"></i>
            <p class="settings-heading">SIDEBAR SKINS</p>
            <div class="sidebar-bg-options" id="sidebar-light-theme">
              <div class="img-ss rounded-circle bg-light border mr-3"></div>
              Light
            </div>
            <div class="sidebar-bg-options selected" id="sidebar-dark-theme">
              <div class="img-ss rounded-circle bg-dark border mr-3"></div>
              Dark
            </div>
            <p class="settings-heading mt-2">HEADER SKINS</p>
            <div class="color-tiles mx-0 px-4">
              <div class="tiles success"></div>
              <div class="tiles warning"></div>
              <div class="tiles danger"></div>
              <div class="tiles primary"></div>
              <div class="tiles info"></div>
              <div class="tiles dark"></div>
              <div class="tiles default border"></div>
            </div>
          </div>
        </div>
        <!-- partial -->
        <!-- partial:partials/_sidebar.html -->
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item">
            <div class="d-flex sidebar-profile">
              <div class="sidebar-profile-image">
              <i class="typcn typcn-user-outline mr-0"></i>
                <span class="sidebar-status-indicator"></span>
              </div>
              <div class="sidebar-profile-name">
                <p class="sidebar-name">
                {{Auth::guard('admin')->user()->name}}
                </p>
                <p class="sidebar-designation">
                  Welcome
                </p>
              </div>
            </div>
            
           
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{route('dashboard')}}">
              <i class="typcn typcn-device-desktop menu-icon"></i>
              <span class="menu-title">Dashboard <span class="badge badge-primary ml-3"> </span></span>
            </a>
          </li>
          @foreach($modules as $module)
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#ui-basic{{$module->id}}" aria-expanded="false" aria-controls="ui-basic">
              <i class="typcn typcn-briefcase menu-icon"></i>
              <span class="menu-title">{{ $module->name }}</span>
              <i class="typcn typcn-chevron-right menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-basic{{$module->id}}">
              <ul class="nav flex-column sub-menu">
              @foreach($sidebarPermissions->filter(function ($permission) use ($module) {
                return $permission->module_id == $module->id;
                        }) as $permission)
                       
                          
                <li class="nav-item"> 
                @if(Route::has($permission->name))
                <a class="nav-link" href="{{ route($permission->name) }}"> {{  $permission->name }} 
              </a>
              @endif
                      </li>
           
                               

                @endforeach
              </ul>
            </div>
          </li>
          @endforeach
        </ul>
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
              <i class="typcn typcn-briefcase menu-icon"></i>
              <span class="menu-title">Modules</span>
              <i class="typcn typcn-chevron-right menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-basic">
              <ul class="nav flex-column sub-menu">
              <li class="nav-item"> <a class="nav-link" href="{{route('supplier_create')}}">Create Supplier</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{route('supplier_view')}}">View Supplier</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{route('module_create')}}">Create Module</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{route('module_view')}}">View Module</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{route('role_create')}}">Create Role</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{route('role_view')}}">View Role</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{route('permission_create')}}">Create Permission</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{route('permission_view')}}">View Permissions</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{route('user_create')}}">Create Employee</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{route('user_view')}}">View User</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{route('product_create')}}">Create Product</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{route('sales_create')}}">Sales</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{route('sales_view')}}">View Sales</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{route('product_view')}}">View Products</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{route('sales_index')}}">Filter Sales Report</a></li>
              </ul>
            </div>
          </li>
      </nav>
        <!-- partial -->