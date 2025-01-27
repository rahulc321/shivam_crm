<div class="main-sidebar" id="sidebar-scroll">

    <!-- Start::nav -->
    <nav class="main-menu-container nav nav-pills flex-column sub-open">
        <div class="slide-left" id="slide-left">
            <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
                <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"></path>
            </svg>
        </div>
        <ul class="main-menu">
            <!-- Start::slide__category -->
            <li class="slide__category"><span class="category-name">Dashboards</span></li>
            <!-- End::slide__category -->

            <li class="slide">
                <a href="/admin" class="side-menu__item {{ request()->is('admin') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" height="24px" viewBox="0 0 24 24"
                        width="24px" fill="#5f6368">
                        <path d="M0 0h24v24H0V0z" fill="none" />
                        <path d="M12 3L2 12h3v8h6v-6h2v6h6v-8h3L12 3zm5 15h-2v-6H9v6H7v-7.81l5-4.5 5 4.5V18z" />
                        <path d="M7 10.19V18h2v-6h6v6h2v-7.81l-5-4.5z" opacity=".3" />
                    </svg>
                    <span class="side-menu__label">Dashboard</span>
                </a>
            </li>




            @if (Auth::user()->roles->contains('title', 'Admin'))
            <!-- Start::slide -->
            <li
                class="slide has-sub {{ request()->is('admin/permissions*') ? 'open' : '' }} {{ request()->is('admin/roles*') ? 'open' : '' }} {{ request()->is('admin/admin*') ? 'open' : '' }}">
                <a href="javascript:void(0);"
                    class="side-menu__item {{ request()->is('admin/permissions*') ? 'active' : '' }} {{ request()->is('admin/roles*') ? 'active' : '' }} {{ request()->is('admin/users*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" height="24px" viewBox="0 0 24 24"
                        width="24px" fill="#5f6368">
                        <path d="M0 0h24v24H0V0z" fill="none" />
                        <path
                            d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zm8 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zM8 13c-2.33 0-7 1.17-7 3.5V19h7v-2.5c0-2.33 4.67-3.5 7-3.5H8z" />
                    </svg>
                    <span class="side-menu__label">User Management</span>
                    <i class="ri-arrow-right-s-line side-menu__angle"></i>
                </a>
                <ul class="slide-menu child1 pages-ul">

                    <!-- <li class="slide">
                    <a href='{{ route("admin.permissions.index") }}' class="side-menu__item {{ request()->is('admin/permissions*') ? 'active' : '' }}">Permissions</a>
                </li> -->

                    <li class="slide">
                        <a href='{{ route("admin.roles.index") }}'
                            class="side-menu__item {{ request()->is('admin/roles*') ? 'active' : '' }}">Roles</a>
                    </li>

                    <li class="slide">
                        <a href='{{ route("admin.admin") }}'
                            class="side-menu__item {{ request()->is('admin/admin*') ? 'active' : '' }}">Admin</a>
                    </li>



                </ul>
            </li>


            <!--  -->
            <li
                class="slide has-sub {{ request()->is('admin/permissions*') ? 'open' : '' }} {{ request()->is('admin/roles*') ? 'open' : '' }} {{ request()->is('admin/users*') ? 'open' : '' }}">
                <a href="javascript:void(0);"
                    class="side-menu__item {{ request()->is('admin/permissions*') ? 'active' : '' }} {{ request()->is('admin/roles*') ? 'active' : '' }} {{ request()->is('admin/users*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" height="24px" viewBox="0 0 24 24"
                        width="24px" fill="#5f6368">
                        <path d="M0 0h24v24H0V0z" fill="none" />
                        <path
                            d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zm8 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zM8 13c-2.33 0-7 1.17-7 3.5V19h7v-2.5c0-2.33 4.67-3.5 7-3.5H8z" />
                    </svg>
                    <span class="side-menu__label">Users</span>
                    <i class="ri-arrow-right-s-line side-menu__angle"></i>
                </a>
                <ul class="slide-menu child1 pages-ul">


                    <?php
                        $roles = DB::table('roles')->where('title','!=','Admin')->get();
                    ?>
                    @foreach($roles as $role)
                    <li class="slide">
                        @php
                        // Assuming $role->name contains the role name
                        $roleName = is_object($role) ? $role->title : $role;
                        $displayRole = ucfirst(str_replace('end_user_', '', $roleName));
                        @endphp
                        <a href="{{ route('admin.users.index', ['type' => $roleName]) }}"
                            class="side-menu__item {{ request()->query('type') === $roleName ? 'active' : '' }}">
                            {{ $displayRole }}
                        </a>
                    </li>
                    @endforeach

                    <li class="slide">
                        <a href="{{ route('admin.contacts') }}"
                            class="side-menu__item {{ request()->is('admin/contacts*') ? 'active' : '' }}">
                            Contacts
                        </a>

                    </li>





                </ul>
            </li>


            <!-- MESSAGE -->
            <li class="slide">
                <a href='{{ route("admin.message.index") }}'
                    class="side-menu__item {{ request()->is('admin/message*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" height="24px" viewBox="0 0 24 24"
                        width="24px" fill="#5f6368">
                        <path d="M0 0h24v24H0V0z" fill="none" />
                        <path
                            d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 14H4v-4h16v4zm0-10H4V6h16v2z" />
                    </svg>
                    <span class="side-menu__label">Messages</span>
                </a>
            </li>

            @endif

            <!-- Task -->
            <li class="slide">
                <a href='{{ route("admin.task.index") }}'
                    class="side-menu__item {{ request()->is('admin/task*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" height="24px" viewBox="0 0 24 24"
                        width="24px" fill="#5f6368">
                        <path d="M0 0h24v24H0V0z" fill="none" />
                        <path
                            d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 14H4v-4h16v4zm0-10H4V6h16v2z" />
                    </svg>
                    <span class="side-menu__label">Task</span>
                </a>
            </li>


            <li class="slide">
                <a href='{{ route("admin.training") }}'
                    class="side-menu__item {{ request()->is('admin/training*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" height="24px" viewBox="0 0 24 24"
                        width="24px" fill="#5f6368">
                        <path d="M0 0h24v24H0z" fill="none" />
                        <path
                            d="M12 2L1 9l11 7 9-5.9V17h2V9L12 2zm0 2.75L18.14 9 12 13.25 5.86 9 12 4.75zM4 19h16v2H4v-2z" />
                    </svg>
                    <span class="side-menu__label">Training</span>
                </a>
            </li>


        </ul>
        <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24"
                height="24" viewBox="0 0 24 24">
                <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"></path>
            </svg></div>
    </nav>
    <!-- End::nav -->

</div>