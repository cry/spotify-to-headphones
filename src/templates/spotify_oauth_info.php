<?php $this->layout('template', ['title' => 'Spotify Authentication Required']) ?>

<h3>Spotify permissions required.</h3>

<p>In order to fetch your playlists, we need to request permission from Spotify.</p>

<p>This page will automatically redirect to the spotify oAuth page.</p>

<meta http-equiv="refresh" content="3; url=<?=$authorize_url?>">