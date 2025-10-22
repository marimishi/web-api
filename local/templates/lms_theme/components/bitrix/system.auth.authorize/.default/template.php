<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

$this->setFrameMode(true);
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    :root {
        --caribbean-current: #3C6E71;
        --indigo-dye: #284B63;
        --platinum: #E5E5E5;
        --white: #FFFFFF;
        --jet: #353535;
    }
    
    .auth-form-modern {
        max-width: 100%;
        margin: 0 auto;
    }
    
    .alert-error {
        background: #f8d7da;
        color: #721c24;
        padding: 1rem;
        border-radius: 8px;
        border-left: 4px solid #dc3545;
        margin-bottom: 1.5rem;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: var(--indigo-dye);
    }
    
    .input-wrapper {
        position: relative;
    }
    
    .form-input {
        width: 100%;
        padding: 1rem;
        padding-left: 2.5rem;
        border: 1px solid var(--platinum);
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.3s ease;
        box-sizing: border-box;
        background: var(--white);
    }
    
    .form-input:focus {
        border-color: var(--caribbean-current);
        box-shadow: 0 0 0 3px rgba(60, 110, 113, 0.1);
        outline: none;
    }
    
    .input-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--caribbean-current);
    }
    
    .form-options {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }
    
    .checkbox-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin: 0;
        cursor: pointer;
        font-size: 0.9rem;
        color: var(--jet);
    }
    
    .forgot-link {
        font-size: 0.9rem;
        color: var(--caribbean-current);
        text-decoration: none;
        transition: color 0.3s ease;
    }
    
    .forgot-link:hover {
        color: var(--indigo-dye);
    }
    
    .submit-btn {
        width: 100%;
        padding: 1rem;
        background: var(--caribbean-current);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    
    .submit-btn:hover {
        background: var(--indigo-dye);
        transform: translateY(-2px);
    }
    
    .auth-links {
        text-align: center;
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--platinum);
    }
    
    .auth-link {
        color: var(--caribbean-current);
        text-decoration: none;
        transition: color 0.3s ease;
        font-size: 0.9rem;
    }
    
    .auth-link:hover {
        color: var(--indigo-dye);
    }
</style>

<div class="auth-form-modern">
    <?php if ($arResult['SHOW_ERRORS'] == 'Y' && $arResult['ERROR_MESSAGE']): ?>
        <div class="alert-error">
            <?= $arResult['ERROR_MESSAGE'] ?>
        </div>
    <?php endif; ?>

    <form name="system_auth_form" method="post" target="_top" action="<?= $arResult['AUTH_URL'] ?>">
        <?php if ($arResult['BACKURL'] != ''): ?>
            <input type="hidden" name="backurl" value="<?= $arResult['BACKURL'] ?>" />
        <?php endif; ?>
        
        <input type="hidden" name="AUTH_FORM" value="Y" />
        <input type="hidden" name="TYPE" value="AUTH" />

        <div class="form-group">
            <label class="form-label">
                Логин или Email
            </label>
            <div class="input-wrapper">
                <input 
                    type="text" 
                    name="USER_LOGIN" 
                    id="USER_LOGIN" 
                    value="<?= $arResult['LAST_LOGIN'] ?>" 
                    placeholder="Введите ваш логин или email"
                    class="form-input"
                >
                <i class="fas fa-user input-icon"></i>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">
                Пароль
            </label>
            <div class="input-wrapper">
                <input 
                    type="password" 
                    name="USER_PASSWORD" 
                    id="USER_PASSWORD" 
                    placeholder="Введите ваш пароль"
                    autocomplete="off"
                    class="form-input"
                >
                <i class="fas fa-lock input-icon"></i>
            </div>
        </div>

        <div class="form-options">
            <label class="checkbox-label">
                <input type="checkbox" name="USER_REMEMBER" id="USER_REMEMBER" value="Y" style="width: 18px; height: 18px; accent-color: var(--caribbean-current);">
                Запомнить меня
            </label>
            
            <?php if ($arParams['FORGOT_PASSWORD_URL'] != ''): ?>
                <a href="<?= $arParams['FORGOT_PASSWORD_URL'] ?>" class="forgot-link">
                    Забыли пароль?
                </a>
            <?php endif; ?>
        </div>

        <button type="submit" class="submit-btn">
            <i class="fas fa-sign-in-alt"></i> Войти в систему
        </button>

        <?php if ($arParams['REGISTER_URL'] != ''): ?>
            <div class="auth-links">
                <a href="<?= $arParams['REGISTER_URL'] ?>" class="auth-link">
                    Нет аккаунта? Зарегистрироваться
                </a>
            </div>
        <?php endif; ?>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Добавляем обработчики для улучшения UX
    const inputs = document.querySelectorAll('.form-input');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.style.borderColor = 'var(--caribbean-current)';
            this.style.boxShadow = '0 0 0 3px rgba(60, 110, 113, 0.1)';
        });
        
        input.addEventListener('blur', function() {
            this.style.borderColor = 'var(--platinum)';
            this.style.boxShadow = 'none';
        });
    });
    
    // Автофокус на поле логина
    const loginInput = document.getElementById('USER_LOGIN');
    if (loginInput && !loginInput.value) {
        loginInput.focus();
    }
});
</script>