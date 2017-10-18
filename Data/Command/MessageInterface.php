<?php
namespace Imatic\Bundle\DataBundle\Data\Command;

interface MessageInterface
{
    /**
     * @return string
     */
    public function getType();

    /**
     * @return array
     */
    public function getParameters();

    /**
     * @return string
     */
    public function getMessage();

    /**
     * @return string
     */
    public function getText();

    /**
     * @return string
     */
    public function getTranslationDomain();

    /**
     * @param string $translationDomain
     */
    public function setTranslationDomain($translationDomain);

    /**
     * @param string $prefix
     */
    public function setPrefix($prefix);

    /**
     * @return string
     */
    public function getPrefix();

    /**
     * @return string
     */
    public function __toString();
}
