<?php if(isset($this->data['success'])): ?>
    <?php if(!$this->data['success']): ?>
        <div id="messageContainer" class="warning"><?php echo $this->data['error']; ?></div>
    <?php else: ?>
        <div id="messageContainer" class="success"><?php echo $this->data['message'] ?></div>
    <?php endif ?>
<?php endif ?>