<?php

namespace Legacy\Iblock;

use Bitrix\Iblock\ElementTable;
use Bitrix\Iblock\SectionTable;
use Bitrix\Iblock\ElementPropertyTable;
use Bitrix\Main\DB\SqlExpression;
use Bitrix\Main\Entity\Query;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Entity\ExpressionField;

class IblockElementTable extends ElementTable
{
    public static function withSelect(Query $query)
    {
        $query->setSelect([
            'ID',
            'NAME',
            'CODE',
            'ACTIVE',
            'DATE_CREATE',
            'TIMESTAMP_X',
            'SORT',
            'PREVIEW_TEXT',
            'DETAIL_TEXT'
        ]);
    }

    public static function withRuntimeProperties(Query $query)
    {
        // Присоединение свойства "Описание"
        $query->registerRuntimeField(
            'DESCRIPTION_PROP',
            new ReferenceField(
                'DESCRIPTION_PROP',
                ElementPropertyTable::class,
                [
                    'ref.IBLOCK_ELEMENT_ID' => 'this.ID',
                    'ref.IBLOCK_PROPERTY_ID' => new SqlExpression('?', 1)
                ]
            )
        );

        // Присоединение свойства "Цена"
        $query->registerRuntimeField(
            'PRICE_PROP',
            new ReferenceField(
                'PRICE_PROP',
                ElementPropertyTable::class,
                [
                    'ref.IBLOCK_ELEMENT_ID' => 'this.ID',
                    'ref.IBLOCK_PROPERTY_ID' => new SqlExpression('?', 2)
                ]
            )
        );

        $query->addSelect('DESCRIPTION_PROP.VALUE', 'DESCRIPTION_VALUE');
        $query->addSelect('PRICE_PROP.VALUE', 'PRICE_VALUE');
    }

    public static function withFilter(Query $query, $arFilter = [])
    {
        if (!empty($arFilter)) {
            $query->setFilter($arFilter);
        }
        
        $query->addFilter('ACTIVE', 'Y');
    }

    public static function withOrder(Query $query, $arOrder = [])
    {
        if (empty($arOrder)) {
            $arOrder = ['SORT' => 'ASC', 'ID' => 'DESC'];
        }
        $query->setOrder($arOrder);
    }

    public static function withPage(Query $query, $limit = 10, $page = 1)
    {
        if ($limit > 0) {
            $query->setLimit($limit);
        }
        if ($page > 0) {
            $query->setOffset(($page - 1) * $limit);
        }
    }
}