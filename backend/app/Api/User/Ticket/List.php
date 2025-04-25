<?php

/*
 * This file is part of MyMythicalID.
 * Please view the LICENSE file that was distributed with this source code.
 *
 * # MythicalSystems License v2.0
 *
 * ## Copyright (c) 2021–2025 MythicalSystems and Cassian Gherman
 *
 * Breaking any of the following rules will result in a permanent ban from the MythicalSystems community and all of its services.
 */

use MyMythicalID\App;
use MyMythicalID\Chat\User\Session;
use MyMythicalID\Chat\Tickets\Tickets;
use MyMythicalID\Chat\columns\UserColumns;

$router->get('/api/user/ticket/list', function () {
    App::init();
    $appInstance = App::getInstance(true);
    $appInstance->allowOnlyGET();
    $s = new Session($appInstance);
    $tickets = Tickets::getAllTicketsByUser($s->getInfo(UserColumns::UUID, false), 150);
    $appInstance->OK('Tickets', [
        'tickets' => $tickets,
    ]);

});
