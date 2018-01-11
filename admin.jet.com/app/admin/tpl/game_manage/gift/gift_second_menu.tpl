<div>
<?php if($this->privilege->checkAuth('game_manage','gift','addGift')){ ?>
    &nbsp;<a href="index.php?m=game_manage&c=gift&a=addGift&type=normal&game_id=<?php echo $_GET['game_id']; ?>" class="btn btn-primary">新建普通礼包 (普通、QQ群、统一码)</a>&nbsp;&nbsp;&nbsp;
<?php } ?>
<?php if($this->privilege->checkAuth('game_manage','gift','addGift')){ ?>
    &nbsp;<a href="index.php?m=game_manage&c=gift&a=addGift&type=point&game_id=<?php echo $_GET['game_id']; ?>" class="btn btn-primary">新建积分礼包</a>&nbsp;&nbsp;&nbsp;
<?php } ?>
<?php if($this->privilege->checkAuth('game_manage','gift','addGift')){ ?>
    &nbsp;<a href="index.php?m=game_manage&c=gift&a=addGift&type=vip&game_id=<?php echo $_GET['game_id']; ?>" class="btn btn-primary">新建VIP礼包</a>&nbsp;&nbsp;&nbsp;
<?php } ?>
</div>