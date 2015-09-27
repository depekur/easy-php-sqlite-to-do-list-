<?php 
foreach ($data as $item) {
   $id = $item['id'];
   $title = $item['title'];
   $message = $item['message'];
   $time = $item['time'];
?>

<div class="task">
   
   <h4 class="task-title"><?=$title;?></h4>

   <p class="task-data" > <?=$message;?> </p>

   <hr>
   <i class="task-meta"><?=$time;?></i>
   <span class="task-delete" data-id="<?=$id?>" title="Delete"><i class="fa fa-trash-o"></i></span>      
 
</div>
     
<?php  }  ?>