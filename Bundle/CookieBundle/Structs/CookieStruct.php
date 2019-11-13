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

class CookieStruct implements \JsonSerializable
{
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
    public $groupName;

    /**
     * @var CookieGroupStruct
     */
    public $group;

    /**
     * @var string
     */
    private $matchingPattern;

    /**
     * @param string $name
     * @param string $matchingPattern
     * @param string $label
     * @param string $groupName
     */
    public function __construct($name, $matchingPattern, $label, $groupName = CookieGroupStruct::OTHERS)
    {
        $this->name = $name;
        $this->matchingPattern = $matchingPattern;
        $this->label = $label;
        $this->groupName = $groupName;
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
    public function getMatchingPattern()
    {
        return $this->matchingPattern;
    }

    /**
     * @param string $matchingPattern
     */
    public function setMatchingPattern($matchingPattern)
    {
        $this->matchingPattern = $matchingPattern;
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
    public function getGroupName()
    {
        return $this->groupName;
    }

    /**
     * @param string $groupName
     */
    public function setGroupName($groupName)
    {
        $this->groupName = $groupName;
    }

    /**
     * @return CookieGroupStruct
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param CookieGroupStruct $group
     */
    public function setGroup(CookieGroupStruct $group)
    {
        $this->group = $group;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $data = get_object_vars($this);
        unset($data['group']);

        return $data;
    }
}
