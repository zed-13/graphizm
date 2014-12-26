<?php if (!empty($gallery_name)): ?>
<h2 class="gcaption" id="<?php echo $name_id; ?>">
    <?php echo $gallery_name; ?>
    <span style='float:right;'>
        <a href="#" style="text-decoration:none;" title="<?php echo $title; ?>">&spades;</a>
    </span>
</h2>
<?php endif;?>
<div id="<?php echo $div_id; ?>" class="gallery">
    <div class="lecentreur">
        <?php echo $a_gallery_code; ?>
    </div>
</div>
<div style="clear:both;"></div>
