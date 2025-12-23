<form action="/gallery/add" method="post" enctype="multipart/form-data">
    <label for="fileInput">
        <span class="textSpan">Image:</span>
        <input type="file" name="file" id="file" required/>
    </label>

    <?php if($this->isUserLoggedIn): ?>
        <div id="privacyRadioContainer">
            <label for="privateRadio">
                <span>Private</span>
                <span><input type="radio" name="privacy" id="privateRadio" value="true"/></span>
            </label>
            <label for="publicRadio">
                <span>Public</span>
                <span><input type="radio" name="privacy" id="publicRadio" value="false" checked/></span>
            </label>
        </div>
    <?php endif; ?>

    <label for="author">
        <span class="textSpan">Author: </span>
        <input type="text" name="author" id="author" value="<?php echo isset($_SESSION['user']) ? $_SESSION['user']['username'] : ""; ?>" required/>
    </label>

    <label for="title">
        <span class="textSpan">Title: </span>
        <input type="text" name="title" id="title" required/>
    </label>

    <label for="watermark">
        <span class="textSpan">Watermark: </span>
        <input type="text" name="watermark" id="watermark" required/>
    </label>

    <div id="formButtons">
        <input type="submit" name="submit" value="Submit">
        <input type="reset" name="reset" value="Clear">
    </div>
</form>
