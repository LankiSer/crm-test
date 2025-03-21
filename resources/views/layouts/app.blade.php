<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} CRM</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        :root {
            --primary-color: #0b66c3;
            --sidebar-width: 250px;
            --header-height: 60px;
            --bitrix-light-blue: #ebf3fc;
            --bitrix-dark-blue: #0b66c3;
            --bitrix-green: #bbed21;
            --bitrix-gray: #eef2f4;
        }
        
        body {
            background-color: #f5f5f5;
            font-family: 'Figtree', sans-serif;
        }
        
        /* Header Styles */
        .app-header {
            height: var(--header-height);
            background-color: #fff;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 999;
        }
        
        .app-logo {
            color: var(--bitrix-dark-blue);
            font-weight: 600;
            font-size: 20px;
            text-decoration: none;
            display: flex;
            align-items: center;
        }
        
        .app-logo span {
            color: var(--bitrix-green);
        }
        
        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width);
            background-color: #fff;
            position: fixed;
            top: var(--header-height);
            left: 0;
            bottom: 0;
            box-shadow: 1px 0 3px rgba(0, 0, 0, 0.1);
            z-index: 998;
            overflow-y: auto;
            transition: all 0.3s;
        }
        
        .sidebar.collapsed {
            width: 60px;
        }
        
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .sidebar-item {
            position: relative;
        }
        
        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            color: #545454;
            text-decoration: none;
            transition: all 0.3s;
            border-left: 3px solid transparent;
            white-space: nowrap;
        }
        
        .sidebar-link:hover, 
        .sidebar-link.active {
            background-color: var(--bitrix-light-blue);
            color: var(--bitrix-dark-blue);
            border-left-color: var(--bitrix-dark-blue);
        }
        
        .sidebar-icon {
            margin-right: 10px;
            font-size: 18px;
            width: 24px;
            text-align: center;
        }
        
        .sidebar-text {
            transition: opacity 0.3s;
        }
        
        .collapsed .sidebar-text {
            opacity: 0;
            width: 0;
        }
        
        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: var(--header-height);
            padding: 20px;
            min-height: calc(100vh - var(--header-height));
            transition: margin-left 0.3s;
        }
        
        .main-content.expanded {
            margin-left: 60px;
        }
        
        /* Toggle Button */
        .sidebar-toggle {
            cursor: pointer;
            border: none;
            background: transparent;
        }
        
        /* Cards */
        .card {
            border-radius: 0.5rem;
            border: none;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }
        
        .card-header {
            background-color: white;
            border-bottom: 1px solid #eee;
            padding: 0.75rem 1.25rem;
        }
        
        /* Bitrix-style breadcrumb */
        .bitrix-breadcrumb {
            background-color: var(--bitrix-gray);
            padding: 10px 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        
        /* Notification badge */
        .notification-badge {
            position: absolute;
            top: 8px;
            right: 8px;
            background-color: #f44336;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* User menu */
        .user-menu {
            position: relative;
        }
        
        .user-menu-toggle {
            cursor: pointer;
            display: flex;
            align-items: center;
        }
        
        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            margin-right: 8px;
            background-color: var(--bitrix-light-blue);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--bitrix-dark-blue);
            font-weight: bold;
        }

        .header-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: var(--bitrix-gray);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #545454;
            margin-left: 10px;
            cursor: pointer;
            position: relative;
        }

        .header-icon:hover {
            background-color: var(--bitrix-light-blue);
            color: var(--bitrix-dark-blue);
        }
        
        /* Mobile adjustments */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <header class="app-header">
        <div class="container-fluid">
            <div class="row align-items-center h-100">
                <div class="col-auto">
                    <button class="sidebar-toggle" id="sidebar-toggle">
                        <i class="bi bi-list fs-4"></i>
                    </button>
                </div>
                <div class="col-auto">
                    <a href="{{ route('dashboard') }}" class="app-logo">
                        CRM<span>24</span>
                    </a>
                </div>
                <div class="col-auto ms-auto d-flex align-items-center">
                    <div class="header-icon" title="Поиск">
                        <i class="bi bi-search"></i>
                    </div>
                    <div class="header-icon" title="Уведомления">
                        <i class="bi bi-bell"></i>
                        @if(rand(0, 1))
                            <span class="notification-badge">{{ rand(1, 9) }}</span>
                        @endif
                    </div>
                    <div class="header-icon" title="Чат">
                        <a href="{{ route('chat.index') }}" class="text-decoration-none text-dark">
                            <i class="bi bi-chat-dots"></i>
                            @if(auth()->user() && auth()->user()->unreadMessages()->count() > 0)
                                <span class="notification-badge">{{ auth()->user()->unreadMessages()->count() }}</span>
                            @endif
                        </a>
                    </div>
                    <div class="user-menu ms-3">
                        <div class="user-menu-toggle">
                            <div class="user-avatar">
                                {{ auth()->user() ? strtoupper(substr(auth()->user()->name, 0, 1)) : 'Г' }}
                            </div>
                            <span class="ms-2 d-none d-sm-inline">{{ auth()->user() ? auth()->user()->name : 'Гость' }}</span>
                        </div>
                        <div class="dropdown position-absolute end-0 mt-2 d-none" id="userDropdown">
                            <div class="dropdown-menu show">
                                <a class="dropdown-item" href="#">
                                    <i class="bi bi-person me-2"></i> Профиль
                                </a>
                                <a class="dropdown-item" href="{{ route('settings') }}">
                                    <i class="bi bi-gear me-2"></i> Настройки
                                </a>
                                <div class="dropdown-divider"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right me-2"></i> Выйти
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    
    <aside class="sidebar" id="sidebar">
        <ul class="sidebar-menu">
            <li class="sidebar-item">
                <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2 sidebar-icon"></i>
                    <span class="sidebar-text">Панель управления</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="{{ route('contacts.index') }}" class="sidebar-link {{ request()->routeIs('contacts.*') ? 'active' : '' }}">
                    <i class="bi bi-person sidebar-icon"></i>
                    <span class="sidebar-text">Контакты</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="{{ route('companies.index') }}" class="sidebar-link {{ request()->routeIs('companies.*') ? 'active' : '' }}">
                    <i class="bi bi-building sidebar-icon"></i>
                    <span class="sidebar-text">Компании</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="{{ route('deals.index') }}" class="sidebar-link {{ request()->routeIs('deals.*') ? 'active' : '' }}">
                    <i class="bi bi-cash-stack sidebar-icon"></i>
                    <span class="sidebar-text">Сделки</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="{{ route('tasks.index') }}" class="sidebar-link {{ request()->routeIs('tasks.*') ? 'active' : '' }}">
                    <i class="bi bi-check2-square sidebar-icon"></i>
                    <span class="sidebar-text">Задачи</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="{{ route('chat.index') }}" class="sidebar-link {{ request()->routeIs('chat.*') ? 'active' : '' }}">
                    <i class="bi bi-chat-dots sidebar-icon"></i>
                    <span class="sidebar-text">Чат</span>
                    @if(auth()->user() && auth()->user()->unreadMessages()->count() > 0)
                        <span class="notification-badge">{{ auth()->user()->unreadMessages()->count() }}</span>
                    @endif
                </a>
            </li>
            <li class="sidebar-item">
                <a href="{{ route('reports') }}" class="sidebar-link {{ request()->routeIs('reports') ? 'active' : '' }}">
                    <i class="bi bi-bar-chart sidebar-icon"></i>
                    <span class="sidebar-text">Отчеты</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="{{ route('settings') }}" class="sidebar-link {{ request()->routeIs('settings') ? 'active' : '' }}">
                    <i class="bi bi-gear sidebar-icon"></i>
                    <span class="sidebar-text">Настройки</span>
                </a>
            </li>
        </ul>
    </aside>
    
    <main class="main-content" id="main-content">
        @if(isset($breadcrumbs))
        <div class="bitrix-breadcrumb">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Главная</a></li>
                    @foreach($breadcrumbs as $label => $url)
                        @if($loop->last)
                            <li class="breadcrumb-item active" aria-current="page">{{ $label }}</li>
                        @else
                            <li class="breadcrumb-item"><a href="{{ $url }}">{{ $label }}</a></li>
                        @endif
                    @endforeach
                </ol>
            </nav>
        </div>
        @endif
        
        <!-- Flash Messages -->
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Закрыть"></button>
            </div>
        @endif
        
        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Закрыть"></button>
            </div>
        @endif
        
        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Закрыть"></button>
            </div>
        @endif
        
        @yield('content')
    </main>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sidebar Toggle
            const sidebarToggle = document.getElementById('sidebar-toggle');
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('expanded');
            });
            
            // User Menu Toggle
            const userMenuToggle = document.querySelector('.user-menu-toggle');
            const userDropdown = document.getElementById('userDropdown');
            
            userMenuToggle.addEventListener('click', function() {
                userDropdown.classList.toggle('d-none');
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function(event) {
                if (!userMenuToggle.contains(event.target) && !userDropdown.contains(event.target)) {
                    userDropdown.classList.add('d-none');
                }
            });
            
            // Mobile sidebar toggle
            if (window.innerWidth < 768) {
                sidebar.classList.add('collapsed');
                mainContent.classList.add('expanded');
            }
        });
    </script>
</body>
</html>
