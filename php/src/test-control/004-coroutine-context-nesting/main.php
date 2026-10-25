<?php
global $coroutines, $script, $logger;
$scope = $coroutines->newScope(null);
$timer = $script->getThreads()->getMain()->getTimer();
$captured = "php-nested";
$otherTaskProgress = [false];
$timer->setTimeout(function ($params) use (&$otherTaskProgress, $logger) {
    $otherTaskProgress[0] = true;
    $logger->info("nest:php:other-task-progressed");
}, 50);
$scope->launch(function ($context) { $context->setDispatcher(function ($dispatchers) { return $dispatchers->getIO(); }); }, null, function ($outer) use ($scope, $captured, $logger) {
    if ($captured !== "php-nested") throw new RuntimeException("outer capture unavailable");
    $logger->info("nest:php:outer-started");
    $scope->launch(function ($context) { $context->setDispatcher(function ($dispatchers) { return $dispatchers->getScriptMain(); }); }, null, function ($inner) use ($captured, $logger) {
        if ($captured !== "php-nested") throw new RuntimeException("nested capture unavailable");
        $logger->info("nest:php:inner-callback");
    });
    if ($captured !== "php-nested") throw new RuntimeException("outer capture not restored");
    $logger->info("nest:php:outer-resumed");
});
$scope->delay(200)->onSuccess(function ($value) use ($otherTaskProgress, $captured, $logger) {
    if (!$otherTaskProgress[0] || $captured !== "php-nested") throw new RuntimeException("delay state mismatch");
    $logger->info("nest:php:delay-resumed");
});
$timer->setTimeout(function ($params) use ($scope, $logger) {
    $logger->info("nest:php:gate");
    $scope->cancel("done");
}, 1500);
