<?
require_once('/standardebooks.org/web/lib/Core.php');

// Delete unconfirmed newsletter subscribers who are more than a week old
Db::Query('delete from NewsletterSubscribers where IsConfirmed = false and datediff(utc_timestamp(), Timestamp) >= 7');
?>
