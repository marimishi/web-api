<?php

define("NO_AGENT_CHECK", true);
define("NOT_CHECK_PERMISSIONS", true);

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

header("HTTP/1.1 200 OK");
header('Content-Type: application/json; charset=utf-8');

if (!CModule::IncludeModule("iblock")) {
    echo json_encode(['error' => 'Модуль инфоблоков не подключен']);
    exit;
}

$request = Bitrix\Main\Context::getCurrent()->getRequest();
$action = $request->get('action');
$iblockId = (int)$request->get('iblock_id') ?: 1; 
$limit = (int)$request->get('limit') ?: 10;
$page = (int)$request->get('page') ?: 1;

if ($action === 'getElements') {
    $offset = ($page - 1) * $limit;
    
    $elements = CIBlockElement::GetList(
        ['SORT' => 'ASC', 'ID' => 'DESC'],
        [
            'IBLOCK_ID' => $iblockId,
            'ACTIVE' => 'Y',
            'CHECK_PERMISSIONS' => 'N'
        ],
        false,
        ['nPageSize' => $limit, 'iNumPage' => $page],
        [
            'ID',
            'NAME',
            'CODE',
            'PREVIEW_TEXT',
            'DETAIL_TEXT',
            'DATE_CREATE',
            'PROPERTY_DESCRIPTION',
            'PROPERTY_PRICE'
        ]
    );
    
    $result = [];
    while ($element = $elements->GetNext()) {
        $result[] = [
            'id' => $element['ID'],
            'name' => $element['NAME'],
            'code' => $element['CODE'],
            'preview_text' => $element['PREVIEW_TEXT'],
            'detail_text' => $element['DETAIL_TEXT'],
            'date_create' => $element['DATE_CREATE'],
            'description' => $element['PROPERTY_DESCRIPTION_VALUE'],
            'price' => $element['PROPERTY_PRICE_VALUE']
        ];
    }
    
    echo json_encode([
        'success' => true,
        'data' => $result,
        'page' => $page,
        'limit' => $limit
    ]);
    exit;
}

echo json_encode([
    'available_actions' => [
        'getElements' => 'Получить элементы инфоблока',
    ],
    'parameters' => [
        'action' => 'getElements',
        'iblock_id' => 'ID инфоблока (по умолчанию 1)',
        'limit' => 'Количество элементов (по умолчанию 10)',
        'page' => 'Страница (по умолчанию 1)'
    ],
    'example_url' => '/local/api/?action=getElements&iblock_id=1&limit=10&page=1'
]);

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_after.php');