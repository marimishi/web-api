<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$aMenuLinks = array();

// Проверяем авторизацию
global $USER;
if (!$USER->IsAuthorized()) {
    return $aMenuLinks;
}

// Получаем роль пользователя
$userId = $USER->GetID();
global $DB;

try {
    $userData = $DB->Query("
        SELECT r.CODE as ROLE_CODE
        FROM b_lms_users lu 
        LEFT JOIN b_lms_roles r ON lu.ROLE_ID = r.ID 
        WHERE lu.USER_ID = " . intval($userId)
    )->Fetch();
} catch (Exception $e) {
    $userData = false;
}

if (!$userData) {
    return $aMenuLinks;
}

$userRole = $userData['ROLE_CODE'];

// Меню для Администратора
if ($userRole == 'admin') {
    $aMenuLinks = array(
        array(
            "Управление LMS",
            "/admin/",
            array(),
            array(),
            ""
        ),
        array(
            "Пользователи",
            "/admin/users.php",
            array(),
            array(),
            ""
        ),
        array(
            "Курсы",
            "/admin/courses.php",
            array(),
            array(),
            ""
        )
    );
}
// Меню для Преподавателя
elseif ($userRole == 'teacher') {
    $aMenuLinks = array(
        array(
            "Мои курсы",
            "/admin/courses.php",
            array(),
            array(),
            ""
        ),
        array(
            "Уроки",
            "/admin/lessons.php",
            array(),
            array(),
            ""
        ),
        array(
            "Студенты",
            "/admin/enrollments.php",
            array(),
            array(),
            ""
        ),
        array(
            "Задания",
            "/teacher/assignments/",
            array(),
            array(),
            ""
        )
    );
}
// Меню для Студента
elseif ($userRole == 'student') {
    $aMenuLinks = array(
        array(
            "Мои курсы",
            "/student/courses/",
            array(),
            array(),
            ""
        ),
        array(
            "Прогресс",
            "/student/progress/",
            array(),
            array(),
            ""
        ),
        array(
            "Задания",
            "/student/assignments/",
            array(),
            array(),
            ""
        ),
        array(
            "Оценки",
            "/student/grades/",
            array(),
            array(),
            ""
        )
    );
}

// Общие пункты меню для всех авторизованных пользователей
$commonMenu = array(
    array(
        "Профиль",
        "/profile/",
        array(),
        array(),
        ""
    ),
    array(
        "Все курсы",
        "/courses/",
        array(),
        array(),
        ""
    ),
    array(
        "Помощь",
        "/help/",
        array(),
        array(),
        ""
    )
);

// Объединяем основное меню с общими пунктами
$aMenuLinks = array_merge($aMenuLinks, $commonMenu);

return $aMenuLinks;
?>