<?php

namespace App\Livewire;

class Component extends \Livewire\Component
{
    public function validateField(array|string $fields): array
    {
        $fields = is_array($fields) ? $fields : [$fields];

        $rules = $this->getRules();
        $messages = $this->getMessages();
        $attributes = $this->getValidationAttributes();
        $filtered = [];

        foreach ($rules as $ruleKey => $ruleValue) {
            foreach ($fields as $field) {
                if ($ruleKey === $field) {
                    $filtered[$ruleKey] = $ruleValue;
                    break;
                }
            }
        }

        return $this->validate($filtered, $messages, $attributes);
    }
}
