<aside class="main-sidebar">
    <section class="sidebar">
      <div class="user-panel">
        <div class="pull-left image">
          <img src="{{ asset('public/dist/img/user2-160x160.jpg') }}" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>{{ Auth::user()->name }}</p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MAIN NAVIGATION</li>
        <li class="{{ Request::is('admin') ? 'active' : '' }}">
            <a href="{{ url('admin') }}">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
          </a>
        </li>

        {{-- <li class="treeview">
          <a href="#">
            <i class="fa fa-users"></i>
            <span>User Managment </span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{ url('users') }}"><i class="fa fa-circle-o"></i> Users</a></li>
            <li><a href="{{ url('roles') }}"><i class="fa fa-circle-o"></i> Role Permission</a></li>
          </ul>
        </li> --}}

        <li class="{{ Request::is('admin/keywords') ? 'active' : '' }}">
          <a href="{{ url('admin/keywords') }}">
            <i class="fa fa-cog"></i> <span>Keyword</span>
          </a>
        </li>

      </ul>
    </section>
  </aside>
