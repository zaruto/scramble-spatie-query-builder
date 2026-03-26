<?php

namespace Zaruto\ScrambleSpatieQueryBuilder;

enum FilterModeEnum: string
{
    case Contains = 'contains';
    case StartsWith = 'starts_with';

    case EndsWith = 'ends_with';
    case Exact = 'exact';
}
