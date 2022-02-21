<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arIBlockFilter = $arParams["IBLOCK_ID"];
global $USER;
$currentUserId = \Bitrix\Main\Engine\CurrentUser::get()->getId();
if($USER->IsAuthorized()){
	if (!empty($arIBlockFilter) && $this->StartResultCache($currentUserId)) {
	    if (!CModule::IncludeModule("iblock")) {
	        $this->AbortResultCache();
	        ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
	        return;
	    }
		
		$currentUser = CUser::GetByID($currentUserId);
		$currentUserInf = $currentUser->Fetch();
		$currentUserIdGroup = $currentUserInf[$arParams['CODE_PROPERTY_USER']];
		$arFilter = Array("IBLOCK_ID"=>1, "ACTIVE" => "Y", "!PROPERTY_AUTHOR" => $currentUserId);
		$arGroupBy = Array("PROPERTY_AUTHOR");
		$arOrder = Array("NAME" => "DESC", "ACTIVE_FROM" => "DESC");
		$res = CIBlockElement::GetList($arOrder, $arFilter, $arGroupBy);
		
		$authId = -1;
		while($arFields = $res->GetNext(false, false)) {
			$key = $arFields[$arParams["CODE_PROPERTY_AUTHOR"]];
			$rsUser = CUser::GetByID($key);
			$arUser = $rsUser->Fetch();

			if ($arUser[$arParams['CODE_PROPERTY_USER']] == $currentUserIdGroup) {
				$arResult['count'] = count($arFields);		
				$key = "[" . $key . "] - " . $arUser['LOGIN'];
					$arResult[$key][] = [
					$arFields['NAME'],
					$arFields['ACTIVE_FROM'],
		        ];
				$authId = $key;
			}

			if ($authId == -1) {
				$arResult['count'] = 0;
			}
			
		}
		$count = $arResult['count'];
	    $this->SetResultCacheKeys(['count']);
	    $this->IncludeComponentTemplate();
	}
	$countForTitle = Bitrix\Main\Localization\Loc::getMessage('COUNT_NEWS') . $count;
	$APPLICATION->SetPageProperty('title', $countForTitle);
}