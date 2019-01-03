<?php

$data = [];

$cpuInfo = file_get_contents('/proc/cpuinfo');
if (preg_match_all("#^processor[ \t]*:#m", $cpuInfo, $matches)) {
	$data['numCores'] = count($matches[0]);
} else {
	$data['numCores'] = null;
}

$memInfo = file_get_contents('/proc/meminfo');
if (preg_match_all("#^(MemTotal|MemFree|MemAvailable):[ \t]*([0-9]+)#m", $memInfo, $matches)) {
	$data['memory'] = [];
	foreach ($matches[1] as $k => $v) {
		$data['memory'][$v] = (int)$matches[2][$k];
	}
}

$df = explode("\n", `df`);
foreach ($df as $line) {
	if (!$line) {
		continue;
	}
	$line = preg_split("#[ \t]+#", $line);
	[$_, $total, $used, $available, $_, $mountPoint] = $line;
	if ($mountPoint == '/') {
		$data['disk'] = compact('total', 'used', 'available');
	}
}

header('Content-type: application/json');
echo json_encode($data, JSON_PRETTY_PRINT);

