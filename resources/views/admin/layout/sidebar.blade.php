<div class="sidebar">
    <div class="side-head">
        <a href="{{route('admin.dashboard')}}" class="primary-color side-logo">
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
        <li class="side_line {{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}{{ request()->routeIs('profile*') ? 'active' : '' }}">
            <a href="{{ route('admin.dashboard')}}">
                <i class="fa-solid fa-house fa-2xl menu-icon"></i>
                <span>Home</span>
            </a>
        </li>
        <li class="side_line {{ request()->routeIs('earning.dashboard*') ? 'active' : '' }}">
            <a href="{{ route('earning.dashboard')}}">
                <i class="fa-solid fa-chart-line fa-2xl menu-icon"></i>
                <span>Earnings</span>
            </a>
        </li>

        <p class="partition"><span>Basic Element</span></p>
        <li class="dropdown {{ request()->routeIs('category*') ? 'active' : '' }}{{ request()->routeIs('gift*') ? 'active' : '' }}{{ request()->routeIs('language*') ? 'active' : '' }}{{ request()->routeIs('hashtag*') ? 'active' : '' }}{{ request()->routeIs('artist*') ? 'active' : '' }}{{ request()->routeIs('page*') ? 'active' : '' }}">
            <a class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa-solid fa-sliders fa-2xl menu-icon"></i>
                <span> Basic Items </span>
            </a>
            <ul class="dropdown-menu side-submenu {{ request()->routeIs('category*') ? 'show' : '' }}{{ request()->routeIs('gift*') ? 'show' : '' }}{{ request()->routeIs('language*') ? 'show' : '' }}{{ request()->routeIs('hashtag*') ? 'show' : '' }}{{ request()->routeIs('artist*') ? 'show' : '' }}{{ request()->routeIs('page*') ? 'show' : '' }}">
                <li class="side_line {{ request()->routeIs('category*') ? 'active' : '' }}">
                    <a href="{{ route('category.index') }}" class="dropdown-item">
                        <i class="fa-solid fa-shapes fa-2xl submenu-icon"></i>
                        <span>{{__('Label.Category')}}</span>
                    </a>
                </li>
                <li class="side_line {{ request()->routeIs('language*') ? 'active' : '' }}">
                    <a href="{{ route('language.index') }}" class="dropdown-item">
                        <i class="fa-solid fa-globe fa-2xl submenu-icon"></i>
                        <span>{{__('Label.Language')}}</span>
                    </a>
                </li>
                <li class="side_line {{ request()->routeIs('artist*') ? 'active' : '' }}">
                    <a href="{{ route('artist.index') }}" class="dropdown-item">
                        <i class="fa-solid fa-user-tie fa-2xl submenu-icon"></i>
                        <span>{{__('Label.Artist')}}</span>
                    </a>
                </li>
                <li class="side_line {{ request()->routeIs('hashtag*') ? 'active' : '' }}">
                    <a href="{{ route('hashtag.index') }}" class="dropdown-item">
                        <i class="fa-solid fa-hashtag fa-2xl submenu-icon"></i>
                        <span>Hashtag</span>
                    </a>
                </li>
                <li class="side_line  {{ request()->routeIs('page*') ? 'active' : '' }}">
                    <a href="{{ route('page.index') }}" class="dropdown-item">
                        <i class="fa-solid fa-book-open-reader fa-2xl submenu-icon"></i>
                        <span>{{__('Label.Pages')}}</span>
                    </a>
                </li>
                <li class="side_line {{ request()->routeIs('gift*') ? 'active' : '' }}">
                    <a href="{{ route('gift.index') }}" class="dropdown-item">
                        <i class="fa-solid fa-gift fa-2xl submenu-icon"></i>
                        <span>{{__('Label.gift')}}</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="side_line {{ request()->routeIs('user*') ? 'active' : '' }}">
            <a href="{{ route('user.index') }}">
                <i class="fa-solid fa-users fa-2xl menu-icon"></i>
                <span>{{__('Label.Users')}}</span>
            </a>
        </li>

        <p class="partition"><span>Configuration</span></p>
        <li class="side_line {{ request()->routeIs('section*') ? 'active' : '' }}">
            <a href="{{ route('section.index') }}">
                <i class="fa-solid fa-bars-staggered fa-2xl menu-icon"></i>
                <span>Section</span>
            </a>
        </li>

        <p class="partition"><span>Content</span></p>
        <li class="side_line {{ request()->routeIs('video*') ? 'active' : '' }}">
            <a href="{{ route('video.index') }}">
                <i class="fa-solid fa-video fa-2xl menu-icon"></i>
                <span>{{__('Label.Video')}}</span>
            </a>
        </li>
        <li class="side_line {{ request()->routeIs('music*') ? 'active' : '' }}">
            <a href="{{ route('music.index') }}">
                <i class="fa-solid fa-music fa-2xl menu-icon"></i>
                <span>Music</span>
            </a>
        </li>
        <li class="side_line {{ request()->routeIs('reels*') ? 'active' : '' }}">
            <a href="{{ route('reels.index') }}">
                <i class="fa-solid fa-film fa-2xl menu-icon"></i>
                <span>Reels</span>
            </a>
        </li>
        <li class="{{ (request()->routeIs('post*')) ? 'active' : '' }}">
            <a href="{{ route('post.index') }}">
                <i class="fa-solid fa-square-plus fa-2xl menu-icon"></i>
                <span>{{__('Label.post')}}</span>
            </a>
        </li>
        <li class="side_line {{ request()->routeIs('podcast*') ? 'active' : '' }}">
            <a href="{{ route('podcasts.index') }}">
                <i class="fa-solid fa-podcast fa-2xl menu-icon"></i>
                <span>Podcasts</span>
            </a>
        </li>
        <li class="side_line {{ request()->routeIs('radio*') ? 'active' : '' }}">
            <a href="{{ route('radio.index') }}">
                <i class="fa-solid fa-radio fa-2xl menu-icon"></i>
                <span>Radio</span>
            </a>
        </li>
        <li class="side_line {{ request()->routeIs('playlist*') ? 'active' : '' }}">
            <a href="{{ route('playlist.index') }}">
                <i class="fa-solid fa-headphones fa-2xl menu-icon"></i>
                <span>Playlist</span>
            </a>
        </li>

        <p class="partition"><span>Rent</span></p>
        <li class="side_line {{ request()->routeIs('rentsection*') ? 'active' : '' }}">
            <a href="{{ route('rentsection.index') }}">
                <i class="fa-solid fa-bars-staggered fa-2xl menu-icon"></i>
                <span>Section</span>
            </a>
        </li>
        <li class="side_line {{ request()->routeIs('renttransaction*') ? 'active' : '' }}">
            <a href="{{ route('renttransaction.index') }}">
                <i class="fa-solid fa-wallet fa-2xl menu-icon"></i>
                <span>{{__('Label.Transactions')}}</span>
            </a>
        </li>
        <li class="side_line {{ request()->routeIs('rentsetting*') ? 'active' : '' }}">
            <a href="{{ route('rentsetting.index') }}">
                <i class="fa-solid fa-gear fa-2xl menu-icon"></i>
                <span>Settings</span>
            </a>
        </li>

        <p class="partition"><span>Interaction</span></p>
        <li class="side_line {{ request()->routeIs('comment*') ? 'active' : '' }}">
            <a href="{{ route('comment.index') }}">
                <i class="fa-solid fa-comments fa-2xl menu-icon"></i>
                <span>Comment</span>
            </a>
        </li>
        <li class="side_line {{ request()->routeIs('notification*') ? 'active' : '' }}">
            <a href="{{ route('notification.index') }}">
                <i class="fa-solid fa-bell fa-2xl menu-icon"></i>
                <span>Notification</span>
            </a>
        </li>

        <p class="partition"><span>Report</span></p>
        <li class="side_line  {{ request()->routeIs('report.*') ? 'active' : '' }}">
            <a href="{{ route('report.index') }}">
                <i class="fa-solid fa-list fa-2xl menu-icon"></i>
                <span>Reason List</span>
            </a>
        </li>
        <li class="side_line  {{ request()->routeIs('reportpost*') ? 'active' : '' }}">
            <a href="{{ route('reportpost.index') }}">
                <i class="fa-solid fa-flag fa-2xl menu-icon"></i>
                <span>Post Report</span>
            </a>
        </li>
        <li class="side_line  {{ request()->routeIs('contentreport*') ? 'active' : '' }}">
            <a href="{{ route('contentreport.index') }}">
                <i class="fa-solid fa-clapperboard fa-2xl menu-icon"></i>
                <span>Content</span>
            </a>
        </li>
        <li class="side_line {{ request()->routeIs('commenreport*') ? 'active' : '' }}">
            <a href="{{ route('commenreport.index') }}">
                <i class="fa-solid fa-comments fa-2xl menu-icon"></i>
                <span>Comment</span>
            </a>
        </li>

        <p class="partition"><span>Financial</span></p>
        <li class="dropdown {{ request()->routeIs('package*') ? 'active' : '' }}{{ request()->routeIs('payment*') ? 'active' : '' }}{{ request()->routeIs('transaction*') ? 'active' : '' }}">
            <a class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa-solid fa-money-bill fa-2xl menu-icon"></i>
                <span>Subscription</span>
            </a>
            <ul class="dropdown-menu side-submenu {{ request()->routeIs('package*') ? 'show' : '' }}{{ request()->routeIs('payment*') ? 'show' : '' }}{{ request()->routeIs('transaction*') ? 'show' : '' }}">
                <li class="side_line {{ request()->routeIs('package*') ? 'active' : '' }}">
                    <a href="{{ route('package.index') }}" class="dropdown-item">
                        <i class="fa-solid fa-box-archive fa-2xl submenu-icon"></i>
                        <span>{{__('Label.Package')}}</span>
                    </a>
                </li>
                <li class="side_line {{ request()->routeIs('transaction*') ? 'active' : '' }}">
                    <a href="{{ route('transaction.index') }}" class="dropdown-item">
                        <i class="fa-solid fa-wallet fa-2xl submenu-icon"></i>
                        <span>{{__('Label.Transactions')}}</span>
                    </a>
                </li>
                <li class="side_line {{ request()->routeIs('payment*') ? 'active' : '' }}">
                    <a href="{{ route('payment.index') }}" class="dropdown-item">
                        <i class="fa-solid fa-money-bill-wave fa-2xl submenu-icon"></i>
                        <span>{{__('Label.Payment')}}</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="dropdown {{ request()->routeIs('withdrawal*') ? 'active' : '' }}{{ request()->routeIs('walletuser*') ? 'active' : '' }}">
            <a class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa-solid fa-wallet fa-2xl menu-icon"></i>
                <span>Wallet</span>
            </a>
            <ul class="dropdown-menu side-submenu {{ request()->routeIs('withdrawal*') ? 'show' : '' }}{{ request()->routeIs('walletuser*') ? 'show' : '' }}">
                <li class="side_line {{ request()->routeIs('walletuser*') ? 'active' : '' }}">
                    <a href="{{ route('walletuser.index') }}" class="dropdown-item">
                        <i class="fa-solid fa-wallet fa-2xl submenu-icon"></i>
                        <span>User Wallet</span>
                    </a>
                </li>
                <li class="side_line {{ request()->routeIs('withdrawal*') ? 'active' : '' }}">
                    <a href="{{ route('withdrawal.index') }}" class="dropdown-item">
                        <i class="fa-solid fa-right-left fa-2xl submenu-icon"></i>
                        <span>Withdrawal</span>
                    </a>
                </li>
            </ul>
        </li>

        <p class="partition"><span>Ads</span></p>
        <li class="dropdown {{ request()->routeIs('ads*') ? 'active' : '' }}{{ request()->routeIs('adpackage*') ? 'active' : '' }}{{ request()->routeIs('adtransaction*') ? 'active' : '' }}{{ request()->routeIs('customadssetting*') ? 'active' : '' }}">
            <a class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa-solid fa-rectangle-ad fa-2xl menu-icon"></i>
                <span>Custom Ads</span>
            </a>
            <ul class="dropdown-menu side-submenu {{ request()->routeIs('ads*') ? 'show' : '' }}{{ request()->routeIs('adpackage*') ? 'show' : '' }}{{ request()->routeIs('adtransaction*') ? 'show' : '' }}{{ request()->routeIs('customadssetting*') ? 'show' : '' }}">
                <li class="side_line {{ request()->routeIs('ads*') ? 'active' : '' }}">
                    <a href="{{ route('ads.index') }}" class="dropdown-item">
                        <i class="fa-solid fa-rectangle-ad fa-2xl submenu-icon"></i>
                        <span>Custom Ads</span>
                    </a>
                </li>
                <li class="side_line {{ request()->routeIs('adpackage*') ? 'active' : '' }}">
                    <a href="{{ route('adpackage.index') }}" class="dropdown-item">
                        <i class="fa-solid fa-box-archive fa-2xl submenu-icon"></i>
                        <span>Coin {{__('Label.Package')}}</span>
                    </a>
                </li>
                <li class="side_line {{ request()->routeIs('adtransaction*') ? 'active' : '' }}">
                    <a href="{{ route('adtransaction.index') }}" class="dropdown-item">
                        <i class="fa-solid fa-wallet fa-2xl submenu-icon"></i>
                        <span>Coin {{__('Label.Transactions')}}</span>
                    </a>
                </li>
                <li class="side_line {{ request()->routeIs('customadssetting*') ? 'active' : '' }}">
                    <a href="{{ route('customadssetting.index') }}" class="dropdown-item">
                        <i class="fa-solid fa-gear fa-2xl submenu-icon"></i>
                        <span>Settings</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="side_line {{ request()->routeIs('admob*') ? 'active' : '' }}">
            <a href="{{ route('admob.index') }}">
                <i class="fa-brands fa-square-google-plus fa-2xl menu-icon"></i>
                <span>AdMob</span>
            </a>
        </li>
        <!-- <li class="side_line {{ request()->routeIs('fbads*') ? 'active' : '' }}">
            <a href="{{ route('fbads.index') }}">
                <i class="fa-brands fa-square-facebook fa-2xl menu-icon"></i>
                <span>FaceBook Ads</span>
            </a>
        </li>  -->

        <p class="partition"><span>Settings</span></p>
        <li class="side_line {{ request()->routeIs('setting*') ? 'active' : '' }}">
            <a href="{{ route('setting') }}">
                <i class="fa-solid fa-gear fa-2xl menu-icon"></i>
                <span>App Settings</span>
            </a>
        </li>
        <li class="side_line {{ request()->routeIs('system.setting*') ? 'active' : '' }}">
            <a href="{{ route('system.setting.index') }}">
                <i class="fa-solid fa-screwdriver-wrench fa-2xl menu-icon"></i>
                <span>System Settings</span>
            </a>
        </li>

        <p class="partition"><span>Logout</span></p>
        <li>
            <a href="{{ route('admin.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fa-solid fa-arrow-right-from-bracket fa-2xl menu-icon"></i>
                <span>{{__('Label.Logout')}}</span>
            </a>

            <form id="logout-form" action="{{ route('admin.logout') }}" method="GET" class="d-none">
                @csrf
            </form>
        </li>
    </ul>
</div>