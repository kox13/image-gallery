<div class='image-card'>
    <a href="/<?php echo $image['watermark_path']; ?>" target="_blank" class="image-link">
        <img src="/<?php echo $image['thumbnail_path']; ?>" alt="<?php echo $image['title'] ?>"/>
    </a>
    <span class='image-title'><?php echo $image['title']; ?></span>
    <span class='image-author'><?php echo $image['author']; ?></span>
    <span class='image-private'>
        <?php if($image['private']): ?>
            PRIVATE
        <?php endif ?>
    </span>
    <?php if($this->isUserLoggedIn): ?>
        <?php if(!$image['favourite']): ?>
            <input type='checkbox' name='favourites[]' class='image-checkbox' value="<?php echo $image['id']; ?>"/>
        <?php else: ?>
            <input type='checkbox' name='favourites[]' class='image-checkbox' value="<?php echo $image['id']; ?>" checked disabled/>
        <?php endif; ?>
    <?php endif ?>
</div>