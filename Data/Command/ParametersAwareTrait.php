<?php

namespace Imatic\Bundle\DataBundle\Data\Command;

trait ParametersAwareTrait
{

    /**
     * {@inheritdoc}
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * {@inheritdoc}
     */
    public function getParameter($name, $default = null)
    {
        if (array_key_exists($name, $this->parameters)) {
            return $default;
        }

        return $this->parameters[$name];
    }
}
