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
    protected $db;

    public function __construct(DatabaseInterface $database)
    {
        $this->db = $database;
    }

    public function create()
    {
        $sql = $this->getSchema();
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    public function getSchema()
    {
        $table  = $this->db->getPrefix() . self::NAME;
        $schema = $this->schema;

        return "CREATE TABLE {$table} (
			$schema
		) {$this->db->getCollate()};";
    }
}
