<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">

<meta name="description" content="SmartHR - An advanced Bootstrap 5 admin dashboard template for HRM and CRM. Ideal for managing employee records, payroll, attendance, recruitment, and team performance with an intuitive and responsive design. Perfect for HR teams and business managers looking to streamline workforce management.">
<meta name="keywords" content="HR dashboard template, HRM admin template, Bootstrap 5 HR dashboard, workforce management dashboard, employee management system, payroll dashboard, HR analytics, admin dashboard, CRM admin template, human resources management, HR admin template, team management dashboard, recruitment dashboard, employee attendance system, performance management, HR CRM, HR dashboard HTML, Bootstrap HR template, employee engagement, HR software, project management dashboard">
<meta name="author" content="Dreams Technologies">
<meta name="robots" content="index, follow">

<!-- Apple Touch Icon -->
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/img/logo/app-logo.png') }}">

<!-- PWA Manifest -->
<link rel="manifest" href="/manifest.json">
<meta name="theme-color" content="#ffffff">

<!-- Favicon -->
<link rel="icon" href="{{ asset('assets/img/logo/app-logo.png') }}" type="image/x-icon" />
<link rel="shortcut icon" href="{{ asset('assets/img/logo/app-logo.png') }}" type="image/x-icon" />

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">

<!-- Feather CSS -->
<link rel="stylesheet" href="{{ asset('assets/plugins/icons/feather/feather.css') }}">

<!-- Tabler Icons -->
<link rel="stylesheet" href="{{ asset('assets/plugins/tabler-icons/tabler-icons.min.css') }}">

<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">

<!-- Font Awesome -->
<link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome/css/fontawesome.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome/css/all.min.css') }}">

<!-- Datetimepicker -->
<link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datetimepicker.min.css') }}">

<!-- Bootstrap Tagsinput -->
<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css') }}">

<!-- Summernote -->
<link rel="stylesheet" href="{{ asset('assets/plugins/summernote/summernote-lite.min.css') }}">

<!-- Daterangepicker -->
<link rel="stylesheet" href="{{ asset('assets/plugins/daterangepicker/daterangepicker.css') }}">

<!-- Color Picker -->
<link rel="stylesheet" href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/@simonwep/pickr/themes/nano.min.css') }}">

<!-- Google Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com/">
<link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@200;300;400;500;600;700;800;900;1000&display=swap" rel="stylesheet">

<!-- Custom App CSS -->
<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/bokanturai.css') }}">

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<!-- Font Awesome CDN -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

@stack('styles')
