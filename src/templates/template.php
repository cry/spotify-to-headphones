<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title><?=$this->e($title)?> | Spotify → Headphones</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="stylesheet" href="/assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="/assets/css/toastr.min.css">
	<link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>

	<div class="spinner"></div>

	<nav class="navbar navbar-default navbar-fixed-top">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#">Spotify → Headphones <small> | alpha</small></a>
			</div>

			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-right navbar-nav">
					<li><a href="#">Settings</a></li>
					<li><a href="#">Logs</a></li>
				</ul>
			</div>
		</div>
	</nav>

	<div class="container blocking-margin">
		<div class="row">
			<?=$this->section('content')?>
		</div>
	</div>

	<div class="modal" id="modal">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 id="modal-title"class="modal-title">Modal title</h4>
				</div>
				<div id="modal-body" class="modal-body"></div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<script src="/assets/js/jquery-2.2.4.min.js"></script>
	<script src="/assets/js/bootstrap.min.js"></script>
	<script src="/assets/js/toastr.min.js"></script>
	<script src="/assets/js/s2h.js"></script>
	
</body>
</html>