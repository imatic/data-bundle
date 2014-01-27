<?php

namespace Imatic\Bundle\DataBundle\Data\Command;

interface ParametersAwareInterface
{
    /**
     * @return mixed[]
     */
    public function getParameters();

    /**
     * @param $name
     * @param mixed|null $default
     * @return mixed
     */
    public function getParameter($name, $default = null);
}
