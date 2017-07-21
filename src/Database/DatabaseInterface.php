<?php
/**
 * Created by PhpStorm.
 * User: alpipego
 * Date: 21.07.2017
 * Time: 13:08
 */

namespace Alpipego\WpLib\Database;

interface DatabaseInterface
{
    public function getPrefix() : string;
    public function getCollate() : string;
}
