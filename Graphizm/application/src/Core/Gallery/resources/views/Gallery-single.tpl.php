<?php if (!empty($gallery_name)): ?>
<h2 class="gcaption navbar navbar-inverse" id="<?php echo $name_id; ?>">
    <span class="gallery-title">
        <?php echo $gallery_name; ?>
    </span>
    <span style='float:right;'>
        <a href="#" data-toggle="tooltip" class="up" title="<?php echo $title; ?>">&spades;</a>
    </span>
</h2>
<?php endif;?>
<div id="<?php echo $div_id; ?>" class="gallery">
    <div class="lecentreur">
        <?php echo $a_gallery_code; ?>
    </div>
</div>
<div style="clear:both;"></div>
