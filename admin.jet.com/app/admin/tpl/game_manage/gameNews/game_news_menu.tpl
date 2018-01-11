<div>
<?php if($this->privilege->checkAuth('game_manage','game','addGame')){ ?>
    &nbsp;<a href="index.php?m=game_manage&c=gameNews&a=addGameNews&game_id=<?php echo $_GET['game_id']; ?>" class="btn btn-primary">新建资讯</a>
<?php } ?>
</div>