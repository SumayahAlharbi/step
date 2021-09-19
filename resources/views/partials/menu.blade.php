<div id="sidebar" class="c-sidebar c-sidebar-fixed c-sidebar-lg-show">

    <div class="c-sidebar-brand d-md-down-none">
        <a class="c-sidebar-brand-full h4" href="#">
            {{ trans('panel.site_title') }}
        </a>
    </div>

    <ul class="c-sidebar-nav">
        <li>
            <select class="searchable-field form-control">

            </select>
        </li>
        <li class="c-sidebar-nav-item">
            <a href="{{ route("admin.home") }}" class="c-sidebar-nav-link">
                <i class="c-sidebar-nav-icon fas fa-fw fa-tachometer-alt">

                </i>
                {{ trans('global.dashboard') }}
            </a>
        </li>
        @can('user_management_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/permissions*") ? "c-show" : "" }} {{ request()->is("admin/roles*") ? "c-show" : "" }} {{ request()->is("admin/users*") ? "c-show" : "" }} {{ request()->is("admin/teams*") ? "c-show" : "" }} {{ request()->is("admin/audit-logs*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-users c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.userManagement.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('permission_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.permissions.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/permissions") || request()->is("admin/permissions/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-unlock-alt c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.permission.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('role_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.roles.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/roles") || request()->is("admin/roles/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-briefcase c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.role.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('user_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.users.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/users") || request()->is("admin/users/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-user c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.user.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('audit_log_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.audit-logs.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/audit-logs") || request()->is("admin/audit-logs/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-file-alt c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.auditLog.title') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        @can('tracking_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/strategic-plans*") ? "c-show" : "" }} {{ request()->is("admin/goals*") ? "c-show" : "" }} {{ request()->is("admin/projects*") ? "c-show" : "" }} {{ request()->is("admin/initiatives*") ? "c-show" : "" }} {{ request()->is("admin/action-plans*") ? "c-show" : "" }} {{ request()->is("admin/risks*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-chart-line c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.tracking.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('strategic_plan_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.strategic-plans.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/strategic-plans") || request()->is("admin/strategic-plans/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-chart-bar c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.strategicPlan.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('goal_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.goals.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/goals") || request()->is("admin/goals/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-bullseye c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.goal.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('project_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.projects.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/projects") || request()->is("admin/projects/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-briefcase c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.project.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('initiative_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.initiatives.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/initiatives") || request()->is("admin/initiatives/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-clipboard-check c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.initiative.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('action_plan_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.action-plans.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/action-plans") || request()->is("admin/action-plans/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-clipboard-list c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.actionPlan.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('risk_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.risks.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/risks") || request()->is("admin/risks/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-exclamation-triangle c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.risk.title') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        @if(\Illuminate\Support\Facades\Schema::hasColumn('teams', 'owner_id') && \App\Models\Team::where('owner_id', auth()->user()->id)->exists())
            <li class="c-sidebar-nav-item">
                <a class="{{ request()->is("admin/team-members") || request()->is("admin/team-members/*") ? "c-active" : "" }} c-sidebar-nav-link" href="{{ route("admin.team-members.index") }}">
                    <i class="c-sidebar-nav-icon fa-fw fa fa-users">
                    </i>
                    <span>{{ trans("global.team-members") }}</span>
                </a>
            </li>
        @endif
        @if(file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php')))
            @can('profile_password_edit')
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ request()->is('profile/password') || request()->is('profile/password/*') ? 'c-active' : '' }}" href="{{ route('profile.password.edit') }}">
                        <i class="fa-fw fas fa-key c-sidebar-nav-icon">
                        </i>
                        {{ trans('global.change_password') }}
                    </a>
                </li>
            @endcan
        @endif
        <li class="c-sidebar-nav-item">
            <a href="#" class="c-sidebar-nav-link" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                <i class="c-sidebar-nav-icon fas fa-fw fa-sign-out-alt">

                </i>
                {{ trans('global.logout') }}
            </a>
        </li>
    </ul>

</div>
