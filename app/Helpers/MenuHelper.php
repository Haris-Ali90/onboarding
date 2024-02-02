<?php

/**
 * MenuHelper
 *
 * @author Ali Nawaz <ali.nawaz@vservices.com>
 * @date   12/12/18
 */

/**
 * Return if a menu has childrens
 *
 * @param object $menus
 * @param integer $menuId
 *
 * @return bool
 */
function menuHasChildren($menus, $menuId)
{
    foreach ($menus as $menu) {
        if ($menu->parent_id == $menuId) {
            return true;
        }
    }

    return false;
}
