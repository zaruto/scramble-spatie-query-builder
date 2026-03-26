<?php

use Exonn\ScrambleSpatieQueryBuilder\AllowedSortsExtension;
use Illuminate\Support\Facades\Route;

test('test AllowedSortsExtensions', function () {

    $queryParam = 'sort';

    config()->set('query-builder.parameters.sort', $queryParam);

    $result = generateForRoute(function () {
        return Route::get('test', [
            AllowedSortsExtensionController::class, 'a',
        ]);
    }, [
        AllowedSortsExtension::class,
    ]);

    expect($result['paths']['/test']['get']['parameters'][0])->toBe([
        'name' => $queryParam,
        'in' => 'query',
        'description' => 'Sort the results by the given fields. Available fields: `foo`, `bar`, `-foo`, `-bar`. You can sort by multiple options by separating them with a comma. To sort in descending order, use - sign in front of the sort, for example: `-name`.',
        'schema' => [
            'type' => 'string',
        ],
        'example' => ['title', '-title', 'title,-id'],
    ]);

});

class AllowedSortsExtensionController extends \Illuminate\Routing\Controller
{
    public function a(): Illuminate\Http\Resources\Json\JsonResource
    {
        \Spatie\QueryBuilder\QueryBuilder::for(null)
            ->allowedSorts(['foo', 'bar']);

        return $this->unknown_fn();
    }
}
