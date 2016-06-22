<?php $this->layout('template', ['title' => 'Spotify Authentication Required']) ?>

<h3>Spotify permissions required.</h3>

<p>In order to fetch your playlists, we need to request permission from Spotify.</p>

<p>This page will automatically redirect to the spotify oAuth page.</p>

<p>Click the button below if this page doesn't redirect in 3 seconds.</p>

<button onclick="window.location = '<?=$authorize_url?>';" class="btn btn-primary pull-right">Go to Spotify</button>

<meta http-equiv="refresh" content="3; url=<?=$authorize_url?>">