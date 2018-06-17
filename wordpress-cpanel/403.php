<?php
$test_mode = 0;
require_once(dirname(__FILE__) . '/../../../wp-load.php');

attachment_test();
report_access();

if ($test_mode == 0) {
eicar_header();
eicar_test();
}
else {
header('Content-type: text/plain');
echo 'TEST MODE: Analytics sent via e-mail';
}

function attachment_test() {

$uri = $_SERVER['REQUEST_URI'];
$matched_content = preg_match('/^\/.*\.((\w{1,5})|favicon)(\?.*)?$/i', $uri);

if ($matched_content == 1 && !isset($_GET['na'])) {
die ('');
}

}

function eicar_header() {
header('Content-disposition: attachment; filename="eicar.com"');
header('Content-type: application/octet-stream');
}

function eicar_test() {
$test_file_data = file_get_contents('https://secure.eicar.org/eicar.com');
echo $test_file_data;
}

function report_access() {
$file_path = dirname(__FILE__) . '/../data/c403.txt';
$uri = $_SERVER['REQUEST_URI'];
$matched_content = preg_match('/^\/.*\.(\w{1,5})(\?.*)?$/i', $uri);

$cur_data = array(
'timestamp' => gmdate('F j, Y, g:i a'),
'server' => $_SERVER,
'request' => $_REQUEST,
'get' => $_GET,
'post' => $_POST,
'cookies' => $_COOKIE,
'reverse_ip' => $_SERVER['REMOTE_ADDR'],
'reverse_host' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
'url' => $uri
);
if (array_key_exists('HTTP_X_NEWRELIC_ID', $_SERVER) === true) {
$relic_id = $_SERVER['HTTP_X_NEWRELIC_ID'];
$relic_transaction = $_SERVER['HTTP_X_NEWRELIC_TRANSACTION'];
$cur_data['relic_id'] = $relic_id;
$cur_data['relic_trans'] = $relic_transaction;
}
$entry = json_encode($cur_data, JSON_PRETTY_PRINT);
$entry_html = str_ireplace("\n", "<br/>", $entry);
if (strlen($entry_html) > 35) {
$entry_html = substr($entry_html, 0, 35) . '...';
}

$to = '...';

$given_headers = array(
'From: ....'
);

$subject = 'Web Site Error - 403 Forbidden';
$message = 'A user received a 403 forbidden error. You have requested to track this activity on the web site.' . "\r\n";

if (preg_match('/(.*)\.archive\.org/i', $cur_data['reverse_host'])) {

array_push($given_headers, 'Cc: info@archive.org');
array_push($given_headers, 'Disposition-Notification-To: ...');
array_push($given_headers, 'Return-Receipt-To: ...');

$subject = 'Internet Archive - Virus';
$message = 'A user using your server was redirected to a virus and did not detect it.' . "\r\n";
$message .= $message . 'Please test using the following link: https://web.archive.org/save/...' . "\r\n";

}

$message = $message . "\r\n";
$message = $message . 'Requested URI: ' . $cur_data['url'] . "\r\n";
$message = $message . "\r\n";
$message = $message . 'IP Address: ' . $cur_data['reverse_ip'] . "\r\n";
$message = $message . 'Host Name: '. $cur_data['reverse_host'] . "\r\n";
$message = $message . "\r\n";
$message = $message . 'The data has been formatted as JSON to help: ' . "\r\n";
$message = $message . "\r\n";
$message = $message . $entry . "\r\n";

$data_add = json_encode(array(
'to' => $to,
'headers' => $headers,
'subject' => $subject,
'message' => $message
), true) . "\r\n";

file_put_contents($file_path, $data_add, FILE_APPEND);
wp_mail( $to, $subject, $message, $headers = $given_headers );
}
?>
