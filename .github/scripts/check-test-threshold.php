<?php

declare(strict_types=1);

if ($argc !== 3) {
    fwrite(STDERR, "Usage: php check-test-threshold.php <junit-report.xml> <minimum-percent>\n");
    exit(2);
}

$reportPath = $argv[1];
$minimumPercent = filter_var(
    $argv[2],
    FILTER_VALIDATE_INT,
    ['options' => ['min_range' => 0, 'max_range' => 100]]
);

if ($minimumPercent === false) {
    fwrite(STDERR, "The minimum percentage must be an integer between 0 and 100.\n");
    exit(2);
}

if (!is_file($reportPath)) {
    fwrite(STDERR, "PHPUnit did not create the JUnit report: {$reportPath}\n");
    exit(1);
}

libxml_use_internal_errors(true);
$report = simplexml_load_file($reportPath, SimpleXMLElement::class, LIBXML_NONET);

if ($report === false) {
    fwrite(STDERR, "Could not read PHPUnit's JUnit report.\n");

    foreach (libxml_get_errors() as $error) {
        fwrite(STDERR, trim($error->message) . "\n");
    }

    exit(1);
}

$testCases = $report->xpath('//testcase');

if ($testCases === false || count($testCases) === 0) {
    fwrite(STDERR, "PHPUnit did not report any test cases.\n");
    exit(1);
}

$unpassedResults = ['failure', 'error', 'skipped', 'warning', 'risky'];
$passed = 0;

foreach ($testCases as $testCase) {
    $hasUnpassedResult = false;

    foreach ($testCase->children() as $result) {
        if (in_array($result->getName(), $unpassedResults, true)) {
            $hasUnpassedResult = true;
            break;
        }
    }

    if (!$hasUnpassedResult) {
        $passed++;
    }
}

$total = count($testCases);
$percentage = ($passed / $total) * 100;

printf(
    "Passed: %d/%d (%.2f%%); required: at least %d%%.\n",
    $passed,
    $total,
    $percentage,
    $minimumPercent
);

if (($passed * 100) < ($minimumPercent * $total)) {
    fwrite(STDERR, "The test pass percentage is below the required threshold.\n");
    exit(1);
}
