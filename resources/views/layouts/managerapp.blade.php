<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- CSRF Token -->
		<meta name="csrf-token" content="{{ csrf_token() }}">

		<title>@yield('title')</title>

		<!-- Styles -->
		<link href="{{ asset('css/app.css') }}" rel="stylesheet">

		<style>
			.bar{
				border-bottom: solid 1px #ccc;
			}
			.content{
				margin: 8px auto;
				background-color: ghostwhite;
			}
			.footer{
				border-top: solid 1px #ccc;
				text-align: right;
				padding-bottom: 16px;
			}

			.flex{
				display: flex;
			}
			.right{
				margin-left: auto;
			}
		</style>
	</head>

	<body>
		<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
			<div class="container-fluid">
				<a href="{{url('/management')}}" class="navbar-brand">ロゴ</a>

				<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
					<span class="navbar-toggler-icon"></span>
				</button>

				<div class="collapse navbar-collapse" id="navbarSupportedContent">
					<!-- Left Side Of Navbar -->
					<ul class="navbar-nav">
						<li><a href="{{ route('mgmt.dept') }}" class="nav-link">申請一覧</a></li>
						<li><a href="{{ route('mgmt.dept') }}" class="nav-link">社員一覧</a></li>
						<li><a href="{{ route('mgmt.master') }}" class="nav-link">基本情報</a></li>
						<li><a href="{{ route('mgmt.dept') }}" class="nav-link">勤怠入力</a></li>
					</ul>
				</div>
			</div>
		</nav>

		<div class="content">
			@yield('content')
		</div>
		
		<div class="footer">
			@yield('footer')
		</div>
	</body>
</html>