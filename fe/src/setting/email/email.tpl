<!--target: list-->
<div class="changephone-top">
    <div class="changephone-title stepone">
        <div class="number-text">
            <p class="number-p current">提供最新邮箱</p>
            <p class="number-p">验证邮箱</p>
            <p class="number-p three last">修改成功</p>
        </div>
    </div>
    <div class="changephone-box">
        <div class="login-username">
            <input class="login-input" id="login-email" type="text" />
            <label class="user-lable" for="login-email">邮箱</label>
            <div class="username-error"></div>
        </div>
        <div class="login-username">
            <input class="login-input testing email-test" id="login-testing" type="password" />
            <label class="user-lable" for="login-testing">验证码</label>
            <span class="login-username-testing testing_span"></span>
            <div class="username-error"></div>
        </div>
        <a href="###" class="login-fastlogin">确认</a>
    </div>
</div>

<!--target: list2nd-->
<div class="changephone-top">
    <div class="changephone-title steptwo">
        <div class="number-text">
            <p class="number-p current">提供最新邮箱</p>
            <p class="number-p">验证邮箱</p>
            <p class="number-p three last">修改成功</p>
        </div>
    </div>
    <div class="changephone-box">
        <div class="success-pic"></div>
        <div class="success-text email-box">认证邮件已经发送至 <span class="email-box-span">${email}</span></div>
        <div class="success-box-time">
            <span class="timer-span" id="time-span">6秒后自动跳转至</span>&nbsp;
            <a class="success-link" href="/account/overview/index">我的账户</a>
        </div>
    </div>
</div>
