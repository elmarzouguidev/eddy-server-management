<?php

namespace App\Rules;

use App\Infrastructure\LinodeCloud;
use App\Infrastructure\ProviderFactory;
use App\Models\Credentials;
use App\Provider;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Str;

class LinodeCloudToken implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (config('eddy.fake_credentials_validation')) {
            if (Str::startsWith($value, 'valid-')) {
                return;
            }

            $fail(__('The API token is invalid.'));

            return;
        }

        /** @var ProviderFactory */
        $providerFactory = app(ProviderFactory::class);

        /** @var LinodeCloud */
        $linode = $providerFactory->forCredentials(new Credentials([
            'provider' => Provider::Linode,
            'credentials' => ['linode_cloud_token' => $value],
        ]));

        if (!$linode->canConnect()) {
            $fail(__('The API token is invalid and cant connect'));
        }
    }
}
