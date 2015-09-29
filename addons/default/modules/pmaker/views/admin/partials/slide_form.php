<?php echo form_open('', 'id="slide_form"'); ?>

    

    <div class="form_inputs" id="pm-slide-form" data-id="<?php echo $id;?>">
        
            <ul>
                <li>
                    <label>Thumbnail:</label>
                    <span><?php echo img(array('src' => site_url('files/thumb/'.$file_id), 'alt' => $name)); ?></span>
                </li>
                <li>
                    <label>Filename:</label>
                    <span><?php echo $filename;?></span>
                </li>
                <li>
                    <label>Name:</label>
                    <span><?php echo $name;?></span>
                </li>
                <li>
                    <label>Size:</label>
                    <span><?php echo $filesize;?> kb</span>
                </li>
                <li>
                    <label for="html">Caption:</label>
                    <span><?php echo form_input('html',$html,'style="width:400px"');?></span>
                </li>
                <li>
                    <label for="showcaption">Show Caption:</label>
                    <span><?php echo form_dropdown('showcaption',array('0'=>'No','1'=>'Yes'),$showcaption)?></span>
                </li>
                <li>
                    <label>Link:</label>
                    <span><?php echo form_input('link',$link);?></span>
                </li>
                <li>
                    <label>Linkable:</label>
                    <span><?php echo form_dropdown('linkable',array('0'=>'No','1'=>'Yes'),$linkable)?></span>
                </li>
            </ul>
        
    </div>
    <div id="instance-actions" class="align-right padding-bottom padding-right buttons buttons-small">
            <?php $this->load->view('admin/partials/buttons', array('buttons' => array('save'))); ?>
    </div>
    <?php echo form_hidden('file_id', $file_id) ?>
<?php echo form_close(); ?>