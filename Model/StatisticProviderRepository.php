<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
declare(strict_types=1);

namespace Alekseon\CustomFormsStatistics\Model;

/**
 *
 */
class StatisticProviderRepository
{

    /**
     * @var array
     */
    private $statisticProviders;

    /**
     * @param array $statisticProviders
     */
    public function __construct(
        array $statisticProviders = []
    ) {
        $this->statisticProviders = $statisticProviders;
    }

    /**
     * @param $attribute
     * @return mixed
     */
    public function getStatisticProviderByAttribute($attribute)
    {
        $frontendInput = $attribute->getFrontendInput();
        if (!isset($this->statisticProviders[$frontendInput]['provider'])) {
            $this->statisticProviders[$frontendInput]['provider'] = false;
            if (isset($this->statisticProviders[$frontendInput]['factory'])) {
                $factory = $this->statisticProviders[$frontendInput]['factory'];
                $this->statisticProviders[$frontendInput]['provider'] = $factory->create();
            }
        }

        $provider = $this->statisticProviders[$frontendInput]['provider'];
        if ($provider) {
            $provider->setAttribute($attribute);
        }
        return $provider;
    }
}
