<?php

namespace api\classes;

use Bitrix\Main\Loader;
use Legacy\General\DataProcessor;
use Legacy\General\Constants;
use Legacy\Iblock\ElementsTable;

class Test
{
    private static function processData($query)
    {
        $result = [];

        while ($arr = $query->fetch()) {
            $result = [
                'id' => $arr['ID'],
                'code' => $arr['CODE'],
                'title' => $arr['NAME'],
                'price' => $arr['PRICE_VALUE'] ?? null,
            ];
        }

        return $result;
    }

    private static function processDetailData($query)
    {
        $result = [];

        if ($arr = $query->fetch()) {
            $result = [
                'id' => $arr['ID'],
                'code' => $arr['CODE'],
                'date' => $arr['ACTIVE_FROM'] ? $arr['ACTIVE_FROM']->format('c') : null,
                'title' => $arr['NAME'],
                'description' => $arr['DESCRIPTION_VALUE'] ?? null,
                'price' => $arr['PRICE_VALUE'] ?? null,
                'sort' => $arr['SORT'],
                'date_create' => $arr['DATE_CREATE'] ? $arr['DATE_CREATE']->format('c') : null,
                'timestamp_x' => $arr['TIMESTAMP_X'] ? $arr['TIMESTAMP_X']->format('c') : null,
                'active_to' => $arr['ACTIVE_TO'] ? $arr['ACTIVE_TO']->format('c') : null,
            ];

            foreach ($result['content'] as &$item) {
                if($item['name'] == 'iblock_elements' && $item['iblock_id'] == Constants::IB_PROMOCODES) {
                    $item['name'] = 'promocodes';
                    unset($item['iblock_id'], $item['element_ids']);
                }
            }

        }

        return array_change_key_case_recursive($result);
    }

    public static function get($arRequest)
    {
        $result = [];
        if (Loader::includeModule('iblock')) {
            $page = (int)($arRequest['page'] ?? 1);
            $limit = (int)($arRequest['limit'] ?? 10);

            $q = ElementsTable::query()
                ->countTotal(true)
                ->withSelect()
                ->setLimit($limit)
                ->withPage($page)
                ->withOrderByDate('DESC')
                ->withDateActive()
                ->exec()
            ;

            $result['count'] = $q->getCount();
            $result['items'] = self::processData($q);
        }
        return $result;
    }

    public static function getByIds($arRequest)
    {
        $ids = $arRequest['ids'] ?? [];

        $result = [];
        if (Loader::includeModule('iblock')) {
            $q = ElementsTable::query()
                ->withSelect()
                ->withFilterByIDs($ids)
                ->exec()
            ;
            $result = self::processData($q);
        }

        return DataProcessor::sortResultByIDs($result, $ids);
    }

    public static function getByCode($arRequest)
    {
        $code = $arRequest['code'] ?? '';

        if(!$code) {
            throw new \Exception('Не передан код');
        }

        $result = [];
        if (Loader::includeModule('iblock')) {
            $q = ElementsTable::query()
                ->withDetailSelect()
                ->withFilterByСode($code);
            $result = self::processDetailData($q);
        }

        return $result;
    }
}