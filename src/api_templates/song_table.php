<?php $this->layout('template', []) ?>

<style>
    .cover {
        height: 150px;
        width: 150px;
    }

    table{
        width:100%;
    }

    .full-width {
        width: 100%;
    }

    a {
        font-size: 16px;
    }
</style>

<table class="table table-striped table-hover ">
    <thead>
        <tr>
            <th>Preview</th>
            <th>Name</th>
            <th>Artist</th>
            <th>Album</th>
            <th>Send album to Headphones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($songs as $song): ?>
            <tr>
                <td><audio controls preload="none"> <source src="<?=$song['preview']?>" type="audio/mpeg"> Your browser does not support the audio element. </audio></td>
                <td><?=$song['name']?></td>
                <td><?=$song['artist']?></td>
                <td><?=$song['album']?></td>
                <td>
                    <button onclick="s2h.downloadAlbum('<?=base64_encode($song['album'])?>', '<?=base64_encode($song['artist'])?>')" class="btn btn-primary full-width">Send</button>
                </td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table> 