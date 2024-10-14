<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4" style="background-color: #1A1A2E;">
  <!-- Brand Logo -->
  <a href="index3.html" class="brand-link" style="color: #FFFFFF;">
    <img src="{{ url('assets/adminassets/dist/img/Syncologo.png') }}" alt="SyncoLogo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-bold">Synco</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="info">
        <a href="#" class="d-block" style="color: #EAEAEA;">Hi, {{ Auth::user()->name }}</a>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

      @if(Auth::user()->user_type == 1)
          <li class="nav-item">
            <a href="{{ url('admin/dashboard') }}" class="nav-link @if(Request::segment(2) =='dashboard') active @endif" style="color: #EAEAEA;">
              <i class="nav-icon fas fa-tachometer-alt" style="color: #FF6F61;"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('admin/admin/list') }}" class="nav-link @if(Request::segment(2) =='admin') active @endif" style="color: #EAEAEA;">
              <i class="nav-icon fas fa-user" style="color: #FF6F61;"></i>
              <p>Admin</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('admin/student/list') }}" class="nav-link @if(Request::segment(2) =='student') active @endif" style="color: #EAEAEA;">
              <i class="nav-icon fas fa-users" style="color: #FF6F61;"></i>
              <p>Users</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('admin/project/list') }}" class="nav-link @if(Request::segment(2) =='project') active @endif" style="color: #EAEAEA;">
              <i class="nav-icon fas fa-tasks" style="color: #FF6F61;"></i>
              <p>Project List</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{url ('student/profile')}}" class="nav-link @if(Request::segment(2) =='profile') active @endif" style="color: #EAEAEA;">
              <i class="nav-icon far fa-user" style="color: #FF6F61;"></i>
              <p>Profile</p>
            </a>
          </li>

        @elseif(Auth::user()->user_type == 3)
          <!-- Student Sidebar Links -->
          <li class="nav-item">
            <a href="{{ url('student/project/project/add') }}" class="nav-link @if(Request::segment(2) == 'project') active @endif" style="color: #EAEAEA;">
              <i class="nav-icon fas fa-plus" style="color: #FF6F61;"></i>
              Create a Project
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ url('student/dashboard') }}" class="nav-link @if(Request::segment(2) == 'dashboard') active @endif" style="color: #EAEAEA;">
              <i class="nav-icon fas fa-list" style="color: #FF6F61;"></i>
              My Projects
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ route('invitations.index') }}" class="nav-link @if(Request::segment(2) == 'invitations') active @endif" style="color: #EAEAEA;">
              <i class="nav-icon fas fa-envelope" style="color: #FF6F61;"></i>
              <p>
                Pending Invitations
                @php
                    $invitationCount = \App\Models\ProjectInvitation::where('email', Auth::user()->email)
                                        ->where('status', \App\Models\ProjectInvitation::STATUS_PENDING)
                                        ->count();
                @endphp
                @if($invitationCount > 0)
                  <span class="badge badge-warning">{{ $invitationCount }}</span>
                @endif
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ route('student.profile') }}" class="nav-link @if(Request::segment(2) == 'profile') active @endif" style="color: #EAEAEA;">
              <i class="nav-icon far fa-user" style="color: #FF6F61;"></i>
              <p>Profile</p>
            </a>
          </li>

          <li class="nav-item">
          <a href="{{ route('project.overview') }}" class="nav-link @if(Request::segment(3) == 'overview') active @endif" style="color: #EAEAEA;">
            <i class="nav-icon fas fa-tasks" style="color: #FF6F61;"></i>
            Project Overview
          </a>
        </li>
        <li class="nav-item">
        <a href="{{ route('notifications.index') }}" class="nav-link @if(Request::is('notifications')) active @endif">
            <i class="nav-icon fas fa-bell"></i>
            <p>Notifications</p>
        </a>
      </li>



        @endif
        <li class="nav-item">
          <a href="{{ route('logout') }}" class="nav-link" style="color: #EAEAEA;">
            <i class="nav-icon fas fa-sign-out-alt" style="color: #FF6F61;"></i>
            <p>Logout</p>
          </a>
        </li>
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>
