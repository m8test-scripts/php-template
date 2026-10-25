<?php
global $script, $logger;
$timer = $script->getThreads()->getMain()->getTimer();
$timeoutArgs = false; $immediateDone = false; $worker = false; $count = 0; $interval = 0; $intervalArgs = false;
$nestedResult = $timer->runWithThreadContext(function () use ($timer) {
    return $timer->runWithThreadContext(function () { return "nested"; });
});
$timer->setImmediate(function ($params) use (&$immediateDone, $logger) { $immediateDone = true; $logger->info("thread-context:php:immediate"); });
$timer->setTimeout(function ($params) use (&$timeoutArgs, $logger) {
    $timeoutArgs = $params !== null;
    $logger->info("thread-context:php:timeout");
}, 100, "payload", null);
$cancelledTimeout = $timer->setTimeout(function ($params) { throw new RuntimeException("cleared timeout ran"); }, 1000);
$timer->clearTimeout($cancelledTimeout);
$cancelledImmediate = $timer->setImmediate(function ($params) { throw new RuntimeException("cleared immediate ran"); });
$timer->clearImmediate($cancelledImmediate);
$interval = $timer->setInterval(function ($params) use (&$count, &$interval, &$intervalArgs, $script, $logger) {
    $intervalArgs = $params !== null;
    $count++;
    if ($count === 2) { $script->getThreads()->getMain()->getTimer()->clearInterval($interval); $logger->info("thread-context:php:interval"); }
}, 75, "tick");
$script->getThreads()->onStart(function ($thread) use ($logger) {
    if (!$thread->isCurrent() || $thread->isMain() || !$thread->isAlive() || $thread->isInterrupted() || (string) $thread->getName() !== "thread-context-worker") throw new RuntimeException("thread start context mismatch");
    $logger->info("thread-context:php:thread-start");
});
$script->getThreads()->onExit(function ($thread) use ($logger) {
    if (!$thread->isCurrent() || $thread->isMain() || !$thread->isAlive() || $thread->isInterrupted() || (string) $thread->getName() !== "thread-context-worker") throw new RuntimeException("thread exit context mismatch");
    $logger->info("thread-context:php:thread-exit");
});
$script->getThreads()->start("thread-context-worker", function ($thread) use (&$worker, $logger) { $worker = true; $logger->info("thread-context:php:thread"); });
$timer->setTimeout(function ($params) use (&$timeoutArgs, &$immediateDone, &$worker, &$count, &$intervalArgs, &$nestedResult, $logger) {
    if ($nestedResult !== "nested" || !$timeoutArgs || !$intervalArgs || !$immediateDone || !$worker || $count !== 2) throw new RuntimeException("timer callback state mismatch");
    $logger->info("thread-context:php:verified");
}, 500);
