<?php include('connect.php'); ?>
<form id="login-form" class="form form--login" method="POST" action="login.php">
    <div class="form__title">
        <h2>Login</h2>
        <img src="images/logo.png" alt="logo">
    </div>
    <div class="input-group">
        <input class="input-group__input" type="email" name="email">
        <label class="input-group__label">
            <span class="input-group__label-text">Email</span>
        </label>
        <svg class="input-group__icon" xmlns="http://www.w3.org/2000/svg" width="16" height="14">
            <path id="ic_mail"
                  d="M790 188h16v14h-16v-14zm2 2h12v10h-12v-10zm12.989.352l-6.637 6.637-.352-.352-.352.352-6.637-6.637 1.341-1.341 5.648 5.648 5.648-5.648z"
                  transform="translate(-790 -188)"/>
        </svg>
    </div>
    <div class="input-group">
        <input class="input-group__input" type="password" name="password">
        <label class="input-group__label">
            <span class="input-group__label-text">Password</span>
        </label>
        <svg class="input-group__icon" xmlns="http://www.w3.org/2000/svg" width="16" height="17">
            <path id="ic_lock"
                  d="M826 169h16v10h-16v-10zm2 2h12v6h-12v-6zm9-1v-3a3 3 0 0 0-6 0v3h-2v-3a5 5 0 0 1 10 0v3h-2z"
                  transform="translate(-826 -162)"/>
        </svg>
    </div>
    <div class="login-group">
        <button class="login-group__button" type="submit" name="login">LOGIN</button>
        <a href="#" class="login-group__forgot">Forgot?</a>
    </div>
</form>