#!/usr/bin/php
<?php
$data = file_get_contents("users.json");
$allEmployees = json_decode($data, true);
$managers = array(245, 308, 499, 486, 555, 10, 187, 187, 305);
//var_export($allEmployees);
emptyDir("./build");
$created = 0;
foreach ($allEmployees as $index => $empData) {
	if (stristr($empData['department'], "platform")) {
		$name = $empData['name'];
		$birthDay = str_replace("-", "", $empData['birthDay']);
		if (strlen($birthDay) < 4) continue;
		$formattedDate = "2020".$birthDay;
		$alarmUid = strtoupper(getRandomHex(4)."-".getRandomHex(2)."-".getRandomHex(2)."-".getRandomHex(2)."-".getRandomHex(6));
		$xwralarmUid = strtoupper(getRandomHex(4)."-".getRandomHex(2)."-".getRandomHex(2)."-".getRandomHex(2)."-".getRandomHex(6));
		$file = preg_replace("/\W+/", "_", $name).".ics";
		$output = "BEGIN:VCALENDAR
CALSCALE:GREGORIAN
VERSION:2.0
X-WR-CALNAME:Birthday ".$name."
METHOD:PUBLISH
PRODID:-//Apple Inc.//Mac OS X 10.12.6//EN
BEGIN:VEVENT
TRANSP:TRANSPARENT
DTEND;VALUE=DATE:".$formattedDate."
LAST-MODIFIED:20171127T100723Z
UID:".strtoupper(getRandomHex(4)."-".getRandomHex(2)."-".getRandomHex(2)."-".getRandomHex(2)."-".getRandomHex(6))."
DTSTAMP:20171127T100723Z
LOCATION:Wherever there's a party
DESCRIPTION:
STATUS:CONFIRMED
SEQUENCE:0
SUMMARY:It's ".$name."'s birthday!
DTSTART;VALUE=DATE:".$formattedDate."
X-APPLE-TRAVEL-ADVISORY-BEHAVIOR:AUTOMATIC
CREATED:20171127T100723Z
RRULE:FREQ=YEARLY;INTERVAL=1
BEGIN:VALARM
X-WR-ALARMUID:".$alarmUid."
UID:".$alarmUid."
TRIGGER;VALUE=DATE-TIME:19760401T005545Z
ACTION:NONE
END:VALARM
BEGIN:VALARM
X-WR-ALARMUID:".$xwralarmUid."
UID:".$xwralarmUid."
TRIGGER:-P4DT14H30M
ATTACH;VALUE=URI:Basso
ACTION:AUDIO
END:VALARM
END:VEVENT
END:VCALENDAR";
file_put_contents("./build/".$file, $output);
$created++;
	}
}
echo $created." birthday events created!".PHP_EOL;
 
function getRandomHex($num_bytes=4) {
  return bin2hex(openssl_random_pseudo_bytes($num_bytes));
}

function emptyDir($dir) {
	$it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
	$files = new RecursiveIteratorIterator($it,
				RecursiveIteratorIterator::CHILD_FIRST);
	foreach($files as $file) {
		if ($file->isDir()){
			rmdir($file->getRealPath());
		} else {
			unlink($file->getRealPath());
		}
	}
}