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

use BadFunctionCallException;
use Exception;

class Feed
{
    protected $manager;

    protected $xmlExport;

    public function __construct()
    {
        $this->manager = new ExportManager();
    }

    const ROUTE = 'incomaker/v210';
    const COMMAND = '/feed';

    public function registerRoutes()
    {
        register_rest_route(Feed::ROUTE, Feed::COMMAND, array(
            array(
                'methods' => array('GET'),
                'callback' => array($this, 'execute'),
                'args' => array(
                    'id' => array(
                        'validate_callback' => function ($param, $request, $key) {
                            return !empty($param);
                        }),
                    'limit' => array(
                        'validate_callback' => function ($param, $request, $key) {
                            return is_numeric($param);
                        }),
                    'offset' => array(
                        'validate_callback' => function ($param, $request, $key) {
                            return is_numeric($param);
                        }),
                    'downloadCount' => array(
                        'validate_callback' => function ($param, $request, $key) {
                            return is_numeric($param);
                        }),
                    'since' => array(
                        'validate_callback' => function ($param, $request, $key) {
                            if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])( (0[0-9]|[1-2][0-4]):(0[0-9]|[1-5][0-9]):(0[0-9]|[1-5][0-9]))?$/",$param)) {
                                return true;
                            }
                        }),
                )
            )
        ));
    }

    function execute(\WP_REST_Request $request){

        try {
            $xmlExport = $this->manager->getExport($_GET["type"]);
            if ($xmlExport == NULL) throw new BadFunctionCallException();
        } catch (Exception $e) {
            header('HTTP/1.0 400 Bad Request');
            return "400-1 Invalid command";
        }

        if (!isset($request["key"]) || get_option("incomaker_option")['api_key'] != $request["key"]) {
            header('HTTP/1.0 401 Unauthorized');
            return "401-2 Invalid API key";
        }

        try {
            $xmlExport->setLimit(isset($request["limit"]) ? $request["limit"] : NULL);
            $xmlExport->setOffset(isset($_GET["offset"]) ? $request["offset"] : NULL);
            $xmlExport->setId(isset($request["id"]) ? $request["id"] : NULL);
            $xmlExport->setSince(isset($request["since"]) ? $request["since"] : NULL);
        } catch (InvalidArgumentException $e) {
            header('HTTP/1.0 400 Bad Request');
            return "400-2 " . $e->getMessage();
        }

        try {
            return $xmlExport->createXmlFeed();
        } catch (Exception $e) {
            header('HTTP/1.0 510 Not extended');
            return "510-1 " . $e->getMessage();
        }
    }
}