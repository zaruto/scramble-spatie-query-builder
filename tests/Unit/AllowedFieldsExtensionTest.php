<?php

use Illuminate\Support\Facades\Route;
use Zaruto\ScrambleSpatieQueryBuilder\AllowedFieldsExtension;

test('test AllowedFieldsExtensions', function () {

    $queryParam = 'fields';

    config()->set('query-builder.parameters.fields', $queryParam);

    $result = generateForRoute(function () {
        return Route::get('test', [
            AllowedFieldsExtensionController::class, 'a',
        ]);
    }, [
        AllowedFieldsExtension::class,
    ]);

    expect($result['paths']['/test']['get']['parameters'][0])->toBe([
        'name' => $queryParam,
        'in' => 'query',
        'schema' => [
            'anyOf' => [
                [
                    'type' => 'array',
                    'items' => [
                        'type' => 'string',
                        'enum' => [
                            'foo',
                            'bar',
                        ],
                    ],
                ],
                [
                    'type' => 'string',
                ],
            ],
        ],
        'example' => ['foo', 'bar'],
    ]);

});

class AllowedFieldsExtensionController extends \Illuminate\Routing\Controller
{
    public function a(): Illuminate\Http\Resources\Json\JsonResource
    {
        \Spatie\QueryBuilder\QueryBuilder::for(null)
            ->allowedFields(['foo', 'bar']);

        return $this->unknown_fn();
    }
}
