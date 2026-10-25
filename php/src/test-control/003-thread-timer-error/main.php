<?php
global $script, $logger;
$timer = $script->getThreads()->getMain()->getTimer();
$timer->setImmediate(function ($params) use ($logger, $timer) {
    $logger->info("thread-context:php:timer-error");
    $timer->missingTimerCallbackMethod();
});
