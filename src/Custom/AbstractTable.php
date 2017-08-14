<?php
/**
 * Created by PhpStorm.
 * User: alpipego
 * Date: 21.07.2017
 * Time: 12:56
 */

namespace Alpipego\WpLib\Custom;

use Alpipego\WpLib\Database\DatabaseInterface;

abstract class AbstractTable implements TableInterface
{
    const NAME = '';
    const SCHEMA = '';
    const VERSION = '';
    protected $db;

    public function __construct(DatabaseInterface $database)
    {
        $this->db = $database;
    }

    public function run()
    {
        if ($this->needsUpdate()) {
            $queries = $this->create();
            $errors  = array_filter($queries, function ($query) {
                return strpos($query, 'database error') !== false;
            });
            if (empty($errors)) {
                $this->saveVersion();
            }
        }
    }

    protected function needsUpdate() : bool
    {
        return static::VERSION !== get_option('n1_' . static::NAME . '_version');
    }

    public function create()
    {
        $sql = $this->getSchema();
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        return dbDelta($sql);
    }

    public function getSchema()
    {
        $table  = $this->db->getPrefix() . static::NAME;
        $schema = static::SCHEMA;

        return "CREATE TABLE {$table} (
			{$schema}
		) {$this->db->getCollate()};";
    }

    protected function saveVersion() : bool
    {
        return update_option('n1_' . static::NAME . '_version', static::VERSION);
    }
}
