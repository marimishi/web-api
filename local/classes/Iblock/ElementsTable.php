<?php

namespace Legacy\Iblock;

use Bitrix\Main\Entity\Query;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\DB\SqlExpression;
use Bitrix\Main\Type\DateTime;

class ElementsTable extends \Bitrix\Iblock\ElementTable
{
    public static function setDefaultScope($query){
        $query
            ->where("IBLOCK_ID", 1) // Ваш инфоблок ID=1
            ->where("ACTIVE", "Y");
    }

    public static function withSelect(Query $query)
    {
        // Присоединяем свойства
        $query->registerRuntimeField(
            'DESCRIPTION_PROP',
            new ReferenceField(
                'DESCRIPTION_PROP',
                ElementPropertyTable::class,
                [
                    'this.ID' => 'ref.IBLOCK_ELEMENT_ID',
                    'ref.IBLOCK_PROPERTY_ID' => new SqlExpression('?', 1), // Свойство "Описание"
                ]
            )
        );

        $query->registerRuntimeField(
            'PRICE_PROP',
            new ReferenceField(
                'PRICE_PROP',
                ElementPropertyTable::class,
                [
                    'this.ID' => 'ref.IBLOCK_ELEMENT_ID',
                    'ref.IBLOCK_PROPERTY_ID' => new SqlExpression('?', 2), // Свойство "Цена"
                ]
            )
        );

        $query->setSelect([
            'ID',
            'NAME',
            'CODE',
            'ACTIVE',
            'DATE_CREATE',
            'TIMESTAMP_X',
            'SORT',
            'ACTIVE_FROM',
            'ACTIVE_TO',
            'DESCRIPTION_VALUE' => 'DESCRIPTION_PROP.VALUE',
            'PRICE_VALUE' => 'PRICE_PROP.VALUE',
        ]);
    }

    public static function withFilterByIDs(Query $query, $ids)
    {
        $query->whereIn('ID', $ids);
    }

    public static function withFilterByCode(Query $query, $code)
    {
        $query->where('CODE', $code);
    }

    public static function withOrderByDate(Query $query, $order = 'DESC')
    {
        $query->addOrder('DATE_CREATE', $order);
    }

    public static function withPage(Query $query, int $page, int $limit = 10)
    {
        if ($page > 0 && $limit > 0) {
            $query->setLimit($limit);
            $query->setOffset(($page - 1) * $limit);
        }
    }

    public static function withDateActive(Query $query)
    {
        $dt = new DateTime();
        $query->addFilter(null, [
            'LOGIC' => 'OR',
            '<=ACTIVE_FROM' => $dt,
            'ACTIVE_FROM' => null,
        ]);
        $query->addFilter(null, [
            'LOGIC' => 'OR',
            '>=ACTIVE_TO' => $dt,
            'ACTIVE_TO' => null,
        ]);
    }

    public static function withOrderBySort(Query $query, $sort = 'ASC')
    {
        $query->addOrder('SORT', $sort);
    }
}