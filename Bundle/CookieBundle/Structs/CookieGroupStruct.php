<?php
/**
 * Shopware 5
 * Copyright (c) shopware AG
 *
 * According to our dual licensing model, this program can be used either
 * under the terms of the GNU Affero General Public License, version 3,
 * or under a proprietary license.
 *
 * The texts of the GNU Affero General Public License with an additional
 * permission and of our proprietary license can be found at and
 * in the LICENSE file you have received along with this program.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * "Shopware" is a registered trademark of shopware AG.
 * The licensing of the program under the AGPLv3 does not imply a
 * trademark license. Therefore any rights, title and interest in
 * our trademarks remain entirely with us.
 */

namespace Shopware\Bundle\CookieBundle\Structs;

use Shopware\Bundle\CookieBundle\CookieCollection;

class CookieGroupStruct implements \JsonSerializable
{
    const TECHNICAL = 'technical';
    const COMFORT = 'comfort';
    const PERSONALIZATION = 'personalization';
    const STATISTICS = 'statistics';
    const OTHERS = 'others';

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $label;

    /**
     * @var string
     */
    public $description;

    /**
     * @var CookieCollection
     */
    public $cookies;

    /**
     * Only used by the technical group, do not use otherwise!
     *
     * @var bool
     */
    private $required;

    /**
     * @param string $name
     * @param string $label
     * @param string $description
     * @param bool $required
     */
    public function __construct($name, $label, $description = '', $required = false)
    {
        $this->name = $name;
        $this->label = $label;
        $this->description = $description;
        $this->required = $required;
        $this->cookies = new CookieCollection();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return bool
     */
    public function isRequired()
    {
        return $this->required;
    }

    /**
     * @return CookieCollection
     */
    public function getCookies()
    {
        return $this->cookies;
    }

    /**
     * @param CookieStruct $cookieStruct
     */
    public function addCookie(CookieStruct $cookieStruct)
    {
        $this->cookies->add($cookieStruct);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
