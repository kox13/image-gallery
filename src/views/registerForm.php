<form action="/user/register" method="post">
    <label for="usernameInput">
        <span class="textSpan">Username:</span>
        <input type="text" id="usernameInput" name="username" maxlength="30">
    </label>

    <label for="emailInput">
        <span class="textSpan">Email:</span>
        <input type="text" id="emailInput" name="email">
    </label>

    <label for="passwordInput">
        <span class="textSpan">Password:</span>
        <input type="password" id="passwordInput" name="password" maxlength="25">
    </label>

    <label for="repeatPasswordInput">
        <span class="textSpan">Repeat:</span>
        <input type="password" id="repeatPasswordInput" name="repeatPassword" maxlength="25">
    </label>

    <div id="formButtons">
        <input type="submit" name="register" value="Register">
        <input type="reset" name="clear" value="Clear"/>
    </div>
</form>

