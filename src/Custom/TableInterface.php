<?php
/**
 * Created by PhpStorm.
 * User: alpipego
 * Date: 21.07.2017
 * Time: 12:54
 */

namespace Alpipego\WpLib\Custom;

interface TableInterface
{
    public function create();

    public function getSchema();
}
