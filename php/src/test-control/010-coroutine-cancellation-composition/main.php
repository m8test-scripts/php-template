<?php
global $coroutines, $script, $logger;
$a=$coroutines->newScope(null); $b=$coroutines->newScope(null); $c=$coroutines->newScope(null); $d=$coroutines->newScope(null); $e=$coroutines->newScope(null);
$timer=$script->getThreads()->getMain()->getTimer();
$a->withTimeout(75,function($s){return $s->delay(1000);})->catchError(function($s,$err){return $s->resolve("fallback");})->onError(function($err) use($logger){$logger->info("coroutine:php:timeout-cancellation-preserved");});
$b->withTimeoutOrNull(75,function($s){return $s->delay(1000);})->onSuccess(function($v) use($logger){if($v!==null)throw new RuntimeException("expected null");$logger->info("coroutine:php:timeout-or-null-ok");})->onError(function($err){throw new RuntimeException("timeoutOrNull failed");})->onComplete(function() use($logger){$logger->info("coroutine:php:timeout-or-null-complete");});
$timed=0;
$c->loopWithTimeout(75,function($s) use (&$timed){$timed++;return $s->delay(1000)->then(function($inner,$v){return $inner->resolve(true);});})->onError(function($err) use (&$timed,$logger){if($timed!==1)throw new RuntimeException("timed loop count");$logger->info("coroutine:php:loop-timeout-cancelled-wait");});
$count=0;
$d->loop(function($s) use (&$count,$logger){$count++;$logger->info("coroutine:php:loop-iteration:".$count);return $s->resolve($count<3);})->onComplete(function() use (&$count,$logger){if($count!==3)throw new RuntimeException("loop count");$logger->info("coroutine:php:loop-completed-count:3");});
$e->withTimeoutOrNull(500,function($s){return $s->delay(25)->then(function($inner,$v){return $inner->resolve(23);});})->onSuccess(function($v) use($logger){if($v===null||(int)$v!==23)throw new RuntimeException("composed value");$logger->info("coroutine:php:composed-wait-ok");})->onError(function($err){throw new RuntimeException("composed failed");})->onComplete(function() use($logger){$logger->info("coroutine:php:composed-wait-complete");});
$timer->setTimeout(function($p) use($a,$logger){$a->cancel("done");$logger->info("coroutine:php:cancellation-gate");},3000);
