<!-- target: fixBox -->

<div class="login-left-box login">
    <div class="login-left-title">账户绑定</div>
    <div id="login-error" class="login-error"></div>
    <div class="login-username">
        <input autocomplete="off" class="login-input" id="login-user" type="text" />
        <label class="user-lable" for="login-user">用户名</label>
    </div>
    <div class="login-username">
        <input autocomplete="off" class="login-input" id="login-pwd" type="password" />
        <label class="user-lable" for="login-pwd">密码</label>
    </div>
    <div class="login-username login-display-none">
        <input autocomplete="off" class="login-input testing" id="login-testing" type="text" />
        <label class="user-lable" for="login-testing">验证码</label>
        <a href="###" class="login-username-testing">
            <img id="login-img-url" src="#" width="162" height="35" />
        </a>
        <div class="username-error yanzma" id="login-testing-error">
        </div>
    </div>
    <a class="login-fastlogin">确定</a>

</div>