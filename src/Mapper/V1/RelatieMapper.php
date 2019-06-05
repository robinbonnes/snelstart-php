<?php
/**
 * @author  IntoWebDevelopment <info@intowebdevelopment.nl>
 * @project SnelstartApiPHP
 * @deprecated
 */

namespace SnelstartPHP\Mapper\V1;

use function array_map;
use Money\Currency;
use Money\Money;
use Psr\Http\Message\ResponseInterface;
use Ramsey\Uuid\Uuid;
use SnelstartPHP\Mapper\AbstractMapper;
use SnelstartPHP\Model\EmailVersturen;
use SnelstartPHP\Model\Land;
use SnelstartPHP\Model\Adres;
use SnelstartPHP\Model\Type as Type;
use SnelstartPHP\Model\V1 as Model;
use SnelstartPHP\Snelstart;

final class RelatieMapper extends AbstractMapper
{
    public static function find(ResponseInterface $response): ?Model\Relatie
    {
        $mapper = new static($response);
        return $mapper->mapResponseToRelatieModel(new Model\Relatie(), $mapper->responseData);
    }

    public static function findAll(ResponseInterface $response): \Generator
    {
        return (new static($response))->mapManyResultsToSubMappers();
    }

    public static function add(ResponseInterface $response): Model\Relatie
    {
        $mapper = new static($response);
        return $mapper->mapResponseToRelatieModel(new Model\Relatie(), $mapper->responseData);
    }

    public static function update(ResponseInterface $response): Model\Relatie
    {
        $mapper = new static($response);
        return $mapper->mapResponseToRelatieModel(new Model\Relatie(), $mapper->responseData);
    }

    /**
     * Map the data from the response to the model.
     */
    public function mapResponseToRelatieModel(Model\Relatie $relatie, array $data = []): Model\Relatie
    {
        $data = empty($data) ? $this->responseData : $data;
        /**
         * @var Model\Relatie $relatie
         */
        $relatie = $this->mapArrayDataToModel($relatie, $data);
        $currency = new Currency(Snelstart::CURRENCY);

        $relatie->setRelatiesoort(array_map(function(string $relatiesoort) {
            return new Type\Relatiesoort($relatiesoort);
        }, $data["relatiesoort"]));

        if (!empty($data["incassoSoort"])) {
            $relatie->setIncassoSoort(new Type\Incassosoort($data["incassoSoort"]));
        }

        if (!empty($data["aanmaningSoort"])) {
            $relatie->setAanmingsoort(new Type\Aanmaningsoort($data["aanmaningSoort"]));
        }

        if ($data["kredietLimiet"] !== null) {
            $relatie->setKredietLimiet(new Money($data["kredietLimiet"], $currency));
        }

        if ($data["factuurkorting"] !== null) {
            $relatie->setFactuurkorting(new Money($data["factuurkorting"], $currency));
        }

        if (!empty($data["vestigingsAdres"])) {
            $relatie->setVestigingsAdres(static::mapAddressToRelatieAddress($data["vestigingsAdres"]));
        }

        if (!empty($data["correspondentieAdres"])) {
            $relatie->setCorrespondentieAdres(static::mapAddressToRelatieAddress($data["correspondentieAdres"]));
        }

        $relatie->setOfferteEmailVersturen(static::mapEmailVersturenField($data["offerteEmailVersturen"]))
                ->setBevestigingsEmailVersturen(static::mapEmailVersturenField($data["offerteEmailVersturen"]))
                ->setFactuurEmailVersturen(static::mapEmailVersturenField($data["factuurEmailVersturen"]))
                ->setAanmaningEmailVersturen(static::mapEmailVersturenField($data["aanmaningEmailVersturen"]));

        return $relatie;
    }

    /**
     * Map the response data to the model.
     */
    public function mapAddressToRelatieAddress(array $address): Adres
    {
        return (new Adres())
            ->setContactpersoon($address["contactpersoon"])
            ->setStraat($address["straat"])
            ->setPostcode($address["postcode"])
            ->setPlaats($address["plaats"])
            ->setLand(Land::createFromUUID(Uuid::fromString($address["land"]["id"])));
    }

    /**
     * Map all data to the EmailVersturen class (added support for subtypes).
     *
     * @param array  $emailVersturen
     * @param string $emailVersturenClass
     * @return EmailVersturen
     */
    public function mapEmailVersturenField(array $emailVersturen, string $emailVersturenClass = EmailVersturen::class): EmailVersturen
    {
        return new $emailVersturenClass(
            $emailVersturen["shouldSend"],
            $emailVersturen["email"],
            $emailVersturen["ccEmail"]
        );
    }

    /**
     * Map many results to the mapper.
     *
     * @return \Generator
     */
    protected function mapManyResultsToSubMappers(): \Generator
    {
        foreach ($this->responseData as $relatieData) {
            yield $this->mapResponseToRelatieModel(new Model\Relatie(), $relatieData);
        }
    }
}