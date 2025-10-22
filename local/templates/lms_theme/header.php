<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?$APPLICATION->ShowTitle()?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --jet: #353535;
            --caribbean-current: #3c6e71;
            --white: #ffffff;
            --platinum: #d9d9d9;
            --indigo-dye: #284b63;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, var(--white) 0%, var(--platinum) 100%);
            min-height: 100vh;
            color: var(--jet);
        }
        
        .header {
            background: var(--indigo-dye);
            color: var(--white);
            padding: 1rem 0;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 2rem;
        }
        
        .logo {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--white);
            text-decoration: none;
        }
        
        .logo span {
            color: var(--caribbean-current);
        }
        
        .nav-links {
            display: flex;
            list-style: none;
            gap: 2rem;
        }
        
        .nav-links a {
            color: var(--white);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .nav-links a:hover {
            color: var(--caribbean-current);
        }
        
        .user-menu {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--caribbean-current);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-weight: 600;
        }
        
        .main-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
        }
        
        .page-title {
            color: var(--indigo-dye);
            margin-bottom: 2rem;
            font-size: 2.5rem;
            font-weight: 700;
        }
        
        .card {
            background: var(--white);
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
            border: 1px solid var(--platinum);
            margin-bottom: 2rem;
        }
        
        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-primary {
            background: var(--caribbean-current);
            color: var(--white);
        }
        
        .btn-primary:hover {
            background: var(--indigo-dye);
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background: var(--platinum);
            color: var(--jet);
        }
        
        .course-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }
        
        .course-card {
            background: var(--white);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        
        .course-card:hover {
            transform: translateY(-5px);
        }
        
        .course-image {
            height: 200px;
            background: linear-gradient(45deg, var(--indigo-dye), var(--caribbean-current));
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-size: 3rem;
        }
        
        .course-content {
            padding: 1.5rem;
        }
        
        .course-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--indigo-dye);
            margin-bottom: 0.5rem;
        }
        
        .course-meta {
            display: flex;
            justify-content: space-between;
            color: var(--jet);
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }
        
        .progress-bar {
            background: var(--platinum);
            border-radius: 10px;
            height: 8px;
            margin: 1rem 0;
            overflow: hidden;
        }
        
        .progress-fill {
            background: var(--caribbean-current);
            height: 100%;
            border-radius: 10px;
            transition: width 0.3s ease;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin: 2rem 0;
        }
        
        .stat-card {
            background: linear-gradient(135deg, var(--indigo-dye), var(--caribbean-current));
            color: var(--white);
            padding: 1.5rem;
            border-radius: 12px;
            text-align: center;
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        @media (max-width: 768px) {
            .nav-container {
                flex-direction: column;
                gap: 1rem;
            }
            
            .nav-links {
                gap: 1rem;
            }
            
            .main-container {
                padding: 0 1rem;
            }
            
            .course-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="nav-container">
            <a href="/" class="logo">EduLMS</a>
            
            <ul class="nav-links">
                <li><a href="/courses/"><i class="fas fa-book"></i> Курсы</a></li>
                <li><a href="/my-courses/"><i class="fas fa-graduation-cap"></i> Мои курсы</a></li>
                <?if($USER->IsAdmin()):?>
                    <li><a href="/admin/"><i class="fas fa-cog"></i> Админка</a></li>
                <?endif;?>
            </ul>
            
            <div class="user-menu">
                <?if($USER->IsAuthorized()):?>
                    <div class="avatar">
                        <?=mb_substr($USER->GetFullName(), 0, 1)?>
                    </div>
                    <a href="/profile/" style="color: var(--white);">
                        <?=$USER->GetFullName()?>
                    </a>
                    <a href="?logout=yes&sessid=<?=bitrix_sessid()?>" style="color: var(--white); margin-left: 1rem;" title="Выйти">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                <?else:?>
                    <a href="/auth/" class="btn btn-primary">Войти</a>
                <?endif;?>
            </div>
        </div>
    </div>

    <div class="main-container">