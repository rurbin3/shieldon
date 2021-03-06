<?php
/**
 * This file is part of the Shieldon package.
 *
 * (c) Terry L. <contact@terryl.in>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * php version 7.1.0
 * 
 * @category  Web-security
 * @package   Shieldon
 * @author    Terry Lin <contact@terryl.in>
 * @copyright 2019 terrylinooo
 * @license   https://github.com/terrylinooo/shieldon/blob/2.x/LICENSE MIT
 * @link      https://github.com/terrylinooo/shieldon
 * @see       https://shieldon.io
 */

declare(strict_types=1);

namespace Shieldon\Firewall\Utils;

use Shieldon\Firewall\Utils\Collection;

use function php_sapi_name;
use function session_id;
use function session_start;
use function session_status;

use const PHP_SESSION_NONE;

/*
 * A SESSION wrapper.
 *
 * @since 1.1.0
 */
class Session
{
    /**
     * A session Id.
     *
     * @var string
     */
    public $id;

    /**
     * Constructor.
     * 
     * @param $id Session ID
     */
    public function __construct($id = '')
    {
        $this->id = $id;
    }

    /**
     * Create a Session collection.
     *
     * @return Collection
     */
    public function createFromGlobal(): Collection
    {
        $this->id = '_php_cli_';

        if ((php_sapi_name() !== 'cli')) {

            // @codeCoverageIgnoreStart
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            if (!$this->id) {
                $this->id = session_id();
            }
            // @codeCoverageIgnoreEnd
        }

        // If null, we give it a default value.
        // It happens as in CLI environment.
        if (!isset($_SESSION)) {
            $_SESSION = [];
        }

        $_SESSION['id'] = $this->id;

        return new Collection($_SESSION);
    }
}
