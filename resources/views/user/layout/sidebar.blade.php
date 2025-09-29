<div class="sidebar">
    <div class="side-head">
        <a href="{{route('user.dashboard')}}" class="primary-color side-logo">
            <h3>{{App_Name()}}</h3>
        </a>
        <button class="btn side-toggle">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>

    <ul class="side-menu mt-4">

        <p class="partition"><span>{{__('Label.Dashboard')}}</span></p>
        <li class="side_line {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
            <a href="{{ route('user.dashboard')}}">
                <i class="fa-solid fa-house fa-2xl menu-icon"></i>
                <span>{{__('Label.Dashboard')}}</span>
            </a>
        </li>

        <p class="partition"><span>Profile</span></p>
        <li class="dropdown {{ request()->routeIs('uprofile*') ? 'active' : '' }}{{ request()->routeIs('uchangepassword*') ? 'active' : '' }}">
            <a class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa-solid fa-user fa-2xl menu-icon"></i>
                <span>Profile</span>
            </a>
            <ul class="dropdown-menu side-submenu {{ request()->routeIs('uprofile*') ? 'show' : '' }}{{ request()->routeIs('uchangepassword*') ? 'show' : '' }}">
                <li class="side_line {{ request()->routeIs('uprofile*') ? 'active' : '' }}">
                    <a href="{{ route('uprofile.index')}}" class="dropdown-item">
                        <i class="fa-solid fa-user fa-2xl submenu-icon"></i>
                        <span>Profile</span>
                    </a>
                </li>
                <li class="side_line {{ request()->routeIs('uchangepassword*') ? 'active' : '' }}">
                    <a href="{{ route('uchangepassword.index')}}" class="dropdown-item">
                        <i class="fa-solid fa-lock fa-2xl submenu-icon"></i>
                        <span>Change Password</span>
                    </a>
                </li>
            </ul>
        </li>

        <p class="partition"><span>Content</span></p>
        <li class="side_line {{ request()->routeIs('uvideo*') ? 'active' : '' }}">
            <a href="{{ route('uvideo.index') }}">
                <i class="fa-solid fa-video fa-2xl menu-icon"></i>
                <span>{{__('Label.Video')}}</span>
            </a>
        </li>
        <li class="side_line {{ request()->routeIs('ureels*') ? 'active' : '' }}">
            <a href="{{ route('ureels.index') }}">
                <i class="fa-solid fa-film fa-2xl menu-icon"></i>
                <span>Reels</span>
            </a>
        </li>
        <li class="{{ (request()->routeIs('upost*')) ? 'active' : '' }}">
            <a href="{{ route('upost.index') }}">
                <i class="fa-solid fa-square-plus fa-2xl menu-icon"></i>
                <span>{{__('Label.post')}}</span>
            </a>
        </li>
        <li class="side_line {{ request()->routeIs('upodcast*') ? 'active' : '' }}">
            <a href="{{ route('upodcasts.index') }}">
                <i class="fa-solid fa-podcast fa-2xl menu-icon"></i>
                <span>Podcasts</span>
            </a>
        </li>
        <li class="side_line {{ request()->routeIs('uplaylist*') ? 'active' : '' }}">
            <a href="{{ route('uplaylist.index') }}">
                <i class="fa-solid fa-headphones fa-2xl menu-icon"></i>
                <span>Playlist</span>
            </a>
        </li>

        <p class="partition"><span>Ads</span></p>
        <li class="dropdown {{ request()->routeIs('uads*') ? 'active' : '' }}{{ request()->routeIs('uadpackage*') ? 'active' : '' }}{{ request()->routeIs('uadtransaction*') ? 'active' : '' }}">
            <a class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa-solid fa-rectangle-ad fa-2xl menu-icon"></i>
                <span>Ads</span>
            </a>
            <ul class="dropdown-menu side-submenu {{ request()->routeIs('uads*') ? 'show' : '' }}{{ request()->routeIs('uadpackage*') ? 'show' : '' }}{{ request()->routeIs('uadtransaction*') ? 'show' : '' }}">
                <li class="side_line {{ request()->routeIs('uads*') ? 'active' : '' }}">
                    <a href="{{ route('uads.index') }}" class="dropdown-item">
                        <i class="fa-solid fa-rectangle-ad fa-2xl submenu-icon"></i>
                        <span>Ads</span>
                    </a>
                </li>
                <li class="side_line {{ request()->routeIs('uadpackage*') ? 'active' : '' }}">
                    <a href="{{ route('uadpackage.index') }}" class="dropdown-item">
                        <i class="fa-solid fa-box-archive fa-2xl submenu-icon"></i>
                        <span>{{__('Label.Package')}}</span>
                    </a>
                </li>
                <li class="side_line {{ request()->routeIs('uadtransaction*') ? 'active' : '' }}">
                    <a href="{{ route('uadtransaction.index') }}" class="dropdown-item">
                        <i class="fa-solid fa-wallet fa-2xl submenu-icon"></i>
                        <span>{{__('Label.Transactions')}}</span>
                    </a>
                </li>
            </ul>
        </li>

        <p class="partition"><span>Financial</span></p>
        <li class="side_line {{ request()->routeIs('uwithdrawal*') ? 'active' : '' }}">
            <a href="{{ route('uwithdrawal.index') }}">
            <i class="fa-solid fa-wallet fa-2xl menu-icon"></i>
                <span>Withdrawal</span>
            </a>
        </li>

        <p class="partition"><span>Logout</span></p>
        <li>
            <a href="{{ route('user.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fa-solid fa-arrow-right-from-bracket fa-2xl menu-icon"></i>
                <span>{{__('Label.Logout')}}</span>
            </a>

            <form id="logout-form" action="{{ route('user.logout') }}" method="GET" class="d-none">
                @csrf
            </form>
        </li>
    </ul>
</div>