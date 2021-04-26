<?php
/**
 * @author  OptiWise Technologies B.V. <info@optiwise.nl>
 * @project SnelstartApiPHP
 */

namespace SnelstartPHP\Connector;

use Ramsey\Uuid\UuidInterface;
use SnelstartPHP\Exception\SnelstartResourceNotFoundException;
use SnelstartPHP\Mapper\VerkoopordersjabloonMapper;
use SnelstartPHP\Model\Verkoopordersjabloon;
use SnelstartPHP\Request\VerkoopordersjabloonRequest;

final class VerkoopordersjabloonConnector extends BaseConnector
{
    public function find(UuidInterface $id): ?Verkoopordersjabloon
    {
        try {
            $mapper = new VerkoopordersjabloonMapper();
            $request = new VerkoopordersjabloonRequest();

            return $mapper->find($this->connection->doRequest($request->find($id)));
        } catch (SnelstartResourceNotFoundException $e) {
            return null;
        }
    }

    /**
     * @return Verkoopordersjabloon[]|iterable
     * @psalm-return iterable<int, Verkoopordersjabloon>
     */
    public function findAll(): iterable
    {
        $mapper = new VerkoopordersjabloonMapper();
        $request = new VerkoopordersjabloonRequest();

        return $mapper->findAll($this->connection->doRequest($request->findAll()));
    }
}