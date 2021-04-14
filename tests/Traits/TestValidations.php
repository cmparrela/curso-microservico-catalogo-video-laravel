<?php
namespace Tests\Traits;

use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Support\Facades\Lang;

trait TestValidations
{
    abstract protected function model();
    abstract protected function routeStore();
    abstract protected function routeUpdate();

    protected function assertInvalidationStoreAction(
        array $data,
        string $rule,
        $ruleParams = []
    ) {
        $response = $this->json('POST', $this->routeStore(), $data);

        $fields = array_keys($data);
        $this->assertInvalidationFields($response, $fields, $rule, $ruleParams);

    }

    protected function assertInvalidationUpdateAction(
        array $data,
        string $rule,
        $ruleParams = []
    ) {
        $response = $this->json('PUT', $this->routeUpdate(), $data);

        $fields = array_keys($data);
        $this->assertInvalidationFields($response, $fields, $rule, $ruleParams);

    }

    protected function assertInvalidationFields(
        TestResponse $response,
        array $fields,
        string $rule,
        array $ruleParams = []
    ) {
        $response->assertStatus(422);
        $response->assertJsonValidationErrors($fields);

        foreach ($fields as $field) {
            $fieldName = str_replace('_', ' ', $field);
            $response->assertJsonFragment([
                Lang::get("validation.$rule", ['attribute' => $fieldName] + $ruleParams),
            ]);
        }

    }
}
