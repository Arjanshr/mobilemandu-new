<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | Here you can change the default title of your admin panel.
    |
    | For detailed instructions you can look the title section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'title' => 'Mobile Mandu Test',
    'title_prefix' => '',
    'title_postfix' => '',

    /*
    |--------------------------------------------------------------------------
    | Favicon
    |--------------------------------------------------------------------------
    |
    | Here you can activate the favicon.
    |
    | For detailed instructions you can look the favicon section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'use_ico_only' => false,
    'use_full_favicon' => false,

    /*
    |--------------------------------------------------------------------------
    | Google Fonts
    |--------------------------------------------------------------------------
    |
    | Here you can allow or not the use of external google fonts. Disabling the
    | google fonts may be useful if your admin panel internet access is
    | restricted somehow.
    |
    | For detailed instructions you can look the google fonts section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'google_fonts' => [
        'allowed' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Logo
    |--------------------------------------------------------------------------
    |
    | Here you can change the logo of your admin panel.
    |
    | For detailed instructions you can look the logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'logo' => '<b>Mobile</b> Mandu Test',
    'logo_img' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
    'logo_img_class' => 'brand-image img-circle elevation-3',
    'logo_img_xl' => null,
    'logo_img_xl_class' => 'brand-image-xs',
    'logo_img_alt' => 'Admin Logo',

    /*
    |--------------------------------------------------------------------------
    | Authentication Logo
    |--------------------------------------------------------------------------
    |
    | Here you can setup an alternative logo to use on your login and register
    | screens. When disabled, the admin panel logo will be used instead.
    |
    | For detailed instructions you can look the auth logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'auth_logo' => [
        'enabled' => false,
        'img' => [
            'path' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
            'alt' => 'Auth Logo',
            'class' => '',
            'width' => 50,
            'height' => 50,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Preloader Animation
    |--------------------------------------------------------------------------
    |
    | Here you can change the preloader animation configuration. Currently, two
    | modes are supported: 'fullscreen' for a fullscreen preloader animation
    | and 'cwrapper' to attach the preloader animation into the content-wrapper
    | element and avoid overlapping it with the sidebars and the top navbar.
    |
    | For detailed instructions you can look the preloader section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'preloader' => [
        'enabled' => true,
        'mode' => 'fullscreen',
        'img' => [
            'path' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
            'alt' => 'AdminLTE Preloader Image',
            'effect' => 'animation__shake',
            'width' => 60,
            'height' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Menu
    |--------------------------------------------------------------------------
    |
    | Here you can activate and change the user menu.
    |
    | For detailed instructions you can look the user menu section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'usermenu_enabled' => true,
    'usermenu_header' => true,
    'usermenu_header_class' => 'bg-primary',
    'usermenu_image' => true,
    'usermenu_desc' => false,
    'usermenu_profile_url' => true,

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | Here we change the layout of your admin panel.
    |
    | For detailed instructions you can look the layout section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'layout_topnav' => null,
    'layout_boxed' => null,
    'layout_fixed_sidebar' => null,
    'layout_fixed_navbar' => null,
    'layout_fixed_footer' => null,
    'layout_dark_mode' => null,

    /*
    |--------------------------------------------------------------------------
    | Authentication Views Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the authentication views.
    |
    | For detailed instructions you can look the auth classes section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'classes_auth_card' => 'card-outline card-primary',
    'classes_auth_header' => '',
    'classes_auth_body' => '',
    'classes_auth_footer' => '',
    'classes_auth_icon' => '',
    'classes_auth_btn' => 'btn-flat btn-primary',

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the admin panel.
    |
    | For detailed instructions you can look the admin panel classes here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'classes_body' => '',
    'classes_brand' => '',
    'classes_brand_text' => '',
    'classes_content_wrapper' => '',
    'classes_content_header' => '',
    'classes_content' => '',
    'classes_sidebar' => 'sidebar-dark-primary elevation-4',
    'classes_sidebar_nav' => '',
    'classes_topnav' => 'navbar-white navbar-light',
    'classes_topnav_nav' => 'navbar-expand',
    'classes_topnav_container' => 'container',

    /*
    |--------------------------------------------------------------------------
    | Sidebar
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar of the admin panel.
    |
    | For detailed instructions you can look the sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'sidebar_mini' => 'md',
    'sidebar_collapse' => false,
    'sidebar_collapse_auto_size' => false,
    'sidebar_collapse_remember' => true,
    'sidebar_collapse_remember_no_transition' => true,
    'sidebar_scrollbar_theme' => 'os-theme-light',
    'sidebar_scrollbar_auto_hide' => 'l',
    'sidebar_nav_accordion' => true,
    'sidebar_nav_animation_speed' => 300,

    /*
    |--------------------------------------------------------------------------
    | Control Sidebar (Right Sidebar)
    |--------------------------------------------------------------------------
    |
    | Here we can modify the right sidebar aka control sidebar of the admin panel.
    |
    | For detailed instructions you can look the right sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'right_sidebar' => true,
    'right_sidebar_icon' => 'fas fa-list',
    'right_sidebar_theme' => 'dark',
    'right_sidebar_slide' => true,
    'right_sidebar_push' => true,
    'right_sidebar_scrollbar_theme' => 'os-theme-light',
    'right_sidebar_scrollbar_auto_hide' => 'l',

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    |
    | Here we can modify the url settings of the admin panel.
    |
    | For detailed instructions you can look the urls section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'use_route_url' => false,
    'dashboard_url' => 'admin/dashboard',
    'logout_url' => 'logout',
    'login_url' => 'login',
    'register_url' => 'register',
    'password_reset_url' => 'password/reset',
    'password_email_url' => 'password/email',
    'profile_url' => 'user/profile',

    /*
    |--------------------------------------------------------------------------
    | Laravel Mix
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Laravel Mix option for the admin panel.
    |
    | For detailed instructions you can look the laravel mix section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'enabled_laravel_mix' => false,
    'laravel_mix_css_path' => 'css/app.css',
    'laravel_mix_js_path' => 'js/app.js',

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar/top navigation of the admin panel.
    |
    | For detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */
    'menu' => [
        // Navbar items:
        [
            'type'         => 'navbar-search',
            'text'         => 'search',
            'topnav_right' => false,
        ],
        [
            'type'         => 'fullscreen-widget',
            'topnav_right' => false,
        ],

        // Sidebar items:
        [
            'type' => 'sidebar-menu-search',
            'text' => 'search',
        ],

        [
            'header' => 'USERS',
            'key' => 'users',
            'can' => ['browse-users'],
        ],
        [
            'text'    => 'Users',
            'icon'    => 'fas fa-fw fa-users',
            'submenu' => [
                [
                    'icon'    => 'fa fa-arrow-right',
                    'text' => 'Manage Users',
                    'url'  => 'admin/users',
                    'can' => ['browse-users'],
                    'active' => ['users', 'users*', 'regex:@^content/[0-9]+$@'],
                ],
                [
                    'icon'    => 'fa fa-arrow-right',
                    'text' => 'Manage Roles',
                    'url'  => 'admin/roles',
                    'can' => ['browse-roles'],
                    'active' => ['roles', 'roles*', 'regex:@^content/[0-9]+$@'],
                ],
                [
                    'icon'    => 'fa fa-arrow-right',
                    'text' => 'Manage Permissions',
                    'url'  => 'admin/permissions',
                    'can' => ['browse-permissions'],
                    'active' => ['permissions', 'permissions*', 'regex:@^content/[0-9]+$@'],
                ],
            ],
        ],
        [
            'header' => 'CONTENTS',
            'can' => ['browse_featured_products','browse_new_arriavals','browse_popular_products','browse_combo_offers'],
        ],
        [
            'text'    => 'Home Page',
            'icon'    => 'fas fa-money-bill',
            'can' => ['browse-contents'],
            'submenu' => [
                [
                    'icon'    => 'fa fa-arrow-right',
                    'text' => 'Manage Featured Products',
                    'url'  => 'admin/contents/featured-products',
                    'can' => ['browse-contents'],
                    'active' => ['featured-products', 'featured-products*', 'regex:@^content/[0-9]+$@'],
                ],
                [
                    'icon'    => 'fa fa-arrow-right',
                    'text' => 'Manage Popular Products',
                    'url'  => 'admin/contents/popular-products',
                    'can' => ['browse-contents'],
                    'active' => ['popular-products', 'popular-products*', 'regex:@^content/[0-9]+$@'],
                ],
                [
                    'icon'    => 'fa fa-arrow-right',
                    'text' => 'Manage New Arrivals',
                    'url'  => 'admin/contents/new-arriavals',
                    'can' => ['browse-contents'],
                    'active' => ['new-arriavals', 'new-arriavals*', 'regex:@^content/[0-9]+$@'],
                ],
                // [
                //     'icon'    => 'fa fa-arrow-right',
                //     'text' => 'Manage Combo Offers',
                //     'url'  => 'admin/offers/combo-offers',
                //     'can' => ['browse-contents'],
                //     'active' => ['combo-offers', 'combo-offers*', 'regex:@^content/[0-9]+$@'],
                // ],
            ],
        ],
        // [
        //     'header' => 'Parties',
        //     'can' => ['browse-customers'],
        // ],
        // [
        //     'text'    => 'Parties',
        //     'icon'    => 'fas fa-fw fa-users',
        //     'can' => ['browse-customers'],
        //     'submenu' => [
        //         [
        //             'icon'    => 'fa fa-arrow-right',
        //             'text' => 'Manage Customers',
        //             'url'  => 'customers',
        //             'can' => ['browse-customers'],
        //             'active' => ['leads', 'leads*', 'regex:@^content/[0-9]+$@'],
        //         ],
        //         [
        //             'icon'    => 'fa fa-arrow-right',
        //             'text' => 'Manage Suppliers',
        //             'url'  => 'suppliers',
        //             'can' => ['browse-suppliers'],
        //             'active' => ['followups', 'followups*', 'regex:@^content/[0-9]+$@'],
        //         ],

        //     ],
        // ],
        // [
        //     'header' => 'INVENTORY',
        //     'can' => ['browse-products'],
        // ],
        // [
        //     'text'    => 'Product Types',
        //     'icon'    => 'fas fa-list',
        //     'can' => ['browse-types'],
        //     'submenu' => [
        //         [
        //             'icon'    => 'fa fa-arrow-right',
        //             'text' => 'Manage Product Types',
        //             'url'  => 'types',
        //             'can' => ['browse-types'],
        //             'active' => ['types', 'types*', 'regex:@^content/[0-9]+$@'],
        //         ],
        //     ],
        // ],
        // [
        //     'text'    => 'Markas',
        //     'icon'    => 'fas fa-list',
        //     'can' => ['browse-brands'],
        //     'submenu' => [
        //         [
        //             'icon'    => 'fa fa-arrow-right',
        //             'text' => 'Manage Markas',
        //             'url'  => 'brands',
        //             'can' => ['browse-brands'],
        //             'active' => ['brands', 'brands*', 'regex:@^content/[0-9]+$@'],
        //         ],
        //     ],
        // ],
        // [
        //     'text'    => 'Products',
        //     'icon'    => 'fas fa-shopping-cart',
        //     'can' => ['browse-products'],
        //     'submenu' => [
        //         [
        //             'icon'    => 'fa fa-arrow-right',
        //             'text' => 'Manage Products',
        //             'url'  => 'products',
        //             'can' => ['browse-products'],
        //             'active' => ['products', 'products*', 'regex:@^content/[0-9]+$@'],
        //         ],
        //     ],
        // ],
        // [
        //     'text'    => 'Warehouses',
        //     'icon'    => 'fa fa-home',
        //     'can' => ['browse-warehouses'],
        //     'submenu' => [
        //         [
        //             'icon'    => 'fa fa-arrow-right',
        //             'text' => 'Manage Warehouses',
        //             'url'  => 'warehouses',
        //             'can' => ['browse-warehouses'],
        //             'active' => ['warehouses', 'warehouses*', 'regex:@^content/[0-9]+$@'],
        //         ],
        //     ],
        // ],
        // [
        //     'header' => 'Purchase/Sales',
        //     'can' => ['browse-products'],
        // ],
        // [
        //     'text'    => 'Purchase/Sales',
        //     'icon'    => 'fa fa-share-alt',
        //     'can' => ['browse-purchases','browse-sells'],
        //     'submenu' => [
        //         [
        //             'icon'    => 'fa fa-arrow-right',
        //             'text' => 'Manage Purchses',
        //             'url'  => 'purchases',
        //             'can' => ['browse-purchases'],
        //             'active' => ['purchases', 'purchases*', 'regex:@^content/[0-9]+$@'],
        //         ],
        //         [
        //             'icon'    => 'fa fa-arrow-right',
        //             'text' => 'Manage Sales',
        //             'url'  => 'sales',
        //             'can' => ['browse-sells'],
        //             'active' => ['sells', 'sells*', 'regex:@^content/[0-9]+$@'],
        //         ],
        //         [
        //             'icon'    => 'fa fa-arrow-right',
        //             'text' => 'Manage Challans',
        //             'url'  => 'challans',
        //             'can' => ['browse-challans'],
        //             'active' => ['challans', 'challans*', 'regex:@^content/[0-9]+$@'],
        //         ],
        //     ],
        // ],
        // [
        //     'header' => 'REPORTS',
        //     'can' => ['browse-reports'],
        // ],
        // [
        //     'text'    => 'Reports',
        //     'icon'    => 'fas fa-shopping-cart',
        //     'can' => ['browse-reports'],
        //     'submenu' => [
        //         [
        //             'icon'    => 'fa fa-arrow-right',
        //             'text' => 'View Summary',
        //             'url'  => 'summary',
        //             'can' => ['browse-reports'],
        //         ],
        //         [
        //             'icon'    => 'fa fa-arrow-right',
        //             'text' => 'View Daily Summary',
        //             'url'  => 'daily-summary',
        //             'can' => ['browse-reports'],
        //         ],
        //         [
        //             'icon'    => 'fa fa-arrow-right',
        //             'text' => 'View Stock Reports',
        //             'url'  => 'stock-report',
        //             'can' => ['browse-reports'],
        //         ],
        //         [
        //             'icon'    => 'fa fa-arrow-right',
        //             'text' => 'View Warehouse Reports',
        //             'url'  => 'warehouse-report',
        //             'can' => ['browse-reports'],
        //         ],
        //         [
        //             'icon'    => 'fa fa-arrow-right',
        //             'text' => 'View Sales Reports',
        //             'url'  => 'sales-report',
        //             'can' => ['browse-reports'],
        //         ],
        //         [
        //             'icon'    => 'fa fa-arrow-right',
        //             'text' => 'View Sale Items Reports',
        //             'url'  => 'sale-items-report',
        //             'can' => ['browse-reports'],
        //         ],
        //         [
        //             'icon'    => 'fa fa-arrow-right',
        //             'text' => 'View Expense Reports',
        //             'url'  => 'expense-report',
        //             'can' => ['browse-reports'],
        //         ],
        //         // [
        //         //     'icon'    => 'fa fa-arrow-right',
        //         //     'text' => 'View Customer List',
        //         //     'url'  => 'customer-list',
        //         //     'can' => ['browse-reports'],
        //         // ],
        //         // [
        //         //     'icon'    => 'fa fa-arrow-right',
        //         //     'text' => 'View Supplier List',
        //         //     'url'  => 'supplier-list',
        //         //     'can' => ['browse-reports'],
        //         // ],
        //         // [
        //         //     'icon'    => 'fa fa-arrow-right',
        //         //     'text' => 'View Staff Reports',
        //         //     'url'  => 'staff-report',
        //         //     'can' => ['browse-reports'],
        //         // ],
        //         // [
        //         //     'icon'    => 'fa fa-arrow-right',
        //         //     'text' => 'View Supplier Report',
        //         //     'url'  => 'supplier-report',
        //         //     'can' => ['browse-reports'],
        //         // ],
        //         // [
        //         //     'icon'    => 'fa fa-arrow-right',
        //         //     'text' => 'View Customer Report',
        //         //     'url'  => 'customer-report',
        //         //     'can' => ['browse-reports'],
        //         // ],
        //         // [
        //         //     'icon'    => 'fa fa-arrow-right',
        //         //     'text' => 'View Staff Sales Report',
        //         //     'url'  => 'staff-sales-report',
        //         //     'can' => ['browse-reports'],
        //         // ],
        //     ],
        // ],
        [
            'header' => 'CATEGORIES',
            'can' => ['browse-categories'],
        ],
        [
            'text'    => 'Categories',
            'icon'    => 'fas fa-fw fa-list-alt',
            'can' => ['browse-categories'],
            'submenu' => [
                [
                    'icon'    => 'fa fa-arrow-right',
                    'text' => 'Manage Categories',
                    'url'  => 'admin/categories',
                    'can' => ['browse-categories'],
                    'active' => ['categories', 'categories*', 'regex:@^content/[0-9]+$@'],
                ],
            ],
        ],
        [
            'header' => 'Brands',
            'can' => ['browse-brands'],
        ],
        [
            'text'    => 'Brands',
            'icon'    => 'fas fa-fw fa-list-alt',
            'can' => ['browse-brands'],
            'submenu' => [
                [
                    'icon'    => 'fa fa-arrow-right',
                    'text' => 'Manage Brands',
                    'url'  => 'admin/brands',
                    'can' => ['browse-brands'],
                    'active' => ['brands', 'brands*', 'regex:@^content/[0-9]+$@'],
                ],
            ],
        ],
        [
            'header' => 'PRODUCTS',
            'can' => ['browse-products'],
        ],
        [
            'text'    => 'Products',
            'icon'    => 'fas fa-fw fa-list-alt',
            'can' => ['browse-products'],
            'submenu' => [
                [
                    'icon'    => 'fa fa-arrow-right',
                    'text' => 'Manage Products',
                    'url'  => 'admin/products',
                    'can' => ['browse-products'],
                    'active' => ['products', 'products*', 'regex:@^content/[0-9]+$@'],
                ],
            ],
        ],
        ['header' => 'account_settings'],
        [
            'text' => 'profile',
            'url'  => 'user/profile',
            'icon' => 'fas fa-fw fa-user',
        ],
        [
            'header' => 'SETTINGS',
            'can' => ['browse-general-settings'],
        ],
        [
            'text'    => 'Settings',
            'icon'    => 'fas fa-fw fa-cog',
            'can' => 'browse-settings',
            'submenu' => [
                [
                    'can' => 'browse-settings',
                    'text' => 'Manage setting form',
                    'url'  => 'admin/settings',
                    'icon'    => 'fa fa-arrow-right',
                    // 'active' => ['settings', 'settings*', 'regex:@^content/[0-9]+$@'],
                ],
            ],
        ],
        [
            'can' => 'browse-general-settings',
            'text' => 'General settings',
            'url'  => 'admin/settings/general',
            'icon' => 'fas fa-fw fa-cog',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Here we can modify the menu filters of the admin panel.
    |
    | For detailed instructions you can look the menu filters section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SearchFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\DataFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    |
    | Here we can modify the plugins used inside the admin panel.
    |
    | For detailed instructions you can look the plugins section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Plugins-Configuration
    |
    */

    'plugins' => [
        'IconsIcons' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css',
                ],
            ],
        ],
        'Sparkline' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'plugins/sparklines/sparkline.js',
                ],
            ],
        ],
        'JQVmap' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'plugins/jqvmap/jqvmap.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'plugins/jqvmap/jquery.vmap.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'plugins/jqvmap/maps/jquery.vmap.usa.js',
                ],
            ],
        ],
        'icheck' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'plugins/icheck-bootstrap/icheck-bootstrap.min.css',
                ],
            ],
        ],
        'JqueryUI' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'plugins/jquery-ui/jquery-ui.min.js',
                ],
            ],
        ],
        'Datatables' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => '//cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => '//cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css',
                ],
            ],
        ],
        'Date Picker' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'plugins/daterangepicker/daterangepicker.css',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'plugins/moment/moment.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'plugins/daterangepicker/daterangepicker.js',
                ],

            ],
        ],
        'Tempusdominus' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js',
                ],
            ],
        ],
        'Jquery Knobchart' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'plugins/jquery-knob/jquery.knob.min.js',
                ],
            ],
        ],
        'Summernote' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'plugins/summernote/summernote-bs4.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'plugins/summernote/summernote-bs4.min.js',
                ],
            ],
        ],
        'Select2' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css',
                ],
            ],
        ],
        'Chartjs' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.bundle.min.js',
                ],
            ],
        ],
        'Sweetalert2' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.jsdelivr.net/npm/sweetalert2@11',
                ],
            ],
        ],
        'Pace' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/blue/pace-theme-center-radar.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js',
                ],
            ],
        ],
        'Dashboard' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'dist/js/pages/dashboard.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'dist/js/demo.js',
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | IFrame
    |--------------------------------------------------------------------------
    |
    | Here we change the IFrame mode configuration. Note these changes will
    | only apply to the view that extends and enable the IFrame mode.
    |
    | For detailed instructions you can look the iframe mode section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/IFrame-Mode-Configuration
    |
    */

    'iframe' => [
        'default_tab' => [
            'url' => null,
            'title' => null,
        ],
        'buttons' => [
            'close' => true,
            'close_all' => true,
            'close_all_other' => true,
            'scroll_left' => true,
            'scroll_right' => true,
            'fullscreen' => true,
        ],
        'options' => [
            'loading_screen' => 1000,
            'auto_show_new_tab' => true,
            'use_navbar_items' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Livewire support.
    |
    | For detailed instructions you can look the livewire here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'livewire' => false,
];