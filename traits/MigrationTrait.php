<?php

namespace app\traits;

/**
 * Provide a options to migrations
 *
 * Trait MigrationTrait
 * @package app\traits
 */
trait MigrationTrait
{
    public $tableOptions = null;

    /**
     * Return table options according to database
     *
     * @return string|null
     */
    public function getTableOptions()
    {
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $this->tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        return $this->tableOptions;
    }
}