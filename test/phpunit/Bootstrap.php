<?php
/**
 * @link     https://github.com/old-town/workflow-zf2-toolkit
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\Workflow\ZF2\Toolkit\PhpUnit\Test;

use Zend\Loader\AutoloaderFactory;
use Zend\Loader\StandardAutoloader;
use RuntimeException;

error_reporting(E_ALL | E_STRICT);
chdir(__DIR__);

/**
 * Class Bootstrap
 *
 * @package OldTown\Workflow\ZF2\Toolkit\PhpUnit\Test
 */
class Bootstrap
{
    /**
     * Настройка тестов
     *
     * @throws \RuntimeException
     */
    public static function init()
    {
        static::initAutoloader();
    }


    /**
     * Инициализация автозагрузчика
     *
     * @return void
     *
     * @throws RuntimeException
     */
    protected static function initAutoloader()
    {
        $vendorPath = static::findParentPath('vendor');
        if (is_readable($vendorPath . '/autoload.php')) {

            /** @noinspection PhpIncludeInspection */
            include $vendorPath . '/autoload.php';
        }

        if (!class_exists(AutoloaderFactory::class)) {
            $errMsg = 'Ошибка инициации автолоадеров';
            throw new RuntimeException($errMsg);
        }

        try {
            AutoloaderFactory::factory([
                StandardAutoloader::class => [
                    'autoregister_zf' => true,
                    'namespaces' => [
                        'OldTown\\Workflow\\ZF2\\Toolkit' => __DIR__ . '/../../src/',
                        __NAMESPACE__ => __DIR__ . '/tests/',
                        'OldTown\\Workflow\\ZF2\\Toolkit\\PhpUnit\\TestData' => __DIR__ . '/_files',
                        'OldTown\\Workflow\\ZF2\\Toolkit\\PhpUnit\\Utils' => __DIR__ . '/utils',
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            $errMsg = 'Ошибка инициации автолоадеров';
            throw new RuntimeException($errMsg, $e->getCode(), $e);
        }
    }

    /**
     * @param $path
     *
     * @return bool|string
     */
    protected static function findParentPath($path)
    {
        $dir = __DIR__;
        $previousDir = '.';
        while (!is_dir($dir . '/' . $path)) {
            $dir = dirname($dir);
            if ($previousDir === $dir) {
                return false;
            }
            $previousDir = $dir;
        }
        return $dir . '/' . $path;
    }
}

Bootstrap::init();
