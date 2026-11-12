<?php

// 必须使用相对于src目录的路径
require_once 'com/example/tool/ToolB.php';

// 使用 ToolB.php 中的 ToolB 类
use com\example\tool\ToolB;

/** 通过use使用java中的类, 不能使用 use xxx as xxx 只能使use,因为下面这句话会被替换为 import android.widget.Button, 这样写的目的是有代码提示 **/

// 声明全局变量, 如果不声明的话也可以但是没有代码提示
global $logger, $stringResources;
ToolB::methodB($logger);

/** @var m8test_java\com\m8test\script\core\api\ui\compose\ComposeView $composeView */
$composeView->create(function ($slot) use ($stringResources) {
    // 创建一个垂直布局
    $slot->Column(function ($column) use ($stringResources) {
        // 设置布局的水平对齐方式为水平居中
        $column->setHorizontalAlignment(function ($alignments) {
            return $alignments->getCenterHorizontally();
        });
        // 设置布局的垂直排列方式为居中
        $column->setVerticalArrangement(function ($arrangements) {
            return $arrangements->getCenter();
        });
        // 设置布局填满父容器
        $column->setModifier(function ($modifier) {
            return $modifier->fillMaxSize(1.0);
        });
        // 设置垂直布局的内容
        $column->setContent(function ($columnSlot) use ($stringResources) {
            // 1. 创建状态
            $state = $columnSlot->remember(function () use ($columnSlot) {
                return $columnSlot->mutableStateOf(0);
            });
            $columnSlot->Text(function ($text) use ($state, $stringResources) {
                // 2. 组合期间读取状态，状态变化时自动重组
                $text->setText($stringResources->getString("demo.counter.label") . $state->getValue());
            });
            $columnSlot->TextButton(function ($button) use ($state, $stringResources) {
                $button->onClick(function () use ($state) {
                    // 4. 点击按钮时， 更新状态的值
                    $state->setValue($state->getValue() + 1);
                });
                $button->setContent(function ($buttonSlot) use ($stringResources) {
                    $buttonSlot->Text(function ($text) use ($stringResources) {
                        $text->setText($stringResources->getString("demo.counter.button"));
                    });
                });
            });
            $columnSlot->Text(function ($text) use ($stringResources) {
                $text->setText($stringResources->getString("demo.language"));
            });
        });
    });
});

// 启动界面
/** @var m8test_java\com\m8test\script\core\api\ui\Activity $activity */
$activity->start();
