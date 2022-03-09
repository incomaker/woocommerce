<?php
/*
 * Incomaker for Woocommerce
 * Copyright (C) 2021 Incomaker s.r.o.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Incomaker;

require_once('../../../wp-load.php');
require_once(plugin_dir_path('incomaker') . '/vendor/autoload.php');

class Feed
{
    protected $manager;

    protected $xmlExport;

    public function __construct()
    {
        $this->manager = new ExportManager();
    }

    public function execute()
    {
        try {
            $xmlExport = $this->manager->getExport($_GET["type"]);
            if ($xmlExport == NULL) throw new \BadFunctionCallException();
        } catch (\Exception $e) {
            header('HTTP/1.0 400 Bad Request');
            echo "400-1 Invalid command";
            return;
        }

        if (!isset($_GET["key"]) || get_option("incomaker_option")['api_key'] != $_GET["key"]) {
            header('HTTP/1.0 401 Unauthorized');
            echo "401-2 Invalid API key";
            return;
        }

        try {
            $xmlExport->setLimit(isset($_GET["limit"]) ? $_GET["limit"] : NULL);
            $xmlExport->setOffset(isset($_GET["offset"]) ? $_GET["offset"] : NULL);
            $xmlExport->setId(isset($_GET["id"]) ? $_GET["id"] : NULL);
            $xmlExport->setSince(isset($_GET["since"]) ? $_GET["since"] : NULL);    //TODO Since date format check
        } catch (InvalidArgumentException $e) {
            header('HTTP/1.0 400 Bad Request');
            echo "400-2 " . $e->getMessage();
            return;
        }

        header("Content-Type: application/xml; Charset=UTF-8");

        try {
            echo $xmlExport->createXmlFeed();
        } catch (Exception $e) {
            header('HTTP/1.0 510 Not extended');
            echo "510-1 " . $e->getMessage();
            return;
        }
    }
}

(new Feed())->execute();