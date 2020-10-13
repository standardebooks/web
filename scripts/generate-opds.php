<?
require_once('/standardebooks.org/web/lib/Core.php');

use function Safe\krsort;
use function Safe\getopt;
use function Safe\preg_replace;
use function Safe\sort;

$longopts = array("webroot:", "weburl:");
$options = getopt("", $longopts);
$webRoot = $options["webroot"] ?? "/standardebooks.org/web";
$webUrl = $options["weburl"] ?? "https://standardebooks.org";

$contentFiles = explode("\n", trim(shell_exec('find ' . escapeshellarg($webRoot . '/www/ebooks/') . ' -name "content.opf" | sort') ?? ''));
$allEbooks = [];
$newestEbooks = [];
$subjects = [];
$ebooksBySubject = [];

// Iterate over all ebooks to build the various feeds
foreach($contentFiles as $path){
	if($path == '')
		continue;

	try{
		$ebookWwwFilesystemPath = preg_replace('|/content\.opf|ius', '', $path) ?? '';

		$ebook = new Ebook($ebookWwwFilesystemPath);

		$allEbooks[$ebook->ModifiedTimestamp->format('Y-m-d\TH:i:s\Z') . ' ' . $ebook->Identifier] = $ebook;
		$newestEbooks[$ebook->Timestamp->format('Y-m-d\TH:i:s\Z') . ' ' . $ebook->Identifier] = $ebook;

		foreach($ebook->Tags as $tag){
			// Add the book's subjects to the main subjects list
			if(!in_array($tag->Name, $subjects)){
				$subjects[] = $tag->Name;
			}

			// Sort this ebook by subject
			$ebooksBySubject[$tag->Name][$ebook->Timestamp->format('Y-m-d\TH:i:s\Z') . ' ' . $ebook->Identifier] = $ebook;
		}
	}
	catch(\Exception $ex){
		print('Failed to generate OPDS entry for `' . $ebookWwwFilesystemPath . '`. Exception: ' . $ex->getMessage());
		continue;
	}
}

// Create the subjects navigation document
sort($subjects);
$subjectNavigationEntries = [];
foreach($subjects as $subject){
	$summary = number_format(sizeof($ebooksBySubject[$subject])) . ' Standard Ebook';
	if(sizeof($ebooksBySubject[$subject]) != 1){
		$summary .= 's';
	}
	$summary .= ' tagged with “' . strtolower($subject) . ',” most-recently-released first.';

	// We leave the updated timestamp blank, as it will be filled in when we generate the individual feeds
	$subjectNavigationEntries[] = new OpdsNavigationEntry('/opds/subjects/' . Formatter::MakeUrlSafe($subject), 'subsection', 'navigation', null, $subject, $summary);
}
$subjectsFeed = new OpdsNavigationFeed('/opds/subjects', 'Standard Ebooks by Subject', '/opds', $subjectNavigationEntries);
$subjectsFeed->Save(WEB_ROOT . '/opds/subjects/index.xml');

// Now generate each individual subject feed
foreach($ebooksBySubject as $subject => $ebooks){
	krsort($ebooks);
	$subjectFeed = new OpdsAcquisitionFeed('/opds/subjects/' . Formatter::MakeUrlSafe((string)$subject), (string)$subject, '/opds/subjects', $ebooks);
	$subjectFeed->Save(WEB_ROOT . '/opds/subjects/' . Formatter::MakeUrlSafe((string)$subject) . '.xml');
}

// Create the 'all' feed
krsort($allEbooks);
$allFeed = new OpdsAcquisitionFeed('/opds/all', 'All Standard Ebooks', '/opds', $allEbooks, true);
$allFeed->Save(WEB_ROOT . '/opds/all.xml');

// Create the 'newest' feed
krsort($newestEbooks);
$newestEbooks = array_slice($newestEbooks, 0, 30);
$newestFeed = new OpdsAcquisitionFeed('/opds/new-releases', 'Newest 30 Standard Ebooks', '/opds', $newestEbooks);
$newestFeed->Save(WEB_ROOT . '/opds/new-releases.xml');

?>
