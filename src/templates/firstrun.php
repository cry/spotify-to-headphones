<?php $this->layout('template', ['title' => 'Initial Setup']) ?>

<style>
	.barrier {
		height: 20px;
	}
</style>

<h1>Initial Setup</h1> <br>

<form action="" method="post" class="form-horizontal">

	<div class="alert alert-danger">
		<strong>S2H does not provide authentication for itself</strong>. It is assumed you will be placing this in a secure VLAN or reverse proxying it.
	</div>

	<fieldset>
		<legend>Headphones Details</legend>
		<p>Provide your Headphones API key so S2H can mark albums as wanted.</p> <br>
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


	