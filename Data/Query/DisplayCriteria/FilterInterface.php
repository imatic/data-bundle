<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria;

use Symfony\Component\Form\FormInterface;

interface FilterInterface extends \IteratorAggregate, \Countable, \ArrayAccess
{
    /**
     * @param string $index
     *
     * @return FilterRule
     */
    public function get($index);

    /**
     * @param string $index
     *
     * @return bool
     */
    public function has($index);

    /**
     * @return bool
     */
    public function hasDefaults();

    /**
     * @return FormInterface
     */
    public function getForm();

    /**
     * @return string
     */
    public function getTranslationDomain();
}
