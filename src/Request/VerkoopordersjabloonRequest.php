<?php
/**
 * @author  OptiWise Technologies B.V. <info@optiwise.nl>
 * @project SnelstartApiPHP
 */

namespace SnelstartPHP\Request;

use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use Ramsey\Uuid\UuidInterface;

final class VerkoopordersjabloonRequest extends BaseRequest
{
    public function findAll(): RequestInterface
    {
        return new Request("GET", "verkoopordersjablonen");
    }

    public function find(UuidInterface $id): RequestInterface
    {
        return new Request("GET", "verkoopordersjablonen/" . $id->toString());
    }

}