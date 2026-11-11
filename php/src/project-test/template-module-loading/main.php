<?php
require_once 'com/example/tool/ToolB.php';
use com\example\tool\ToolB;
if (!class_exists('com\\example\\tool\\ToolB')) {
    throw new RuntimeException('ToolB could not be loaded from the project source');
}
