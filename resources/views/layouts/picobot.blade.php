<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PICOBOT')</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            min-height: 100vh;
            background: #f3f7fb;
            color: #19344d;
        }

        .navbar {
            min-height: 75px;
            background: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 5%;
            box-shadow: 0 2px 15px rgba(0,0,0,.05);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: inherit;
        }

        .brand-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: #087e8b;
            color: white;
            display: grid;
            place-items: center;
            font-size: 20px;
        }

        .brand h2 {
            font-size: 20px;
            font-weight: 800;
            color: #123c5a;
        }

        .brand small {
            display: block;
            color: #8193a5;
            font-size: 10px;
            letter-spacing: 1px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-name {
            text-align: right;
            font-size: 13px;
            font-weight: 700;
        }

        .user-name small {
            display: block;
            color: #078b88;
            font-size: 11px;
            margin-top: 4px;
        }

        .logout-button {
            border: none;
            border-radius: 8px;
            padding: 10px 17px;
            background: #fff0f0;
            color: #dc4545;
            font-weight: 600;
            cursor: pointer;
            font-size: 12px;
        }

        .logout-button:hover {
            background: #dc4545;
            color: white;
        }

        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 25px;
        }

        .welcome {
            background: linear-gradient(110deg, #0c3155, #087e8b);
            color: white;
            padding: 30px 35px;
            border-radius: 18px;
            margin-bottom: 25px;
            box-shadow: 0 12px 30px rgba(8,126,139,.15);
        }

        .welcome h1 {
            font-size: 25px;
            margin-bottom: 9px;
        }

        .welcome p {
            color: #d5eeee;
            font-size: 13px;
            line-height: 1.7;
        }

        .breadcrumb {
            display: flex;
            gap: 9px;
            align-items: center;
            font-size: 12px;
            color: #8999a9;
            margin-bottom: 22px;
            flex-wrap: wrap;
        }

        .breadcrumb a {
            color: #078b88;
            text-decoration: none;
        }

        .section-title {
            font-size: 18px;
            margin-bottom: 17px;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
            margin-bottom: 28px;
        }

        .stat-card {
            background: white;
            padding: 21px;
            border-radius: 14px;
            border: 1px solid #edf1f5;
            box-shadow: 0 5px 20px rgba(0,0,0,.035);
        }

        .stat-card small {
            color: #8493a5;
            font-size: 12px;
        }

        .stat-card h2 {
            color: #123c5a;
            font-size: 27px;
            margin-top: 10px;
        }

        .content-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,.04);
            border: 1px solid #edf1f5;
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            flex-wrap: wrap;
            margin-bottom: 22px;
        }

        .card-header h3 {
            font-size: 16px;
        }

        .card-header p {
            color: #8493a5;
            font-size: 12px;
            margin-top: 6px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            border: none;
            border-radius: 8px;
            padding: 10px 13px;
            font-size: 11px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: .2s;
        }

        .btn-primary {
            background: #087e8b;
            color: white;
            padding: 12px 17px;
            font-size: 12px;
        }

        .btn-primary:hover {
            background: #066b76;
        }

        .btn-cancel {
            background: #edf1f5;
            color: #647b8e;
            padding: 12px 19px;
            font-size: 12px;
        }

        .btn-cancel:hover {
            background: #dfe6ec;
        }

        .btn-submit {
            background: #087e8b;
            color: white;
            padding: 12px 19px;
            font-size: 12px;
        }

        .btn-submit:hover {
            background: #066b76;
        }

        .btn-detail {
            background: #e8f3fa;
            color: #28749b;
        }

        .btn-edit {
            background: #fff4df;
            color: #a87415;
        }

        .btn-delete {
            background: #fff0f0;
            color: #dc4545;
        }

        .btn-detail:hover,
        .btn-edit:hover,
        .btn-delete:hover {
            filter: brightness(.96);
        }

        .toolbar {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .search-box {
            width: 340px;
            max-width: 100%;
            padding: 11px 14px;
            border: 1px solid #e1e8ef;
            border-radius: 8px;
            font-size: 12px;
            outline: none;
        }

        .search-box:focus {
            border-color: #087e8b;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            min-width: 850px;
            border-collapse: collapse;
        }

        thead {
            background: #f3f7fb;
        }

        th {
            text-align: left;
            padding: 14px 12px;
            color: #647b8e;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            white-space: nowrap;
        }

        td {
            padding: 15px 12px;
            border-bottom: 1px solid #edf1f5;
            font-size: 12px;
            vertical-align: top;
        }

        tbody tr:hover {
            background: #f8fbfd;
        }

        .badge {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 600;
            white-space: nowrap;
        }

        .badge-ringan {
            background: #e5f8ef;
            color: #218653;
        }

        .badge-sedang {
            background: #fff4df;
            color: #a87415;
        }

        .badge-berat {
            background: #fff0f0;
            color: #dc4545;
        }

        .badge-neutral {
            background: #e5f8f6;
            color: #078b88;
        }

        .actions {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }

        .alert {
            padding: 13px 16px;
            border-radius: 9px;
            margin-bottom: 20px;
            font-size: 12px;
        }

        .alert-success {
            background: #e5f8ef;
            color: #218653;
        }

        .alert-error {
            background: #fff0f0;
            color: #dc4545;
        }

        .empty {
            text-align: center;
            padding: 40px 15px;
            color: #8493a5;
            line-height: 1.8;
        }

        .pagination {
            margin-top: 20px;
            font-size: 12px;
        }

        .form-card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,.04);
            border: 1px solid #edf1f5;
        }

        .form-header {
            margin-bottom: 25px;
            padding-bottom: 18px;
            border-bottom: 1px solid #edf1f5;
        }

        .form-header h2 {
            font-size: 18px;
            margin-bottom: 7px;
        }

        .form-header p {
            font-size: 12px;
            color: #8493a5;
            line-height: 1.6;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 22px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 9px;
        }

        .form-group.full {
            grid-column: 1 / -1;
        }

        .form-group label {
            font-size: 12px;
            font-weight: 600;
            color: #425a70;
        }

        .required {
            color: #dc4545;
        }

        .form-control {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #dfe7ee;
            border-radius: 8px;
            outline: none;
            color: #19344d;
            background: white;
            font-size: 12px;
            transition: .2s;
        }

        .form-control:focus {
            border-color: #087e8b;
            box-shadow: 0 0 0 3px rgba(8,126,139,.08);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }

        .form-control.is-invalid {
            border-color: #dc4545;
        }

        .error-message {
            color: #dc4545;
            font-size: 11px;
        }

        .form-note {
            margin-top: 22px;
            padding: 13px 15px;
            background: #eaf7f7;
            border-radius: 8px;
            color: #347d80;
            font-size: 11px;
            line-height: 1.7;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 28px;
            padding-top: 20px;
            border-top: 1px solid #edf1f5;
            flex-wrap: wrap;
        }

        .detail-card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,.04);
            border: 1px solid #edf1f5;
        }

        .detail-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
            padding-bottom: 20px;
            border-bottom: 1px solid #edf1f5;
            margin-bottom: 25px;
        }

        .detail-header h2 {
            font-size: 18px;
            margin-bottom: 6px;
        }

        .detail-header p {
            color: #8493a5;
            font-size: 12px;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 22px;
        }

        .detail-item {
            padding: 17px;
            background: #f8fafc;
            border: 1px solid #edf1f5;
            border-radius: 10px;
        }

        .detail-item.full {
            grid-column: 1 / -1;
        }

        .detail-item small {
            display: block;
            color: #8493a5;
            font-size: 11px;
            margin-bottom: 9px;
        }

        .detail-item h3 {
            font-size: 13px;
            font-weight: 600;
            color: #19344d;
            overflow-wrap: anywhere;
            line-height: 1.6;
        }

        .footer {
            margin-top: 45px;
            padding-bottom: 20px;
            text-align: center;
            color: #9aa7b5;
            font-size: 11px;
        }

        @media(max-width: 700px) {
            .navbar {
                padding: 12px 18px;
            }

            .user-name {
                display: none;
            }

            .container {
                margin-top: 25px;
                padding: 0 15px;
            }

            .welcome {
                padding: 25px;
            }

            .welcome h1 {
                font-size: 21px;
            }

            .stats {
                grid-template-columns: 1fr;
            }

            .content-card,
            .form-card,
            .detail-card {
                padding: 20px;
            }

            .form-grid,
            .detail-grid {
                grid-template-columns: 1fr;
            }

            .form-group.full,
            .detail-item.full {
                grid-column: auto;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

<nav class="navbar">
    <a href="{{ route('dashboard') }}" class="brand">
        <div class="brand-icon">
            <i class="fa-solid fa-heart-pulse"></i>
        </div>
        <div>
            <h2>PICOBOT</h2>
            <small>REHABILITATION SYSTEM</small>
        </div>
    </a>

    <div class="user-info">
        <div class="user-name">
            {{ Auth::user()->name }}
            <small>{{ strtoupper(Auth::user()->role) }}</small>
        </div>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="logout-button">Keluar</button>
        </form>
    </div>
</nav>

<main class="container">
    @yield('content')

    <div class="footer">
        &copy; {{ date('Y') }} PICOBOT - RSUD Dr. Soetomo Surabaya
    </div>
</main>

@stack('scripts')

</body>
</html>
