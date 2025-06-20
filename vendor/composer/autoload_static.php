<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit944e385b424d2cfbdad4a46be8c7cf24
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'Admin\\Tldraw\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Admin\\Tldraw\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit944e385b424d2cfbdad4a46be8c7cf24::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit944e385b424d2cfbdad4a46be8c7cf24::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit944e385b424d2cfbdad4a46be8c7cf24::$classMap;

        }, null, ClassLoader::class);
    }
}
