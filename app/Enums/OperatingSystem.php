<?php

namespace App\Enums;

enum OperatingSystem: string
{
    case WINDOWS_XP = 'Windows XP';
    case WINDOWS_7 = 'Windows 7';
    case WINDOWS_10 = 'Windows 10';
    case WINDOWS_11 = 'Windows 11';
    case WINDOWS_SERVER_2008 = 'Windows Server 2008';
    case WINDOWS_SERVER_2012 = 'Windows Server 2012';
    case WINDOWS_SERVER_2016 = 'Windows Server 2016';
    case WINDOWS_SERVER_2019 = 'Windows Server 2019';
    case LINUX = 'Linux';
}
