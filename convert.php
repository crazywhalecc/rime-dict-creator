<?php

ini_set('memory_limit', '1024M');

require_once "vendor/autoload.php";
require_once "Pinyin.php";

use Fukuball\Jieba\Jieba;
use Fukuball\Jieba\Finalseg;

Jieba::init();
Finalseg::init();

if (!isset($argv[1])) {
	echo "请输入文件名：";
	$input = trim(fgets(STDIN));
} else {
	$input = $argv[1];
}
$name = pathinfo($input)["filename"] ?? "custom";

if (!file_exists($input)) {
	die("文件 " . $input . " 不存在！\n");
}

$msg = file_get_contents($input);

preg_match_all('/[\x{4e00}-\x{9fff}]+/u', $msg, $match);

$data = $match[0];
$data = array_unique($data);

$ls = [];

foreach ($data as $v) {
	$cut = jieba::cut($v);
	foreach ($cut as $vs) {
		if (!isset($ls[$vs])) {
			$ls[$vs] = Pinyin::getPinyin($vs);
			if ($vs === '') unset($ls[$vs]);
		}
	}
}
foreach ($ls as $k => $v) {
	$result[] = $k . "\t" . $v . "\t1";
}
$result = implode("\r\n", $result);
$date = date("Y-m-d");
$head = <<<EOF
# Rime dictionary
# encoding: utf-8
# 个人词库-${name}

---
name: luna_pinyin.${name}
version: "${date}"
sort: by_weight
use_preset_vocabulary: false
...


EOF;
file_put_contents('luna_pinyin.' . $name . '.dict.yaml', $head . $result);
echo "成功生成词库：" . 'luna_pinyin.' . $name . '.dict.yaml !' . PHP_EOL;
