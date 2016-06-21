<?php $this->layout('template', ['title' => 'Initial Setup']) ?>

<style>
	.barrier {
		height: 20px;
	}
</style>

<h2>Initial Setup</h2> <br>

<form action="" method="post" class="form-horizontal">

	<div class="alert alert-danger">
		<strong>Ensure that http://<?=$redirect_url?> is listed in authorized redirects in your spotify application page.</strong>
	</div>


	<fieldset>
		<legend>Headphones Details</legend>
		<p>Provide your Headphones API key so S2H can mark albums as wanted.</p> <br>
		<div class="form-group">
			<label for="headphones_host" class="col-md-2 control-label">Headphones Hostname or IP</label>
			<div class="col-md-10">
				<input type="text" class="form-control" name="headphones_host" id="headphones_host" placeholder="https://headphones.host or https://127.0.0.1:8080" value="" autocomplete="off" required>
			</div>
		</div>
		<div class="form-group">
			<label for="headphones" class="col-md-2 control-label">Headphones API Key</label>
			<div class="col-md-10">
				<input type="text" class="form-control" name="headphones_key" id="headphones" placeholder="API Key" value="" autocomplete="off" required>
			</div>
		</div>

		<div class="barrier"></div>

		<legend>Spotify Details</legend>
		<p>Provide your own generated Spotify client ID and secret so S2H can retrieve your playlists.</p> <br>
		<div class="form-group">
			<label for="spotify_client_id" class="col-md-2 control-label">Spotify Client ID</label>
			<div class="col-md-10">
				<input type="text" class="form-control" name="client_id" id="spotify_client_id" placeholder="Client ID" value="" autocomplete="off" required>
			</div>
		</div>
		<div class="form-group">
			<label for="spotify_client_secret" class="col-md-2 control-label">Spotify Client Secret</label>
			<div class="col-md-10">
				<input type="text" class="form-control" name="client_secret" id="spotify_client_secret" placeholder="Client Secret" value="" autocomplete="off" required>
			</div>
		</div>
	</fieldset>

	<div class="barrier"></div>

	<input type="submit" value="Submit" class="btn btn-primary pull-right">

</form>


	