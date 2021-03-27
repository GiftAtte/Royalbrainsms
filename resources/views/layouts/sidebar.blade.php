<aside class="main-sidebar sidebar-dark-primary elevation-4" id="navbar-collapse">
    
    <!-- Brand Logo -->
    
    <a href="/dashboard" class="brand-link">
      <img src="/img/logo.png" alt=" Logo" class="brand-image img-circle elevation-3"
           style="opacity: .98">
    <span class="brand-text font-weight-light">@{{this.school.short_name}}</span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
       
        <!-- Sidebar user panel (optional) -->
       
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image">
            <img src="/img/admin.jpg" class="img-circle elevation-2" alt="User Image">
          </div>
          <div class="info">
            <a href="#" class="d-block">
              {{Auth::user()->name}}
              <p>{{Auth::user()->type}}</p>
             
          </a>
          </div>
        </div>
  


        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
                 with font-awesome or any other icon font library -->
  
              <li class="nav-item">
              <router-link to="/dashboard" class="nav-link">
                  <i class="nav-icon fas fa-tachometer-alt "></i>
                  <p>
                  Dashboard
  
                  </p>
              </router-link>
              </li>
  
              @can('isAdmin')
            <li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                <i class="nav-icon fa fa-cog red"></i>
                <p>
                  Management
                  <i class="right fa fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <router-link to="/users" class="nav-link">
                    <i class="fas fa-users nav-icon"></i>
                    <p>Users</p>
                  </router-link>
                </li>
                <li class="nav-item">
                  <router-link to="/staff" class="nav-link">
                    <i class="fas fa-users nav-icon navy"></i>
                    <p>Employees</p>
                  </router-link>
                </li>
                <li class="nav-item">
                  <router-link to="/students" class="nav-link">
                    <i class="fas fa-users nav-icon"></i>
                    <p>Students</p>
                  </router-link>
                </li>

                <li class="nav-item">
                  <router-link to="/levels" class="nav-link">
                    <i class="fas fa-bell nav-icon"></i>
                    <p>Levels</p>
                  </router-link>
                </li>
                <li class="nav-item">
                  <router-link to="/subjects" class="nav-link">
                    <i class="fas fa-bell nav-icon"></i>
                    <p>Subject</p>
                  </router-link>
                </li>
                <li class="nav-item">
                  <router-link to="/level_subjects" class="nav-link" >
                    <i class="fas fa-bell nav-icon"></i>
                    <p>Class Subjects</p>
                  </router-link>
                </li>
                <li class="nav-item">
                <router-link to="/reports" class="nav-link">
                  <i class="fa fa-bookmark" aria-hidden="true"></i>
                  <p>Report card</p>
                </router-link>
              </li>
              <li class="nav-item">
                <router-link to="/scores" class="nav-link">
                  <i class="fa fa-list-alt" aria-hidden="true"></i>
                  <p>Score Card</p>
                </router-link>
              </li>
              </ul>
            </li>
  
            <li class="nav-item">
                  <router-link to="/arm_list" class="nav-link">
                   <i class="fa fa-bookmark" aria-hidden="true"></i>
                      <p>
                          Arms List
                      </p>
                  </router-link>
           </li>
            <li class="nav-item">
                  <router-link to="/load_activation" class="nav-link">
                    <i class="fa fa-key" aria-hidden="true"></i>
                      <p>
                          Activate Results
                      </p>
                  </router-link>
           </li>
           <li class="nav-item">
                  <router-link to="/chart" class="nav-link">
                     <i class="fa fa-graduation-cap" aria-hidden="true"></i>
                      <p>
                          Perfomance Tracker
                      </p>
                  </router-link>
           </li>
           @endcan
           <li class="nav-item">
                  <router-link to="/mail" class="nav-link">
                      <i class="nav-icon fas fa-envelope "></i>
                      <p>
                          Send Mail
                      </p>
                  </router-link>
           </li>
  
            <li class="nav-item">
                  <router-link to="/assignments" class="nav-link">
                      <i class="fa fa-book" green aria-hidden="true"></i>
                      <p>
                         Assignment-Notes
                      </p>
                  </router-link>
           </li>
           {{-- CBT --}}
           <li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                <i class="nav-icon fa fa-cog green"></i>
                <p>
                  CBT
                  <i class="right fa fa-angle-left"></i>
                </p>
              </a>
             <ul class="nav nav-treeview">
                <li class="nav-item">
                  <router-link to="#" class="nav-link">
                    <i class="fas fa-users nav-icon"></i>
                    <p>Exam</p>
                  </router-link>
                </li>
                <li class="nav-item">
                  <router-link to="#" class="nav-link">
                    <i class="fas fa-users nav-icon"></i>
                    <p>Take Exam</p>
                  </router-link>
                </li>
             </ul>
             {{-- end of CBT --}}
           </li>
           {{-- Settings --}}
         <li class="nav-item has-treeview ">
              <a href="#" class="nav-link">
                <i class="nav-icon fa fa-cog purple"></i>
                <p>
                  Settings
                  <i class="right fa fa-angle-left"></i>
                </p>
              </a>
             <ul class="nav nav-treeview">
                <li class="nav-item">
                  <router-link to="/school" class="nav-link">
                    <i class="fas fa-users nav-icon"></i>
                    <p>School</p>
                  </router-link>
                </li>
                <li class="nav-item">
                  <router-link to="#" class="nav-link">
                    <i class="fas fa-users nav-icon"></i>
                    <p>Roles</p>
                  </router-link>
                </li>
             </ul>
             {{-- end Settings --}}
           </li>

            <li class="nav-item">
                  <a class="nav-link" href="{{ route('logout') }}"
                  onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                      <i class="nav-icon fa fa-power-off red"></i>
                      <p>
                          {{ __('Logout') }}
                      </p>
                   </a>
  
               <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                   @csrf
               </form>
          </li>
          </ul>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>
  
  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
    <div class="p-3">
      <h5>Title</h5>
      <p>Sidebar content</p>
    </div>
  </aside>
 
  
  <!-- /.control-sidebar -->