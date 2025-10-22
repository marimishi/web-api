<?php

namespace Legacy\API;

use Legacy\General\Constants;
use Bitrix\Main\Loader;
use Legacy\Iblock\IblockElementTable;

class IblockTemplate
{
    public static function getElement($arRequest) 
    {
        if (Loader::includeModule('iblock')) {
            $limit = isset($arRequest['limit']) ? (int)$arRequest['limit'] : 10;
            $page = isset($arRequest['page']) ? (int)$arRequest['page'] : 1;
            
            $query = IblockElementTable::query()
                ->withSelect()
                ->withRuntimeProperties()
                ->withFilter(['IBLOCK_ID' => 1])
                ->withOrder()
                ->withPage($limit, $page);
            
            $count = $query->queryCountTotal();
            $db = $query->exec();

            $result = [];
            while ($res = $db->fetch()) {
                $result[] = $res;
            }
            
            return [
                'items' => $result,
                'total' => $count,
                'page' => $page,
                'limit' => $limit,
                'pages' => ceil($count / $limit)
            ];
        }
        throw new \Exception('Не удалось подключить необходимые модули');
    }
}