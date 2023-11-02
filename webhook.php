<?php
// Verify that the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Define your repository path and branch
    $repositoryPath = dirname(__FILE__);
    $branch = 'main'; // Replace with your branch name

    // Verify the payload (if you're using a secret token)
    $secret = 'mhook'; // Replace with your secret token
    $payload = file_get_contents('php://input');
    $signature = 'sha1=' . hash_hmac('sha1', $payload, $secret);
 
    if (isset($_SERVER['X-Hub-Signature']) && hash_equals($_SERVER['X-Hub-Signature'], $signature)) {
        // Update the local repository
        $output = shell_exec("cd $repositoryPath && git pull origin $branch 2>&1");

        // Log the output for debugging (you can log to a file or a log service)
        error_log($output);
    } else {
       // header('HTTP/1.0 403 Forbidden');
        //echo 'Forbidden';
        $headers = getallheaders();
        error_log(print_r($headers, true));
        echo 'X-Hub-Signature '. $_SERVER['X-Hub-Signature'];
        echo 'signature '. $signature;
    }
} else {
    header('HTTP/1.0 400 Bad Request');
    echo 'Bad Request';
}