<form action="/user/login" method="post">
    <label for="usernameInput">
        <span class="textSpan">Username:</span>
        <input type="text" id="usernameInput" name="username" maxlength="30">
    </label>

    <label for="passwordInput">
        <span class="textSpan">Password:</span>
        <input type="password" id="passwordInput" name="password" maxlength="25">
    </label>

    <div id="formButtons">
        <input type="submit" name="submit" value="Login">
        <input type="reset" name="reset" value="Clear">
    </div>
</form>

