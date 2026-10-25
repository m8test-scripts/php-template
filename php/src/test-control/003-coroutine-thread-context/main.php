<?php
global $coroutines, $script, $logger;
$scope = $coroutines->newScope(null);
$timer = $script->getThreads()->getMain()->getTimer();
$scope->launch(function ($context) {
    $context->setName("script-main");
    $context->setDispatcher(function ($dispatchers) { return $dispatchers->getScriptMain(); });
    if ($context->getName() !== "script-main") throw new RuntimeException("coroutine context name mismatch");
}, null, function ($s) use ($script, $logger) {
    if (!$script->getThreads()->getCurrent()->isCurrent()) throw new RuntimeException("script-main dispatcher mismatch");
    $logger->info("coroutine:php:script-main-verified");
});
$capturedScriptValue = "php-captured";
$otherTaskProgress = [false];
$timer->setTimeout(function ($params) use (&$otherTaskProgress, $logger) {
    $otherTaskProgress[0] = true;
    $logger->info("coroutine:php:other-task-progressed");
}, 50);
$scope->launch(function ($context) { $context->setDispatcher(function ($dispatchers) { return $dispatchers->getIO(); }); }, null, function ($s) use ($capturedScriptValue, $logger) {
    if ($capturedScriptValue !== "php-captured") throw new RuntimeException("captured script value mismatch");
    $logger->info("coroutine:php:io-captured-value-verified");
});
$timer->setTimeout(function ($params) use ($coroutines, $scope, $logger) {
    $scope->launch(null, null, function ($s) use ($coroutines, $scope, $logger) {
        $logger->info("coroutine:php:launch");
        $scope->_async(null, null, function ($asyncScope) use ($logger) {
            $logger->info("coroutine:php:async");
            return 7;
        })->onSuccess(function ($value) use ($coroutines, $scope, $logger) {
            if ((int) $value !== 7) throw new RuntimeException("async value mismatch: " . $value);
            $logger->info("coroutine:php:async-success");
            $scope->withContext(function ($context) {}, function ($contextScope) use ($logger) {
                $logger->info("coroutine:php:with-context");
                return 9;
            })->onSuccess(function ($contextValue) use ($coroutines, $scope, $logger) {
                $coroutines->getFlows()->of(1, 2)->map(function ($flowScope, $item) use ($logger) {
                    $logger->info("coroutine:php:flow:" . $item);
                    return $flowScope->resolve($item * 2);
                })->toList($scope)->onSuccess(function ($values) use ($logger) {
                    if ($values->size() !== 2 || (int) $values->get(0) !== 2 || (int) $values->get(1) !== 4) throw new RuntimeException("flow mapped values mismatch");
                    $logger->info("coroutine:php:flow-complete");
                });
            });
        });
    });
}, 100);
$scope->delay(200)->onSuccess(function ($value) use ($capturedScriptValue, &$otherTaskProgress, $logger) {
    if ($capturedScriptValue !== "php-captured") throw new RuntimeException("delayed script value mismatch");
    if (!$otherTaskProgress[0]) throw new RuntimeException("other task did not progress during delay");
    $logger->info("coroutine:php:delayed-captured-value-verified");
});
$timer->setTimeout(function ($params) use ($logger, $scope) {
    $logger->info("coroutine:php:gate");
    $scope->cancel("done");
}, 3500);
$scope->delay(5000)->onComplete(function () use ($logger) {
    $logger->info("coroutine:php:cancelled-work-complete");
});
