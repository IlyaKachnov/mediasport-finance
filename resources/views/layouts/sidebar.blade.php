<div class="page-sidebar-wrapper">

    <div class="page-sidebar navbar-collapse collapse">

        <ul class="page-sidebar-menu  page-header-fixed " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" style="padding-top: 20px">

            <li class="sidebar-toggler-wrapper hide">
                <div class="sidebar-toggler">
                    <span></span>
                </div>
            </li>   
            <li class="nav-item {{activeLink('/')}} start">
                <a href="{{url('/')}}" class="nav-link nav-toggle">
                    <i class="icon-home"></i>
                    <span class="title">Главная</span>
                    <span class="{{selected('/')}}"></span>
                </a>
            </li>
            @if(Auth::user()->isAdmin() || Auth::user()->isManager())
            <li class="heading">
                <h3 class="uppercase">Меню</h3>
            </li>
            @endif
            @if(Auth::user()->isAdmin() || Auth::user()->isOrganizer())
            <li class="nav-item {{activeLink('leagues')}} ">
                <a href="{{url('leagues')}}" class="nav-link nav-toggle">
                    <i class="icon-trophy"></i>
                    <span class="title">Лиги</span>
                    <span class="{{selected('leagues')}}"></span>
                </a>
            </li>
            <li class="nav-item {{activeSubMenu('matches')}}">
                <a href="javascript:;" class="nav-link nav-toggle ">
                    <i class="icon-game-controller"></i>
                    <span class="title">Матчи</span>
                    <span class="{{selected('matches')}}"></span>
                    <span class="arrow {{activeArrow('matches')}}"></span>
                </a>
                <ul class="sub-menu">
                    @foreach($allGyms as $item)
                    <li class="nav-item  {{activeSegment($item->id,'matches')}} ">
                        <a href="{{url('gyms')}}/{{$item->id}}/matches" class="nav-link ">
                            <span class="title">{{$item->name}}</span>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </li>

            <li class="nav-item {{activeSubMenu('fees')}} ">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="icon-wallet"></i>
                    <span class="title">Взносы</span>
                    <span class="{{selected('fees')}}"></span>
                    <span class="arrow {{activeArrow('fees')}}"></span>
                </a>
                <ul class="sub-menu">
                    @foreach($allLeagues as $leagueItem)
                    <li class="nav-item {{activeSegment($leagueItem->id,'fees')}} ">
                        <a href="{{url('leagues')}}/{{$leagueItem->id}}/fees" class="nav-link ">
                            <span class="title">{{$leagueItem->name}}</span>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </li>
            @elseif(Auth::user()->isManager())
                @if(Auth::user()->gyms)
            <li class="nav-item {{activeSubMenu('matches')}}">
                <a href="javascript:;" class="nav-link nav-toggle ">
                    <i class="icon-game-controller"></i>
                    <span class="title">Матчи</span>
                    <span class="{{selected('matches')}}"></span>
                    <span class="arrow {{activeArrow('matches')}}"></span>
                </a>
                <ul class="sub-menu">
                    @foreach(Auth::user()->gyms as $gymItem)
                    <li class="nav-item  {{activeSegment($gymItem->id,'matches')}} ">
                        <a href="{{url('gyms')}}/{{$gymItem->id}}/matches" class="nav-link ">
                            <span class="title">{{$gymItem->name}}</span>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </li>
                @endif
           @endif

             @if(Auth::user()->isAdmin() || Auth::user()->isOrganizer())
            <li class="nav-item {{activeLink('gyms')}}  ">
                <a href="{{url('gyms')}}" class="nav-link nav-toggle">
                    <i class="icon-pointer"></i>
                    <span class="title">Залы</span>
                    <span class="{{selected('gyms')}}"></span>
                </a>

            </li>
            <li class="nav-item {{activeLink('referees')}}  ">
                <a href="{{url('referees')}}" class="nav-link nav-toggle">
                    <i class="icon-user"></i>
                    <span class="title">Судьи</span>
                    <span class="{{selected('referees')}}"></span>
                </a>

            </li>
            <li class="nav-item {{Request::is('incomes')||Request::is('consumptions')?'active open':''}}">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="icon-graph"></i>
                    <span class="title">Общее</span>
                    <span class="{{Request::is('incomes')||Request::is('consumptions')?'selected': ''}}"></span>
                    <span class="arrow {{Request::is('incomes')||Request::is('consumptions')?'open':''}}"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item {{activeLink('incomes')}}">
                        <a href="{{url('incomes')}}" class="nav-link ">
                            <span class="title">Доходы</span>
                        </a>
                    </li>
                    <li class="nav-item {{activeLink('consumptions')}}">
                        <a href="{{url('consumptions')}}" class="nav-link ">
                            <span class="title">Расходы</span>
                        </a>
                    </li> 
                </ul>
            </li>
            @endif
             @if(Auth::user()->isAdmin())
            <li class="nav-item {{Request::is('users') ? 'active open':''}}">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="icon-briefcase"></i>
                    <span class="title">Управление</span>
                    <span class="{{Request::is('users') ? 'selected':''}}"></span>
                    <span class="arrow {{Request::is('users') ? 'open':''}}"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item  {{activeLink('users')}}">
                        <a href="{{url('users')}}" class="nav-link ">
                            <span class="title">Пользователи</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endif
             @if(Auth::user()->isAdmin() || Auth::user()->isOrganizer())
            <li class="heading">
                <h3 class="uppercase">Отчеты</h3>
            </li>
            <li class="nav-item  {{activeLink('gyms/report')}} ">
                <a href="{{route('gyms.report')}}" class="nav-link nav-toggle">
                    <i class="icon-pointer"></i>
                    <span class="title">Залы</span>
                    <span class="{{selected('gyms/report')}}"></span>
                </a>
            </li>
            <li class="nav-item   {{activeLink('referees/report')}} ">
                <a href="{{route('referees.report')}}" class="nav-link nav-toggle">
                    <i class="icon-user"></i>
                    <span class="title">Судьи</span>
                    <span class="{{selected('referees/report')}}"></span>
                </a>
            </li>
            <li class="nav-item   {{activeLink('common-reports')}} ">
                <a href="{{url('common-reports')}}" class="nav-link nav-toggle">
                    <i class="icon-graph"></i>
                    <span class="title">Общее</span>
                    <span class="{{selected('common-reports')}}"></span>
                </a>
            </li>
            <li class="nav-item  {{activeLink('teams/report')}}" style="display: none;">
                <a href="{{route('teams.report')}}" class="nav-link nav-toggle">
                    <i class="icon-flag"></i>
                    <span class="title">Команды</span>
                    <span class="{{selected('teams/report')}}"></span>
                </a>
            </li>
            <li class="nav-item  {{activeLink('total/report')}}  ">
                <a href="{{route('total.report')}}" class="nav-link nav-toggle">
                    <i class="icon-book-open"></i>
                    <span class="title">Итоги</span>
                    <span class="{{selected('total/report')}}"></span>
                </a>
            </li>
           @endif
        </ul>
    </div>
</div>