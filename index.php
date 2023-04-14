<?php
// https://www.deepl.com/docs-api/translate-text/translate-text/
require 'dotenv.php';
$available_source_langs = [
	'AUTO', // Auto Detect Language
	'BG', // Bulgarian
	'CS', // Czech
	'DA', // Danish
	'DE', // German
	'EL', // Greek
	'EN', // English
	'ES', // Spanish
	'ET', // Estonian
	'FI', // Finnish
	'FR', // French
	'HU', // Hungarian
	'ID', // Indonesian
	'IT', // Italian
	'JA', // Japanese
	'KO', // Korean
	'LT', // Lithuanian
	'LV', // Latvian
	'NB', // Norwegian (Bokmål)
	'NL', // Dutch
	'PL', // Polish
	'PT', // Portuguese (all Portuguese varieties mixed)
	'RO', // Romanian
	'RU', // Russian
	'SK', // Slovak
	'SL', // Slovenian
	'SV', // Swedish
	'TR', // Turkish
	'UK', // Ukrainian
	'ZH' // Chinese
];
$available_target_langs = [
	'BG', // Bulgarian
	'CS', // Czech
	'DA', // Danish
	'DE', // German
	'EL', // Greek
	'EN', // English (unspecified variant for backward compatibility; please select EN-GB or EN-US instead)
	'EN-GB', // English (British)
	'EN-US', // English (American)
	'ES', // Spanish
	'ET', // Estonian
	'FI', // Finnish
	'FR', // French
	'HU', // Hungarian
	'ID', // Indonesian
	'IT', // Italian
	'JA', // Japanese
	'KO', // Korean
	'LT', // Lithuanian
	'LV', // Latvian
	'NB', // Norwegian (Bokmål)
	'NL', // Dutch
	'PL', // Polish
	'PT', // Portuguese (unspecified variant for backward compatibility; please select PT-BR or PT-PT instead)
	'PT-BR', // Portuguese (Brazilian)
	'PT-PT', // Portuguese (all Portuguese varieties excluding Brazilian Portuguese)
	'RO', // Romanian
	'RU', // Russian
	'SK', // Slovak
	'SL', // Slovenian
	'SV', // Swedish
	'TR', // Turkish
	'UK', // Ukrainian
	'ZH' // Chinese (simplified)
];
$source_lang = '';
if (isset($_GET['source'])) {
	if (!in_array(strtoupper($_GET['source']), $available_source_langs)) {
		http_response_code(400);
		die('Error: Unsupported source language! The only supported values are the following: ' . implode(', ', $available_source_langs));
	}
	if (strtolower($_GET['source']) != 'auto') {
		$source_lang = '&source_lang=' . $_GET['source'];
	}
}
$target_lang = '';
if (isset($_GET['target'])) {
	if (!in_array(strtoupper($_GET['target']), $available_target_langs)) {
		http_response_code(400);
		die('Error: Unsupported target language! The only supported values are the following: ' . implode(', ', $available_target_langs));
	}
	$target_lang = '&target_lang=' . $_GET['target'];
} else {
	http_response_code(400);
	die('400 Bad Request - Missing required parameter target');
}
if (isset($_GET['text'])) {
	$text = $_GET['text'];
	$ch =  curl_init('https://api-free.deepl.com/v2/translate');
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, [
		'User-Agent: Twitch Chat Proxy',
		"Authorization: DeepL-Auth-Key {$DeepL_Auth_Key}"
	]);
	curl_setopt($ch, CURLOPT_POSTFIELDS, "text={$text}{$source_lang}{$target_lang}&formality=prefer_more");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$response = json_decode(curl_exec($ch), true);
	curl_close($ch);
	echo $response['translations'][0]['text'] . ' (Detected Source Language: ' . $response['translations'][0]['detected_source_language'] . ')';
} else {
	http_response_code(400);
	echo '400 Bad Request - Missing required parameter text';
}
?>
