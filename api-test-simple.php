<?php
define('NO_KEEP_STATISTIC', true);
define('NOT_CHECK_PERMISSIONS', true);
define('CHK_EVENT', true);
define('BX_NO_ACCELERATOR_RESET', true);

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

header('Content-Type: application/json; charset=utf-8');

try {
    if (!CModule::IncludeModule("iblock")) {
        throw new Exception('Модуль инфоблоков не подключен');
    }

    $iblockId = 1; 
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

    $arFilter = ["IBLOCK_ID" => $iblockId, "ACTIVE" => "Y"];
    $arSelect = [
        "ID", "NAME", "DATE_ACTIVE_FROM", "PREVIEW_TEXT", "DETAIL_TEXT",
        "PROPERTY_PRICE", "PROPERTY_COLOR", "PROPERTY_IN_STOCK", 
        "PROPERTY_DESCRIPTION", "PROPERTY_MANUFACTURE_DATE"
    ];
    
    $dbItems = CIBlockElement::GetList(
        ["SORT" => "ASC"],
        $arFilter,
        false,
        ["nPageSize" => $limit, "iNumPage" => $page],
        $arSelect
    );

    $items = [];
    while ($item = $dbItems->GetNext()) {
        $items[] = [
            'id' => (int)$item['ID'],
            'name' => $item['NAME'],
            'date' => $item['DATE_ACTIVE_FROM'],
            'preview_text' => $item['PREVIEW_TEXT'],
            'detail_text' => $item['DETAIL_TEXT'],
            'properties' => [
                'price' => $item['PROPERTY_PRICE_VALUE'] ? (float)$item['PROPERTY_PRICE_VALUE'] : null,
                'color' => $item['PROPERTY_COLOR_VALUE'],
                'in_stock' => $item['PROPERTY_IN_STOCK_VALUE'] === 'Да',
                'description' => $item['PROPERTY_DESCRIPTION_VALUE'],
                'manufacture_date' => $item['PROPERTY_MANUFACTURE_DATE_VALUE']
            ]
        ];
    }

    $totalCount = CIBlockElement::GetList([], $arFilter, []);
    
    echo json_encode([
        'status' => 'success',
        'data' => [
            'items' => $items,
            'total_count' => $totalCount,
            'iblock_info' => [
                'id' => $iblockId,
                'name' => 'Test API'
            ]
        ],
        'message' => 'API работает!'
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_after.php');
?>