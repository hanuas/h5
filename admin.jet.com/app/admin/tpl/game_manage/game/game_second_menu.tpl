<div>
<?php if($this->privilege->checkAuth('game_manage','game','addGame')){ ?>
    &nbsp;<a href="index.php?m=game_manage&c=game&a=addGame" class="btn btn-primary">新建游戏</a>
<?php } ?>
</div>