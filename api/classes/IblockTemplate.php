<?php

namespace Legacy\API;

use Legacy\General\Constants;
use Bitrix\Main\Loader;
use Legacy\Iblock\IblockElementTable;

class IblockTemplate
{
    public static function getElement($arRequest = [])
    {
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            if (!Loader::includeModule('iblock')) {
                throw new \Exception('Не удалось подключить необходимые модули');
            }

            $limit = isset($arRequest['limit']) ? (int)$arRequest['limit'] : 10;
            $page = isset($arRequest['page']) ? (int)$arRequest['page'] : 1;
            
            $query = IblockElementTable::query()
                ->withSelect()
                ->addFilter('IBLOCK_ID', Constants::IB_TEMPLATE)
                ->withFilter()
                ->withOrder()
                ->withPage($limit, $page);

            $count = $query->queryCountTotal();
            $db = $query->exec();

            $result = [];
            while ($res = $db->fetch()) {
                $properties = self::getElementProperties($res['ID']);
                
                $result[] = [
                    'id' => (int)$res['ID'],
                    'name' => $res['NAME'],
                    'date' => $res['DATE_ACTIVE_FROM'],
                    'preview_text' => $res['PREVIEW_TEXT'],
                    'detail_text' => $res['DETAIL_TEXT'],
                    'properties' => $properties
                ];
            }

            return [
                'status' => 'success',
                'data' => [
                    'items' => $result,
                    'pagination' => [
                        'total_count' => $count,
                        'current_page' => $page,
                        'per_page' => $limit,
                        'total_pages' => ceil($count / $limit)
                    ]
                ],
                'timestamp' => date('Y-m-d H:i:s')
            ];

        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'timestamp' => date('Y-m-d H:i:s')
            ];
        }
    }

    private static function getElementProperties($elementId)
    {
        $properties = [];
        
        $dbProps = \CIBlockElement::GetProperty(
            Constants::IB_TEMPLATE, 
            $elementId
        );
        
        while ($prop = $dbProps->Fetch()) {
            if ($prop['PROPERTY_TYPE'] == 'L' && $prop['LIST_TYPE'] == 'C') {
                $properties[$prop['CODE']] = $prop['VALUE_ENUM'] ?? $prop['VALUE'];
            } else {
                $properties[$prop['CODE']] = $prop['VALUE'];
            }
        }
        
        // file_put_contents($_SERVER['DOCUMENT_ROOT'].'/debug_properties.log', 
        //     "Element ID: $elementId\nProperties: ".print_r($properties, true)."\n\n", 
        //     FILE_APPEND
        // );
        
        return [
            'price' => isset($properties['PRICE']) ? (float)$properties['PRICE'] : null,
            'color' => $properties['COLOR'] ?? null,
            'in_stock' => isset($properties['IN_STOCK']) ? $properties['IN_STOCK'] === 'Да' : null,
            'description' => $properties['DESCRIPTION'] ?? null,
            'manufacture_date' => $properties['MANUFACTURE_DATE'] ?? null
        ];
    }
    
    public static function getTestItems($arRequest = [])
    {
        return self::getElement($arRequest);
    }
}