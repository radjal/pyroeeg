<div id="slide-<?php echo $slide[0]->id; ?>" class="slide">    
    <div class="slide-image">
        <?php echo img(array('src' => site_url('files/thumb/' . $slide[0]->file_id), 'alt' => $slide[0]->name, 'des' => $slide[0]->description)); ?>
        <br/><center><?php echo $slide[0]->width." x ".$slide[0]->height; ?></center>
    </div>
    <div class="slide-des">
        <p><b><?php echo $slide[0]->name; ?></b></p>
        <p><?php echo $slide[0]->description; ?></p>
    </div>
    <div class="slide-actions" data-id="<?php echo $slide[0]->id ?>" data-file="<?php echo $slide[0]->file_id; ?>">
        <a href="<?php echo site_url('admin/pmaker/edit_slide/').'/'.$slide[0]->id;?>" rel="modal" class="button iframe">Edit</a>
        <button type="submit" name="btnAction" value="delete" class="button confirm">
                <span>Delete</span>
        </button>
    
    </div>
    <div style="clear:both"></div>
</div>