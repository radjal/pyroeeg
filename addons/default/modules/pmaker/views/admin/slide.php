<div class="one_half" id="available-widgets">
	<section class="title">
		<h4><?php echo lang('pm:available_slides_label'); ?></h4>
	</section>
	<section class="item">
            <div class="content">
                <div class="form_inputs" id="pm-folder">
                    <fieldset>
                        <ul>
                            <li class="<?php echo alternator('', 'even'); ?>">
                                    <label for="folder_id"><?php echo lang('pm:select_folder_label'); ?></label>
                                    <?php echo form_dropdown('folder_id', array(lang('global:select-pick')) + $folders_tree, '', 'id="folder_id"'); ?>
                            </li>
                        </ul>
                    </fieldset>
                 </div>
                <div class="form_inputs" id="pm-folder">
                    <h3><?php echo lang('pm:list_image');?></h3>
                    <fieldset>
                        <div id="image_list">

                        </div>
                    </fieldset>
                </div>
            </div>
	</section>
</div>

<div class="one_half last" id="available-widgets">

        <section id="slider_title" class="title" data-id="<?php echo $slider->id;?>">
		<h4><?php echo lang('pm:slider_label')." : ".$slider->title;?></h4>
	</section>
    
	<section class="item">     
            <div class="content">
		<div id="slide-list">
                    <?php if (count($slides)!=0):?>
                        <?php foreach ($slides as $s):?>
                        <div id="slide-<?php echo $s->id; ?>" class="slide">
                            <div class="slide-image">
                                <?php echo img(array('src' => site_url('files/thumb/'.$s->file_id), 'alt' => $s->name, 'des'=>$s->description)); ?>
                                <br/><center><?php echo $s->width." x ".$s->height; ?></center>
                            </div>
                            <div class="slide-des">
                                <p><b><?php echo $s->name;?></b></p>
                                <p><?php echo $s->description;?></p>
                            </div>
                            <div class="slide-actions" data-id="<?php echo $s->id?>" data-file="<?php echo $s->file_id;?>">
                                <a href="<?php echo site_url('admin/pmaker/edit_slide').'/'.$s->id;?>" rel="modal" class="button iframe"><?php echo lang('global:edit'); ?></a>
                                <button type="submit" name="btnAction" value="delete" class="button confirm">
                                        <span><?php echo lang('global:delete'); ?></span>
                                </button>
                                
                            </div>
                            <div style="clear:both"></div>
                            
                        </div>
                        <?php endforeach;?>
                        <div style="clear:both"></div>
                    <?php else:?>
                        <p><?php echo lang('pm:no_slides');?></p>
                    <?php endif;?>
                    <div class="empty-drop"></div>
                </div>
            </div>
	</section>
</div>