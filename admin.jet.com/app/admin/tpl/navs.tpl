<ol class="breadcrumb">
  <?php
    foreach($navs as $k=>$v){
  ?>
  <li><a href="<?php echo $v['url']; ?>"><?php echo $v['title']; ?></a></li>
  <?php } ?>
   <li class="active"><?php echo $this->val('title'); ?></li>
</ol>