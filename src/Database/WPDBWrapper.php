<?php
/**
 * Created by PhpStorm.
 * User: alpipego
 * Date: 21.07.2017
 * Time: 13:09
 */

namespace Alpipego\WpLib\Database;

use wpdb;

final class WPDBWrapper implements DatabaseInterface
{
    private $db;

    public function __construct()
    {
        $this->db = $GLOBALS['wpdb'];
    }

    public function getPrefix() : string
    {
        return $this->db->prefix;
    }

    public function getCollate() : string
    {
        return $this->db->get_charset_collate();
    }

    public function getDb() : wpdb
    {
        return $this->db;
    }
}
