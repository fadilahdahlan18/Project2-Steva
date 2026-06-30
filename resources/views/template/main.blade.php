<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>STEVA - @yield('title', 'Sistem Informasi')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --burgundy:       #900c3f;
            --burgundy-dark:  #6a0429;
            --burgundy-light: #b01b52;
            --burgundy-pale:  #fdf2f5;
            --gold:           #c9a84c;
            --gold-light:     #f0d080;
            --sidebar-w:      260px;
            --white:          #ffffff;
            --gray-50:        #f8fafc;
            --gray-100:       #f1f5f9;
            --gray-200:       #e2e8f0;
            --gray-300:       #cbd5e1;
            --gray-400:       #94a3b8;
            --gray-500:       #64748b;
            --gray-600:       #475569;
            --gray-700:       #334155;
            --gray-800:       #1e293b;
            --gray-900:       #0f172a;
            --success:        #10b981;
            --warning:        #f59e0b;
            --danger:         #ef4444;
            --info:           #06b6d4;
            --shadow-sm:      0 2px 8px rgba(128, 0, 32, 0.04), 0 1px 3px rgba(0, 0, 0, 0.02);
            --shadow:         0 10px 25px -5px rgba(128, 0, 32, 0.08), 0 4px 12px -2px rgba(0, 0, 0, 0.03);
            --shadow-lg:      0 20px 40px -10px rgba(0, 0, 0, 0.12);
            --radius:         14px;
            --radius-sm:      8px;
            --transition:     0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: radial-gradient(circle at 10% 20%, hsla(345, 100%, 15%, 0.02) 0%, hsla(0, 0%, 100%, 0) 100%), var(--gray-100);
            color: var(--gray-800);
            min-height: 100vh;
            display: flex;
        }

        h1, h2, h3, h4, h5, h6, .brand-logo, .page-title, .sidebar-brand h1 {
            font-family: 'Outfit', sans-serif;
        }

        /* ===================== SIDEBAR ===================== */
        .sidebar {
            width: var(--sidebar-w);
            height: 100vh;
            background: linear-gradient(195deg, var(--burgundy) 0%, var(--burgundy-dark) 100%);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0;
            z-index: 1000;
            box-shadow: 4px 0 25px rgba(0, 0, 0, 0.2);
            transition: transform var(--transition);
        }

        .sidebar-brand {
            padding: 28px 24px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            text-align: center;
        }

        .sidebar-brand .brand-logo {
            width: 60px; height: 60px;
            border-radius: 50%;
            margin: 0 auto 12px;
            box-shadow: 0 4px 20px rgba(201, 168, 76, 0.35);
            object-fit: cover;
            border: 2px solid var(--gold);
            transition: transform var(--transition);
        }

        .sidebar-brand:hover .brand-logo {
            transform: scale(1.05);
        }

        .sidebar-brand h1 {
            color: var(--white);
            font-size: 20px;
            font-weight: 800;
            letter-spacing: 2px;
        }

        .sidebar-brand p {
            color: rgba(255, 255, 255, 0.5);
            font-size: 10px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-top: 3px;
            font-weight: 600;
        }

        .sidebar-user {
            margin: 16px 20px;
            padding: 14px 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: var(--radius-sm);
            text-decoration: none;
            backdrop-filter: blur(10px);
            transition: all var(--transition);
        }

        .sidebar-user:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(255, 255, 255, 0.15);
            transform: translateY(-1px);
        }

        .sidebar-user .avatar {
            width: 40px; height: 40px;
            border-radius: 50%;
            background: var(--gold);
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 16px;
            color: var(--burgundy-dark);
            flex-shrink: 0;
            overflow: hidden;
            border: 1.5px solid rgba(255, 255, 255, 0.2);
        }

        .sidebar-user .avatar img { width: 100%; height: 100%; object-fit: cover; }

        .sidebar-user .user-info { min-width: 0; }
        .sidebar-user .user-name {
            color: var(--white);
            font-size: 13.5px; font-weight: 600;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .sidebar-user .user-role {
            color: var(--gold);
            font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;
            margin-top: 1px;
        }

        .sidebar-nav {
            padding: 8px 0;
            flex: 1;
            overflow-y: auto;
        }

        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.15); border-radius: 4px; }

        .nav-label {
            color: rgba(255, 255, 255, 0.35);
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            padding: 16px 24px 6px;
        }

        .nav-item {
            margin: 2px 16px;
        }

        .nav-item a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 16px;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 500;
            transition: all var(--transition);
            border-radius: var(--radius-sm);
        }

        .nav-item a:hover {
            color: var(--white);
            background: rgba(255, 255, 255, 0.05);
            transform: translateX(2px);
        }

        .nav-item a.active {
            background: linear-gradient(90deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.02) 100%);
            color: var(--white);
            border-left: 3px solid var(--gold);
            font-weight: 600;
            padding-left: 13px;
        }

        .nav-item a .nav-icon {
            width: 20px; text-align: center;
            font-size: 14px;
            flex-shrink: 0;
            transition: transform var(--transition);
        }
        
        .nav-item a:hover .nav-icon {
            transform: scale(1.1);
        }

        .sidebar-footer {
            padding: 12px 16px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }

        .sidebar-footer .logout-btn {
            border-radius: var(--radius-sm);
            transition: all var(--transition);
        }

        .sidebar-footer .logout-btn:hover {
            background: rgba(239, 68, 68, 0.1) !important;
            color: #f87171 !important;
        }

        /* ===================== MAIN CONTENT ===================== */
        .main-wrapper {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .topbar {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            box-shadow: 0 1px 0 rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .topbar-left {
            display: flex; align-items: center; gap: 16px;
        }

        #sidebar-toggle {
            display: none; background: none; border: none; font-size: 20px; color: var(--gray-800); cursor: pointer;
            padding: 8px; border-radius: 50%; transition: background var(--transition);
        }
        #sidebar-toggle:hover { background: var(--gray-200); }

        .topbar .page-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--gray-800);
            letter-spacing: -0.3px;
        }

        .topbar .topbar-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .topbar-user {
            display: flex; align-items: center; gap: 10px; text-decoration: none; color: var(--gray-800);
            padding: 6px 12px; border-radius: 30px; transition: all var(--transition);
        }
        .topbar-user:hover {
            background: var(--gray-200);
        }

        .topbar-user .avatar {
            width: 32px; height: 32px; border-radius: 50%; background: var(--gold);
            display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 13px;
            color: var(--burgundy-dark); overflow: hidden; flex-shrink: 0;
            border: 1.5px solid var(--white);
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        .topbar-user .avatar img { width: 100%; height: 100%; object-fit: cover; }

        .topbar-user .name { font-size: 13px; font-weight: 600; color: var(--gray-700); }
        
        .topbar-divider { width: 1px; height: 20px; background: var(--gray-300); }

        .topbar .btn-logout {
            display: flex; align-items: center; gap: 6px;
            padding: 8px 16px;
            background: rgba(128, 0, 32, 0.06);
            color: var(--burgundy);
            border: 1.5px solid rgba(128, 0, 32, 0.15); border-radius: var(--radius-sm);
            cursor: pointer; font-size: 13px; font-weight: 600;
            text-decoration: none;
            transition: all var(--transition);
        }

        .topbar .btn-logout:hover {
            background: var(--burgundy);
            color: var(--white);
            border-color: var(--burgundy);
            box-shadow: 0 4px 12px rgba(128, 0, 32, 0.25);
        }

        .content-area {
            padding: 28px;
            flex: 1;
        }

        /* ===================== CARDS & COMPONENTS ===================== */
        .card {
            background: var(--white);
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--gray-200);
            overflow: hidden;
            transition: box-shadow var(--transition);
        }
        .card:hover {
            box-shadow: var(--shadow);
        }

        .card-header {
            padding: 18px 24px;
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: linear-gradient(90deg, #ffffff 0%, var(--gray-50) 100%);
        }

        .card-header h3 {
            font-size: 16px;
            font-weight: 700;
            color: var(--gray-800);
            display: flex; align-items: center; gap: 10px;
            letter-spacing: -0.3px;
        }

        .card-header h3 i {
            color: var(--burgundy);
            font-size: 16px;
        }

        .card-body { padding: 24px; }

        /* Stat Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 28px;
        }

        .stats-grid.grid-3 {
            grid-template-columns: repeat(3, 1fr);
        }

        .stats-grid.grid-2 {
            grid-template-columns: repeat(2, 1fr);
        }

        .stat-card {
            background: var(--white);
            border-radius: var(--radius);
            padding: 16px 18px;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--gray-200);
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all var(--transition);
            position: relative;
            overflow: hidden;
            min-width: 0;
        }

        .stat-card:hover {
            box-shadow: var(--shadow);
            transform: translateY(-4px);
            border-color: rgba(128, 0, 32, 0.15);
        }

        .stat-icon {
            width: 48px; height: 48px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
            transition: transform var(--transition);
        }

        .stat-card:hover .stat-icon {
            transform: scale(1.1);
        }

        .stat-icon.burgundy { background: var(--burgundy-pale); color: var(--burgundy); }
        .stat-icon.gold    { background: #fffbeb; color: var(--gold); }
        .stat-icon.success { background: #ecfdf5; color: #059669; }
        .stat-icon.warning { background: #fffbeb; color: #d97706; }
        .stat-icon.info    { background: #ecfeff; color: #0891b2; }
        .stat-icon.danger  { background: #fff5f5; color: #dc2626; }

        .stat-info {
            flex: 1;
            min-width: 0;
        }

        .stat-info .stat-num {
            font-size: 22px;
            font-weight: 800;
            color: var(--gray-900);
            line-height: 1.1;
            font-family: 'Outfit', sans-serif;
            letter-spacing: -0.5px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .stat-info .stat-label {
            font-size: 11px;
            font-weight: 600;
            color: var(--gray-500);
            margin-top: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Tables */
        .table-responsive { overflow-x: auto; border-radius: var(--radius-sm); border: 1px solid var(--gray-200); }

        .steva-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13.5px;
            text-align: left;
        }

        .steva-table thead tr {
            background: var(--burgundy);
            color: var(--white);
        }

        .steva-table thead th {
            padding: 14px 18px;
            font-weight: 700;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            white-space: nowrap;
            border-bottom: 2px solid rgba(0,0,0,0.12);
        }

        .steva-table tbody tr {
            border-bottom: 1px solid var(--gray-200);
            transition: background var(--transition);
        }

        .steva-table tbody tr:last-child {
            border-bottom: none;
        }

        .steva-table tbody tr:hover { background: rgba(128, 0, 32, 0.03); }
        .steva-table tbody td { padding: 14px 18px; vertical-align: middle; color: var(--gray-700); }

        /* Buttons */
        .btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            padding: 9px 18px; border-radius: var(--radius-sm);
            font-size: 13px; font-weight: 600; cursor: pointer;
            border: 1.5px solid transparent; text-decoration: none;
            transition: all var(--transition);
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .btn-sm { padding: 6px 12px; font-size: 12px; }

        .btn-primary   { background: var(--burgundy); color: var(--white); border-color: var(--burgundy); }
        .btn-primary:hover { background: var(--burgundy-dark); border-color: var(--burgundy-dark); box-shadow: 0 4px 12px rgba(128,0,32,0.2); }

        .btn-secondary { background: var(--white); color: var(--gray-700); border-color: var(--gray-300); }
        .btn-secondary:hover { background: var(--gray-50); border-color: var(--gray-400); }

        .btn-success   { background: #10b981; color: var(--white); border-color: #10b981; }
        .btn-success:hover { background: #059669; border-color: #059669; box-shadow: 0 4px 12px rgba(16,185,129,0.2); }

        .btn-danger    { background: #ef4444; color: var(--white); border-color: #ef4444; }
        .btn-danger:hover { background: #dc2626; border-color: #dc2626; box-shadow: 0 4px 12px rgba(239,68,68,0.2); }

        .btn-warning   { background: #f59e0b; color: var(--white); border-color: #f59e0b; }
        .btn-warning:hover { background: #d97706; border-color: #d97706; box-shadow: 0 4px 12px rgba(245,158,11,0.2); }

        .btn-gold      { background: var(--gold); color: #2A0008; border-color: var(--gold); }
        .btn-gold:hover { background: var(--gold-light); border-color: var(--gold-light); box-shadow: 0 4px 12px rgba(201,168,76,0.25); }

        .btn-burgundy  { background: var(--burgundy); color: var(--white); border-color: var(--burgundy); }
        .btn-burgundy:hover { background: var(--burgundy-dark); border-color: var(--burgundy-dark); box-shadow: 0 4px 12px rgba(128,0,32,0.25); }

        /* Forms */
        .form-group { margin-bottom: 20px; }

        .form-label {
            display: block;
            font-size: 13px; font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 8px;
        }

        .form-control {
            width: 100%;
            padding: 11px 15px;
            border: 1.5px solid var(--gray-300);
            border-radius: var(--radius-sm);
            font-size: 14px;
            color: var(--gray-800);
            background: var(--white);
            transition: all var(--transition);
            font-family: 'Plus Jakarta Sans', sans-serif;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.02);
        }

        .form-control::placeholder {
            color: var(--gray-400);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--burgundy);
            box-shadow: 0 0 0 4px hsla(345, 100%, 15%, 0.08);
        }

        .form-control.is-invalid { border-color: var(--danger); }

        .invalid-feedback {
            color: var(--danger);
            font-size: 12px;
            margin-top: 6px;
            display: block;
            font-weight: 500;
        }

        .form-select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2364748b' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            padding-right: 38px;
        }

        /* Alerts */
        .alert {
            padding: 16px 20px;
            border-radius: var(--radius-sm);
            margin-bottom: 24px;
            display: flex; align-items: flex-start; gap: 12px;
            font-size: 14px;
            border: 1px solid transparent;
            font-weight: 500;
        }

        .alert-success { background: #ecfdf5; border-color: #a7f3d0; color: #065f46; }
        .alert-success i { color: #10b981; }
        .alert-danger  { background: #fff5f5; border-color: #feb2b2; color: #9b2c2c; }
        .alert-danger i { color: #ef4444; }
        .alert-warning { background: #fffbeb; border-color: #fde68a; color: #92400e; }
        .alert-warning i { color: #f59e0b; }
        .alert-info    { background: #ecfeff; border-color: #a5f3fc; color: #155e75; }
        .alert-info i { color: #06b6d4; }

        /* Badges */
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 30px;
            font-size: 11px; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.5px;
            border: 1px solid transparent;
        }

        .badge-success  { background: #ecfdf5; color: #047857; border-color: #a7f3d0; }
        .badge-danger   { background: #fff5f5; color: #b91c1c; border-color: #fecaca; }
        .badge-warning  { background: #fffbeb; color: #b45309; border-color: #fde68a; }
        .badge-info     { background: #ecfeff; color: #0369a1; border-color: #bae6fd; }
        .badge-secondary{ background: var(--gray-100); color: var(--gray-600); border-color: var(--gray-200); }
        .badge-burgundy { background: var(--burgundy-pale); color: var(--burgundy); border-color: rgba(128,0,32,0.15); }

        /* Pagination */
        .pagination { display: flex; gap: 6px; list-style: none; margin-top: 20px; }
        .pagination .page-link {
            padding: 8px 14px; border-radius: var(--radius-sm); border: 1px solid var(--gray-300);
            color: var(--burgundy); text-decoration: none; font-size: 13px; font-weight: 600; transition: all var(--transition);
        }
        .pagination .page-item.active .page-link { background: var(--burgundy); color: white; border-color: var(--burgundy); }
        .pagination .page-link:hover { background: var(--burgundy-pale); }

        /* Page breadcrumb */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .page-header h2 {
            font-size: 22px; font-weight: 800;
            color: var(--gray-900);
            display: flex; align-items: center; gap: 10px;
            letter-spacing: -0.5px;
        }

        .page-header h2 i { color: var(--burgundy); }

        .breadcrumb {
            font-size: 12px; color: var(--gray-500); font-weight: 600;
            display: flex; align-items: center; gap: 6px;
        }
        .breadcrumb a { color: var(--burgundy); text-decoration: none; }
        .breadcrumb a:hover { text-decoration: underline; }

        /* Filters bar / Form */
        .filter-form {
            display: flex; flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 20px;
            align-items: center;
        }
        .filter-form .form-group { margin-bottom: 0; }
        .filter-form label { font-size: 11px; }

        /* Grid rows */
        .row { display: flex; flex-wrap: wrap; gap: 20px; }
        .col-6 { flex: 1; min-width: 250px; }
        .col-12 { width: 100%; }

        /* New responsive grids */
        .grid-2col {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
        }

        /* Back Button Style */
        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: var(--white);
            color: var(--gray-700);
            border: 1.5px solid var(--gray-300);
            border-radius: var(--radius-sm);
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            transition: all var(--transition);
            margin-right: 12px;
            box-shadow: var(--shadow-sm);
        }

        .btn-back:hover {
            background: var(--burgundy-pale);
            color: var(--burgundy);
            border-color: var(--burgundy);
            box-shadow: 0 4px 10px rgba(128, 0, 32, 0.15);
        }

        .btn-back i {
            font-size: 12px;
            transition: transform var(--transition);
        }

        .btn-back:hover i {
            transform: translateX(-3px);
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .stats-grid {
                gap: 12px;
            }
            .stat-card {
                padding: 12px 14px;
                gap: 8px;
            }
            .stat-icon {
                width: 40px;
                height: 40px;
                font-size: 16px;
                border-radius: 8px;
            }
            .stat-info .stat-num {
                font-size: 18px;
            }
            .stat-info .stat-label {
                font-size: 10px;
                margin-top: 2px;
            }
        }

        @media (max-width: 992px) {
            .grid-2col {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 16px;
            }
            .stats-grid.grid-3 {
                grid-template-columns: repeat(3, 1fr);
            }
            .stats-grid.grid-2 {
                grid-template-columns: repeat(2, 1fr);
            }
            .stat-card {
                padding: 16px 18px;
                gap: 12px;
            }
            .stat-icon {
                width: 46px;
                height: 46px;
                font-size: 18px;
                border-radius: 10px;
            }
            .stat-info .stat-num {
                font-size: 20px;
            }
            .stat-info .stat-label {
                font-size: 11px;
                margin-top: 4px;
            }
        }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); z-index: 1050; }
            .sidebar.show { transform: translateX(0); }
            .main-wrapper { margin-left: 0; }
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }
            .stats-grid.grid-3 {
                grid-template-columns: 1fr;
            }
            .stats-grid.grid-2 {
                grid-template-columns: repeat(2, 1fr);
            }
            .stat-card {
                padding: 12px 14px;
                gap: 10px;
            }
            .stat-icon {
                width: 40px;
                height: 40px;
                font-size: 16px;
                border-radius: 8px;
            }
            .stat-info .stat-num {
                font-size: 16px;
            }
            .stat-info .stat-label {
                font-size: 10px;
                margin-top: 2px;
            }
            #sidebar-toggle { display: block; }
            .overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.4); backdrop-filter: blur(4px); z-index: 1040; }
            .overlay.show { display: block; }
            .topbar-date { display: none; }
            .topbar-user .name { display: none; }
            .logout-text { display: none; }
            .topbar .btn-logout { padding: 8px 12px; }
            .btn-back span { display: none; }
            .btn-back { padding: 8px 12px; margin-right: 8px; }
            .content-area { padding: 20px; }
            .filter-form {
                flex-direction: column;
                align-items: stretch;
            }
            .filter-form .form-control {
                width: 100%;
                max-width: unset !important;
            }
            .filter-form button {
                width: 100%;
            }
        }

        @media (max-width: 576px) {
            .stats-grid,
            .stats-grid.grid-3,
            .stats-grid.grid-2 {
                grid-template-columns: 1fr;
            }
        }
    </style>
    @yield('styles')
</head>
<body>

<aside class="sidebar">
    <div class="sidebar-brand">
        <img src="{{ asset('images/logo-steva.png') }}" alt="Logo" class="brand-logo">
        <h1>STEVA</h1>
        <p>Sistem Informasi</p>
    </div>

    <a href="{{ route('profile.edit') }}" class="sidebar-user" title="Edit Profil">
        <div class="avatar">
            @if(auth()->user()->foto)
                <img src="{{ auth()->user()->foto_url }}" alt="Foto">
            @else
                {{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}
            @endif
        </div>
        <div class="user-info">
            <div class="user-name">{{ auth()->user()->nama }}</div>
            <div class="user-role">{{ ucfirst(auth()->user()->role) }}</div>
        </div>
    </a>

    <nav class="sidebar-nav">
        @if(auth()->user()->isAdmin())
            @php
                $pendingPelatihCount = \App\Models\User::where('role', 'pelatih')->where('status', 'pending')->count();
                $pendingMuridCount = \App\Models\User::where('role', 'murid')->where('status', 'pending')->count();
            @endphp
            <div class="nav-label">Menu Utama</div>
            <div class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-gauge-high nav-icon"></i> Dashboard
                </a>
            </div>

            <div class="nav-label">Manajemen Data</div>
            <div class="nav-item">
                <a href="{{ route('admin.users', ['role'=>'murid']) }}" class="{{ request()->routeIs('admin.users') && request('role','murid')=='murid' ? 'active' : '' }}">
                    <i class="fa-solid fa-user-graduate nav-icon"></i> Data Murid
                    @if($pendingMuridCount > 0)
                        <span style="background: #ffc107; color: #212529; font-size: 10px; padding: 2px 6px; border-radius: 10px; font-weight: bold; margin-left: auto;">{{ $pendingMuridCount }}</span>
                    @endif
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('admin.users', ['role'=>'pelatih']) }}" class="{{ request()->routeIs('admin.users') && request('role')=='pelatih' ? 'active' : '' }}">
                    <i class="fa-solid fa-chalkboard-user nav-icon"></i> Data Pelatih
                    @if($pendingPelatihCount > 0)
                        <span style="background: #ffc107; color: #212529; font-size: 10px; padding: 2px 6px; border-radius: 10px; font-weight: bold; margin-left: auto;">{{ $pendingPelatihCount }}</span>
                    @endif
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('admin.jadwal') }}" class="{{ request()->routeIs('admin.jadwal*') ? 'active' : '' }}">
                    <i class="fa-solid fa-calendar-days nav-icon"></i> Jadwal Latihan
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('admin.pembayaran') }}" class="{{ request()->routeIs('admin.pembayaran*') ? 'active' : '' }}">
                    <i class="fa-solid fa-money-bill-wave nav-icon"></i> Pembayaran
                </a>
            </div>

            <div class="nav-label">Monitoring</div>
            <div class="nav-item">
                <a href="{{ route('admin.absensi') }}" class="{{ request()->routeIs('admin.absensi*') ? 'active' : '' }}">
                    <i class="fa-solid fa-clipboard-check nav-icon"></i> Absensi
                </a>
            </div>

            <div class="nav-label">Laporan</div>
            <div class="nav-item">
                <a href="{{ route('admin.laporan.monitoring') }}" class="{{ request()->routeIs('admin.laporan.monitoring') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-line nav-icon"></i> Monitoring Laporan
                </a>
            </div>

        @elseif(auth()->user()->isPelatih())
            <div class="nav-label">Menu Utama</div>
            <div class="nav-item">
                <a href="{{ route('pelatih.dashboard') }}" class="{{ request()->routeIs('pelatih.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-gauge-high nav-icon"></i> Dashboard
                </a>
            </div>

            <div class="nav-label">Informasi</div>
            <div class="nav-item">
                <a href="{{ route('pelatih.jadwal') }}" class="{{ request()->routeIs('pelatih.jadwal') ? 'active' : '' }}">
                    <i class="fa-solid fa-calendar-check nav-icon"></i> Monitoring Jadwal
                </a>
            </div>

            <div class="nav-label">Kelola</div>
            <div class="nav-item">
                <a href="{{ route('pelatih.absensi') }}" class="{{ request()->routeIs('pelatih.absensi*') ? 'active' : '' }}">
                    <i class="fa-solid fa-clipboard-check nav-icon"></i> Absensi Murid
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('pelatih.materi') }}" class="{{ request()->routeIs('pelatih.materi*') ? 'active' : '' }}">
                    <i class="fa-solid fa-book-open nav-icon"></i> Materi Latihan
                </a>
            </div>

        @elseif(auth()->user()->isMurid())
            <div class="nav-label">Menu Utama</div>
            <div class="nav-item">
                <a href="{{ route('murid.dashboard') }}" class="{{ request()->routeIs('murid.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-gauge-high nav-icon"></i> Dashboard
                </a>
            </div>

            <div class="nav-label">Informasi</div>
            <div class="nav-item">
                <a href="{{ route('murid.jadwal') }}" class="{{ request()->routeIs('murid.jadwal') ? 'active' : '' }}">
                    <i class="fa-solid fa-calendar-check nav-icon"></i> Monitoring Jadwal
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('murid.materi') }}" class="{{ request()->routeIs('murid.materi') ? 'active' : '' }}">
                    <i class="fa-solid fa-book-open nav-icon"></i> Materi Latihan
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('murid.absensi') }}" class="{{ request()->routeIs('murid.absensi') ? 'active' : '' }}">
                    <i class="fa-solid fa-clipboard-list nav-icon"></i> Riwayat Absensi
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('murid.pembayaran') }}" class="{{ request()->routeIs('murid.pembayaran*') ? 'active' : '' }}">
                    <i class="fa-solid fa-money-bill-wave nav-icon"></i> Pembayaran
                </a>
            </div>
        @endif


    </nav>

    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn" style="width:100%; background:none; border:none; cursor:pointer; text-align:left; color:rgba(255,255,255,0.75); display: flex; align-items: center; gap: 12px; padding: 11px 20px; font-size: 13.5px; font-weight: 500; font-family: 'Inter', sans-serif; transition: all var(--transition);">
                <i class="fa-solid fa-right-from-bracket nav-icon" style="width: 20px; text-align: center; font-size: 14px; flex-shrink: 0;"></i> Keluar
            </button>
        </form>
    </div>
</aside>

<div class="overlay" id="sidebar-overlay"></div>
<div class="main-wrapper">
    <header class="topbar">
        <div class="topbar-left">
            <button id="sidebar-toggle">
                <i class="fa-solid fa-bars"></i>
            </button>
            @if(!request()->routeIs('admin.dashboard') && !request()->routeIs('pelatih.dashboard') && !request()->routeIs('murid.dashboard'))
            <a href="javascript:void(0);" onclick="goBack()" class="btn-back" title="Kembali">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
            @endif
            <span class="page-title">@yield('page-title', 'Dashboard')</span>
        </div>
        <div class="topbar-right">
            <span class="topbar-date" style="font-size:13px; color:var(--gray-500); margin-right: 10px;">
                <i class="fa-regular fa-clock"></i>
                {{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }}
            </span>
            
            <a href="{{ route('profile.edit') }}" class="topbar-user">
                <div class="avatar">
                    @if(auth()->user()->foto)
                        <img src="{{ auth()->user()->foto_url }}" alt="Foto">
                    @else
                        {{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}
                    @endif
                </div>
                <span class="name">{{ explode(' ', auth()->user()->nama)[0] }}</span>
            </a>

            <div class="topbar-divider"></div>

            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                @csrf
                <button type="submit" class="btn-logout" title="Keluar">
                    <i class="fa-solid fa-right-from-bracket"></i> <span class="logout-text">Keluar</span>
                </button>
            </form>
        </div>
    </header>

    <main class="content-area">
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fa-solid fa-circle-check"></i>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">
                <i class="fa-solid fa-circle-xmark"></i>
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>
</div>

@yield('scripts')
<script>
    function goBack() {
        @auth
            @if(auth()->user()->isAdmin())
                window.location.href = "{{ route('admin.dashboard') }}";
            @elseif(auth()->user()->isPelatih())
                window.location.href = "{{ route('pelatih.dashboard') }}";
            @elseif(auth()->user()->isMurid())
                window.location.href = "{{ route('murid.dashboard') }}";
            @else
                window.location.href = "/";
            @endif
        @else
            window.location.href = "/";
        @endauth
    }

    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.querySelector('.sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const toggleBtn = document.getElementById('sidebar-toggle');

        if (toggleBtn) {
            toggleBtn.addEventListener('click', function() {
                sidebar.classList.toggle('show');
                overlay.classList.toggle('show');
            });
        }

        if (overlay) {
            overlay.addEventListener('click', function() {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
            });
        }
    });
</script>
</body>
</html>
