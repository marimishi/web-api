<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Engine\Controller;
use Bitrix\Main\Engine\ActionFilter;
use Bitrix\Main\Loader;
use Bitrix\Iblock\ElementTable;

class TestApiComponent extends Controller
{
    // Метод для настройки прав доступа к экшенам (действиям)
    protected function getDefaultPreFilters()
    {
        return [
            new ActionFilter\HttpMethod(
                [ActionFilter\HttpMethod::METHOD_GET, ActionFilter\HttpMethod::METHOD_POST]
            ),
            new ActionFilter\Cors(), // Разрешаем CORS, если нужно
        ];
    }

    // Объявляем, какие методы будут доступны как API-эндпоинты
    public function configureActions()
    {
        return [
            'getItems' => [ // Это имя нашего метода-экшена
                'prefilters' => []
            ],
        ];
    }

    // Сам API метод
    // Будет доступен по URL: /bitrix/services/main/ajax.php?action=custom:test.api.getItems
    public function getItemsAction()
    {
        // Подключаем модуль Инфоблоков
        if (!Loader::includeModule("iblock")) {
            return $this->sendError("Module Iblock is not installed");
        }

        // Укажите здесь ID вашего инфоблока
        $iblockId = 1; // !!! ЗАМЕНИТЕ НА РЕАЛЬНЫЙ ID ВАШЕГО ИНФОБЛОКА !!!

        // Выбираем элементы
        $dbElements = CIBlockElement::GetList(
            array("SORT" => "ASC"), // Сортировка
            array("IBLOCK_ID" => $iblockId, "ACTIVE" => "Y"), // Фильтр
            false, // Группировка
            false, // Навигация
            array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM", "PREVIEW_TEXT", "PROPERTY_*") // Выбираемые поля
        );

        $items = array();
        while ($element = $dbElements->GetNextElement()) {
            $fields = $element->GetFields();
            $properties = $element->GetProperties();

            // Формируем массив элемента с нужными полями и свойствами
            $item = array(
                'id' => (int)$fields['ID'],
                'name' => $fields['NAME'],
                'date' => $fields['DATE_ACTIVE_FROM'],
                'preview_text' => $fields['PREVIEW_TEXT'],
                'properties' => array(
                    'price' => (float)$properties['PRICE']['VALUE'],
                    'color' => $properties['COLOR']['VALUE'],
                    'in_stock' => $properties['IN_STOCK']['VALUE'] == 'Y', // Преобразуем в boolean
                    'description' => $properties['DESCRIPTION']['VALUE']['TEXT'] ?? $properties['DESCRIPTION']['VALUE'], // Для свойств типа HTML/текст
                    'manufacture_date' => $properties['MANUFACTURE_DATE']['VALUE'],
                )
            );
            $items[] = $item;
        }

        // Возвращаем успешный JSON-ответ
        return $this->sendSuccess($items);
    }

    // Вспомогательный метод для успешного ответа
    private function sendSuccess($data)
    {
        return [
            'status' => 'success',
            'data' => $data
        ];
    }

    // Вспомогательный метод для ответа с ошибкой
    private function sendError($message)
    {
        return [
            'status' => 'error',
            'message' => $message
        ];
    }
}
?>