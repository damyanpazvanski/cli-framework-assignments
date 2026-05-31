<?php

namespace CommonF\Interfaces;

interface ILoggerAdapter
{
    public function log(string $msg, string $label, int $colorCode);
    public function success(string $msg, string $label);
    public function warning(string $msg, string $label);
    public function error(string $msg, string $label);
}
