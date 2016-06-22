<?php $this->layout('template', ['title' => 'Index']) ?>

<style>
	.cover {
		height: 150px;
		width: 150px;
	}

	table{
	    width:100%;
	}

	a {
		font-size: 16px;
	}
</style>

<h3>Your Spotify Playlists</h3>
<p class="text-muted">Due to limitations in the Headphones API, the entire album is sent to Headphones.</p><br>

<table class="table table-striped table-hover">
	<thead>
		<tr>
			<th>Image</th>
			<th>Name</th>
			<th>Owner</th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($playlists as $playlist): ?>
			<tr>
				<td><img src="<?=$playlist['image']?>" class="cover"></td>
				<td><a href="<?=$playlist['uri']?>"><?=$playlist['name']?></a></td>
				<td><a href="<?=$playlist['owner_uri']?>"><?=$playlist['owner']?></a></td>
				<td>
					<button onclick="s2h.showSongs('<?=base64_encode($playlist['id'])?>', '<?=base64_encode($playlist['name'])?>', '<?=base64_encode($playlist['owner_id'])?>')" class="btn btn-info">View song list</button>
					<button class="btn btn-primary" disabled>Send all to Headphones</button>
				</td>
				<!--<?=$playlist['id']?>-->
			</tr>
		<?php endforeach ?>
	</tbody>
</table> 