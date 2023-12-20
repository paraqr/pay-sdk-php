<?php

namespace paraqr\payment\base;

trait Singleton {

    /**
     * @var static
     */
    protected static $instance;

    /**
     * @param mixed ...$args
     * @return static
     */
    static function instance(...$args) {
        if (!isset(static::$instance)) {
            static::$instance = new static(...$args);
        }
        return static::$instance;
    }
}