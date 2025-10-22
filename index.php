<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$APPLICATION->SetTitle("EduLMS - Система дистанционного обучения");
?>


<div class="card">
    <div style="text-align: center; padding: 3rem 2rem;">
        <div style="font-size: 4rem; color: var(--caribbean-current); margin-bottom: 1rem;">
            <i class="fas fa-graduation-cap"></i>
        </div>
        <h1 style="color: var(--indigo-dye); margin-bottom: 1rem;">Добро пожаловать в систему дистанционного обучения</h1>
        <p style="color: var(--jet); font-size: 1.2rem; line-height: 1.6; max-width: 600px; margin: 0 auto 2rem;">
            Группа 10: Мишина Мария, Бобов Никита, Зарянова Анджела, Таскулина Амина
        </p>
        
        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
            <a href="/courses/" class="btn btn-primary" style="font-size: 1.1rem; padding: 1rem 2rem;">
                <i class="fas fa-book-open"></i> Начать обучение
            </a>
            <?php if (!$USER->IsAuthorized()): ?>
                <a href="/auth/" class="btn btn-secondary" style="font-size: 1.1rem; padding: 1rem 2rem;">
                    <i class="fas fa-sign-in-alt"></i> Войти в систему
                </a>
            <?php else: ?>
                <a href="/profile/" class="btn btn-secondary" style="font-size: 1.1rem; padding: 1rem 2rem;">
                    <i class="fas fa-user"></i> Личный кабинет
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if ($USER->IsAuthorized()): ?>
    <?php
    // Получаем роль пользователя
    $userRole = 'guest';
    $userId = $USER->GetID();
    
    // Запрос к базе данных для получения роли пользователя
    global $DB;
    $userData = $DB->Query("
        SELECT r.CODE 
        FROM b_lms_users lu 
        LEFT JOIN b_lms_roles r ON lu.ROLE_ID = r.ID 
        WHERE lu.USER_ID = " . $userId
    )->Fetch();
    
    if ($userData && $userData['CODE']) {
        $userRole = $userData['CODE'];
    }
    ?>

    
<?php else: ?>
    <!-- Блок для неавторизованных пользователей (оставляем как было) -->
    <div class="card">
        <h2 style="color: var(--indigo-dye); text-align: center; margin-bottom: 2rem;">Почему выбирают нас?</h2>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem;">
            <div style="text-align: center;">
                <div style="font-size: 3rem; color: var(--caribbean-current); margin-bottom: 1rem;">
                    <i class="fas fa-laptop-code"></i>
                </div>
                <h3 style="color: var(--indigo-dye); margin-bottom: 1rem;">Современная платформа</h3>
                <p style="color: var(--jet);">Интуитивно понятный интерфейс и адаптивный дизайн</p>
            </div>
            
            <div style="text-align: center;">
                <div style="font-size: 3rem; color: var(--caribbean-current); margin-bottom: 1rem;">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3 style="color: var(--indigo-dye); margin-bottom: 1rem;">Отслеживание прогресса</h3>
                <p style="color: var(--jet);">Детальная статистика и мониторинг успеваемости</p>
            </div>
            
            <div style="text-align: center;">
                <div style="font-size: 3rem; color: var(--caribbean-current); margin-bottom: 1rem;">
                    <i class="fas fa-comments"></i>
                </div>
                <h3 style="color: var(--indigo-dye); margin-bottom: 1rem;">Обратная связь</h3>
                <p style="color: var(--jet);">Персональные консультации и проверка работ</p>
            </div>
        </div>
    </div>
<?php endif; ?>

<style>
.dashboard-card {
    display: block;
    padding: 1.5rem;
    background: var(--platinum);
    border-radius: 8px;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.dashboard-card:hover {
    transform: translateY(-2px);
    border-color: var(--caribbean-current);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.card-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1rem;
    font-size: 1.5rem;
}

.card-icon.admin {
    background: linear-gradient(135deg, #e74c3c, #c0392b);
    color: white;
}

.card-icon.teacher {
    background: linear-gradient(135deg, #3498db, #2980b9);
    color: white;
}

.card-icon.student {
    background: linear-gradient(135deg, #27ae60, #229954);
    color: white;
}

.card-title {
    font-weight: 600;
    color: var(--indigo-dye);
    margin-bottom: 0.5rem;
    font-size: 1.1rem;
}

.card-desc {
    color: var(--jet);
    font-size: 0.9rem;
    line-height: 1.4;
}

.quick-link {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 1rem;
    background: var(--platinum);
    border-radius: 8px;
    text-decoration: none;
    color: var(--indigo-dye);
    transition: all 0.3s ease;
}

.quick-link:hover {
    background: var(--caribbean-current);
    color: white;
    transform: translateY(-2px);
}

.quick-link i {
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
}

.quick-link span {
    font-weight: 500;
}

/* Сохраняем существующие стили */
.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.2s;
}

.btn-primary {
    background: var(--caribbean-current);
    color: white;
}

.btn-secondary {
    background: var(--indigo-dye);
    color: white;
}

.btn:hover {
    opacity: 0.9;
    transform: translateY(-1px);
}

.card {
    background: white;
    padding: 2rem;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    margin-bottom: 2rem;
}

.page-title {
    color: var(--indigo-dye);
    margin-bottom: 2rem;
    font-size: 2rem;
    font-weight: 600;
}
</style>

<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>