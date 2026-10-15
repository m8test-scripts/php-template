<?php
use m8test_java\java\lang\StringBuilder;
global $logger, $arrays, $iterables, $objectWrappers;
$builder = new StringBuilder("M8Test");
$builder->append(" PHP");
if ($builder->toString() !== "M8Test PHP") throw new RuntimeException("JVM StringBuilder");
$raw = $arrays->intArrayOf(3, 1, 2);
$values = $objectWrappers->wrapIntArray($raw);
if ($values->getSize() !== 3) throw new RuntimeException("wrapped size");
$values->set(0, 7);
if (!$values->contains(7)) throw new RuntimeException("wrapped array");
$callbackCalled = false;
$logger->withContext(function ($context) use (&$callbackCalled) { $callbackCalled = $context !== null; });
if (!$callbackCalled) throw new RuntimeException("Kotlin function callback");
$list = $iterables->listOf("first", "second");
if ($list->get(1) !== "second") throw new RuntimeException("host List factory");
$logger->info("php-template-jvm-interop:passed");
