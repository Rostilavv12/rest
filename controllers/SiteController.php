<?php
namespace app\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;

/**
 * @SWG\Swagger(
 *     basePath="/",
 *     produces={"application/json"},
 *     consumes={"application/json"},
 *     @SWG\Info(version="2.0", title="REST API"),
 *     @SWG\SecurityScheme (
 *          securityDefinition="Bearer",
 *          type="apiKey",
 *          name="Auth",
 *          in="header"
 *     )
 * )
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function actions(): array
    {
        return [
            'docs' => [
                'class' => 'yii2mod\swagger\SwaggerUIRenderer',
                'restUrl' => Url::to(['site/json-schema']),
            ],
            'json-schema' => [
                'class' => 'yii2mod\swagger\OpenAPIRenderer',
                // Тhe list of directories that contains the swagger annotations.
                'scanDir' => [
                    Yii::getAlias('@app/modules/v1/controllers'),
                    Yii::getAlias('@app/modules/v1/actions'),
                    Yii::getAlias('@app/controllers'),
                ],
                'cache' => YII_DEBUG ? null : true,
            ],
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
}