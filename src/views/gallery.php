<?php if(empty($this->data['images'])): ?>
    <h3>There are no images yet</h3>
<?php else: ?>
    <?php
        $action = $this->data['action'];
        $buttonValue = $action === '/favourites/add' ? 'Add to favourites' : 'Delete from favourites';
        $page = 1;
    ?>

    <form action="<?php echo $action ?>" method="post">
        <?php foreach($this->data['images'] as $image): ?>
            <?php include __DIR__ . "/partials/imageCard.php"; ?>
        <?php endforeach; ?>

        <?php if($this->isUserLoggedIn): ?>
            <label for="favouritesFormSubmit">
                <input type="submit" name="favouritesFormSubmit" id="favouritesFormSubmit" value="<?php echo $buttonValue; ?>"/>
            </label>
        <?php endif; ?>
    </form>

    <?php include_once __DIR__ . "/partials/paging.php"; ?>
<?php endif; ?>
