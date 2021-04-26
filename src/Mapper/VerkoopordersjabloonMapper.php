<?php
/**
 * @author  IntoWebDevelopment <info@intowebdevelopment.nl>
 * @project SnelstartApiPHP
 */

namespace SnelstartPHP\Mapper;

use Psr\Http\Message\ResponseInterface;
use SnelstartPHP\Model\Verkoopordersjabloon;

final class VerkoopordersjabloonMapper extends AbstractMapper
{
    public function find(ResponseInterface $response): ?Verkoopordersjabloon
    {
        $this->setResponseData($response);
        return $this->mapArrayDataToModel(new Verkoopordersjabloon());
    }

    public function findAll(ResponseInterface $response): \Generator
    {
        $this->setResponseData($response);

        foreach ($this->responseData as $data) {
            yield $this->mapArrayDataToModel(new Verkoopordersjabloon(), $data);
        }
    }
}