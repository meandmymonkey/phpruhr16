<?php

namespace Workshop;

use Pimple\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TodoController
{
    /** @var  Container */
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function todoAction(Request $request)
    {
        $db = new \PDO(
            'mysql:host=localhost;dbname=phpruhr16',
            'root',
            '',
            [
                \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
            ]
        );
        
        $id = $request->query->get('id');
        
        $result = $db->query('SELECT * FROM todo WHERE id = ' . $id);
        $todo = $result->fetch(\PDO::FETCH_ASSOC);
        
        return new Response(
            $this->container['twig']->render(
                'todo.html.twig',
                [
                    'todo' => $todo
                ]
            )
        );
    }
}