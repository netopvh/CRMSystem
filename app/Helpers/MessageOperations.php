<?php

// Хелпер формирования сообщений

// Формируем сообщения для лидов
function lead_info($message, $lead){

	$message .= "Информация по лиду:\r\n";
	$message .= "Номер: " . $lead->case_number . "\r\n";
	$message .= "Имя: " . $lead->name . "\r\n";
	$message .= "Телефон: " . (isset($lead->main_phone->phone) ? decorPhone($lead->main_phone->phone) : 'Номер не указан') . "\r\n";
	$message .= "Адрес: г. " . $lead->location->city->name . ' ' . (isset($lead->location->address) ? ', ' . $lead->location->address : '') . "\r\n";
	// $message .= ((count($lead->choices_goods_categories) > 0) && (count($lead->choices_goods_categories) > 0) ? "Спрос:\r\n" : '');
	$message .= isset($lead->choices_goods_categories) ? "Товары: " . $lead->choices_goods_categories->implode('name', ',') . "\r\n" : "";
	$message .= isset($lead->choices_services_categories) ? "Услуги: " . $lead->choices_services_categories->implode('name', ',') . "\r\n" : "";
	$message .= ($lead->badget > 0) ? "Бюджет: " . $lead->badget . "\r\n" : '';

	return $message;
}






?>