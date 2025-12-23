<?php $pageAction = $this->data['page_action']; ?>

<?php if($this->data['pages'] > 1): ?>
<div id="pagingContainer">
    <ul>
        <?php for($page = 1; $page <= $this->data['pages']; $page++): ?>
            <li><a href="<?php echo $pageAction . "?page=$page"; ?>"><?php echo $page; ?></a></li>
        <?php endfor; ?>
    </ul>
</div>
<?php endif; ?>
