<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
    .main-container {
        display: flex;
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 20px;
    }
    .sidebar {
        width: 280px;
        margin-right: 30px;
    }
    .user-info {
        padding: 20px;
        border-bottom: 1px solid #e0e0e0;
        font-weight: 500;
        color: #333;
    }
    .user-info i {
        margin-right: 10px;
        color: #0d6efd;
    }
    .org-section, .module-section {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }
    .org-header, .module-header {
        padding: 15px 20px;
        border-bottom: 1px solid #e0e0e0;
        font-weight: 600;
        color: #666;
        font-size: 0.9rem;
        text-transform: uppercase;
    }
    .org-menu {
        list-style: none;
        padding: 10px 0;
        margin: 0;
    }
    .org-menu li a {
        display: block;
        padding: 8px 20px;
        color: #333;
        text-decoration: none;
        font-size: 0.95rem;
        transition: all 0.3s;
    }
    .org-menu li a:hover {
        background-color: #f0f7ff;
    }
    .org-menu li a.active {
        background-color: #e6f2ff;
        border-left: 3px solid #0d6efd;
        color: #0d6efd;
        font-weight: 500;
    }
    .org-menu li a i {
        width: 20px;
        color: #666;
        margin-right: 10px;
    }
    .org-menu li a:hover i {
        color: #0d6efd;
    }
    .org-menu li a.active i {
        color: #0d6efd;
    }
</style>
</head>
<body>
    <!-- User Info -->
<div class="main-container">
    <div class="sidebar">
        <div class="user-info">
            <i class="fas fa-user-circle"></i> Jayadeepa
        </div>
        
        <!-- ORGANIZATION SETTINGS -->
        <div class="org-section">
            <div class="org-header">ORGANIZATION SETTINGS</div>
            <ul class="org-menu">
                <li>
                    <a href="{{ route('settings.organization.index') }}" 
                       class="{{ request()->routeIs('settings.organization.*') ? 'active' : '' }}">
                        <i class="fas fa-building"></i> Organization
                    </a>
                </li>
                <li>
                    <a href="{{ route('settings.users.roles') }}"
                       class="{{ request()->routeIs('settings.users.roles') ? 'active' : '' }}">
                        <i class="fas fa-users"></i> Users & Roles
                    </a>
                </li>
                <li>
                    <a href="{{ route('settings.taxes.compliance') }}"
                       class="{{ request()->routeIs('settings.taxes.compliance') ? 'active' : '' }}">
                        <i class="fas fa-file-invoice"></i> Taxes & Compliance
                    </a>
                </li>
                <li>
                    <a href="{{ route('settings.setup.config') }}"
                       class="{{ request()->routeIs('settings.setup.config') ? 'active' : '' }}">
                        <i class="fas fa-sliders-h"></i> Setup & Configurations
                    </a>
                </li>
                <li>
                    <a href="{{ route('settings.customization.index') }}"
                       class="{{ request()->routeIs('settings.customization.index') ? 'active' : '' }}">
                        <i class="fas fa-paint-brush"></i> Customization
                    </a>
                </li>
                <li>
                    <a href="{{ route('settings.automation.index') }}"
                       class="{{ request()->routeIs('settings.automation.index') ? 'active' : '' }}">
                        <i class="fas fa-robot"></i> Automation
                    </a>
                </li>
            </ul>
        </div>

        <!-- MODULE SETTINGS -->
        <div class="module-section">
            <div class="module-header">MODULE SETTINGS</div>
            <ul class="org-menu">
                <li>
                    <a href="{{ route('setting_handle.create') }}"
                       class="{{ request()->routeIs('settings.general.settings') ? 'active' : '' }}">
                        <i class="fas fa-globe"></i> General
                    </a>
                </li>
                <li>
                    <a href="{{ route('settings.customers.vendors') }}"
                       class="{{ request()->routeIs('settings.customers.vendors') ? 'active' : '' }}">
                        <i class="fas fa-users"></i> Customers and Vendors
                    </a>
                </li>
                <li>
                    <a href="{{ route('setting_handle.create') }}"
                       class="{{ request()->routeIs('setting_handle.create') || request()->routeIs('setting_handle.*') ? 'active' : '' }}">
                        <i class="fas fa-box"></i> Items
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
</body>
</html>