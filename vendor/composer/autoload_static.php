<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit0fe39db2fe7340d1eedd6b4082b72e2b
{
    public static $prefixLengthsPsr4 = array (
        'M' => 
        array (
            'Maneuver\\BitbucketWpUpdater\\' => 28,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Maneuver\\BitbucketWpUpdater\\' => 
        array (
            0 => __DIR__ . '/..' . '/maneuver/bitbucket-wp-updater/src',
        ),
    );

    public static $prefixesPsr0 = array (
        'P' => 
        array (
            'Parsedown' => 
            array (
                0 => __DIR__ . '/..' . '/erusev/parsedown',
            ),
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit0fe39db2fe7340d1eedd6b4082b72e2b::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit0fe39db2fe7340d1eedd6b4082b72e2b::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit0fe39db2fe7340d1eedd6b4082b72e2b::$prefixesPsr0;
            $loader->classMap = ComposerStaticInit0fe39db2fe7340d1eedd6b4082b72e2b::$classMap;

        }, null, ClassLoader::class);
    }
}
