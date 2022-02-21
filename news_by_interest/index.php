<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->ShowTitle();
$APPLICATION->IncludeComponent(
                    "news:infoservice.news_by_interests",
                    ".default",
                    Array(
                    "COMPONENT_TEMPLATE" => ".default",
                    "IBLOCK_ID" => "1",
                    "IBLOCK_TYPE" => 'news',
                    "CACHE_TYPE" => "A",
                    "CODE_PROPERTY_AUTHOR" => 'PROPERTY_AUTHOR_VALUE',
                    "CODE_PROPERTY_USER" => 'UF_GROUP',
                    "CACHE_TIME" => 3600,
            ));
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>
