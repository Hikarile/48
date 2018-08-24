<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>列車訂票系統</title>
<base href="./" />

<script type="text/javascript" src="{{asset('public/js/jquery-3.3.1.min.js')}}"></script>

</head>
<body class=""><center><h2>
	
	<div id="canvas">
		<div id="header">
			<h1>列車訂票系統</h1>
		</div>
		<div id = "menu">
			<a href = "{{ route('train_select') }}">首頁</a>
			<a href = "{{ route('ticket_booking') }}">預訂車票</a>
			<a href = "{{ route('ticket_select') }}">訂票查詢</a>
			<a href = "{{ route('train_data') }}">列車資訊</a>
			@if (session('login'))
			<br/>
			<a href = "{{ route('type') }}">車種管理</a>
			<a href = "{{ route('station') }}">車站管理</a>
			<a href = "{{ route('train') }}">列車管理</a>
			<a href = "{{ route('ticket') }}">訂票紀錄查詢</a>
			<a href = "{{ route('logout') }}">登出</a>	
			@else
			<a href = "{{ route('login') }}">登入後台</a>
			@endif			
		</diuv><p/>
		<div id="content">
			@section('text')
			@show
		</div>
	</div>
</h2></center></body>
</html>