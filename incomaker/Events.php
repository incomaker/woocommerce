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

class Events implements Singletonable
{
    public function __construct()
    {
        add_action('user_register', array($this, 'incomaker_user_register', 10, 1));
        add_action('wp_login', array($this, 'incomaker_user_login', 10, 2));
    }

    public function incomaker_user_register($user_id)
    {

    }

    public function incomaker_user_login($user_id)
    {

    }

    private static $singleton = null;

    public static function getInstance()
    {
        if (self::$singleton == null) {
            self::$singleton = new Events();
        }
        return self::$singleton;
    }
}