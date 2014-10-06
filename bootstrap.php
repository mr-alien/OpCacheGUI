<?php
/**
 * Bootstrap the project
 *
 * PHP version 5.5
 *
 * @author     Pieter Hordijk <info@pieterhordijk.com>
 * @copyright  Copyright (c) 2013 Pieter Hordijk <https://github.com/PeeHaa>
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 * @version    2.0.0
 */
use OpCacheGUI\Format\Byte as ByteFormatter;
use OpCacheGUI\Security\CsrfToken;
use OpCacheGUI\Presentation\Html;

/**
 * Bootstrap the library
 */
require_once __DIR__ . '/src/OpCacheGUI/bootstrap.php';

/**
 * Setup the environment
 */
require_once __DIR__ . '/init.deployment.php';

/**
 * Start the session
 */
session_start();

/**
 * Setup formatters
 */
$byteFormatter = new ByteFormatter;

/**
 * Setup CSRF token
 */
$csrfToken = new CsrfToken;

/**
 * Setup the HTML template renderer
 */
$htmlTemplate = new Html(__DIR__ . '/template', 'page.phtml', $translator);

/**
 * Setup routing
 */
$request = explode('/', $_SERVER['REQUEST_URI']);
switch(end($request)) {
    case 'configuration':
        $content = $htmlTemplate->render('configuration.phtml', [
            'byteFormatter' => $byteFormatter,
            'active'        => 'config',
        ]);
        break;

    case 'cached-scripts':
        $content = $htmlTemplate->render('cached.phtml', [
            'byteFormatter' => $byteFormatter,
            'csrfToken'     => $csrfToken,
            'active'        => 'cached',
        ]);
        break;

    case 'graphs':
        $content = $htmlTemplate->render('graphs.phtml', [
            'byteFormatter' => $byteFormatter,
            'active'        => 'graphs',
        ]);
        break;

    case 'reset':
        ob_start();
        require __DIR__ . '/template/reset.pjson';
        return;

    case 'invalidate':
        ob_start();
        require __DIR__ . '/template/invalidate.pjson';
        return;

    default:
        $content = $htmlTemplate->render('status.phtml', [
            'byteFormatter' => $byteFormatter,
            'csrfToken'     => $csrfToken,
            'active'        => 'status',
        ]);
        break;
}

echo $content;
