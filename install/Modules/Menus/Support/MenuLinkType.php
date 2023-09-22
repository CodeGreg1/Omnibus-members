<?php

namespace Modules\Menus\Support;

class MenuLinkType
{
    /**
     * @var string Default
     */
    const DEFAULT = 'Default';

    /**
     * @var string BLOG
     */
    const BLOG = 'Blog';

    /**
     * @var string PAGE
     */
    const PAGE = 'Page';

    /**
     * @var string CUSTOM
     */
    const CUSTOM = 'Custom';

    /**
     * List of menu types
     *
     * @return array
     */
    public static function lists()
    {
        return [
            self::DEFAULT => self::DEFAULT,
            self::BLOG => self::BLOG,
            self::PAGE => self::PAGE,
            self::CUSTOM => self::CUSTOM
        ];
    }
}