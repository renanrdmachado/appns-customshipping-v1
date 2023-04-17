<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Informações sobre sua assinatura') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Você deve ter uma assinatura ativa para usufruir deste APP.") }}
        </p>
    </header>

    <div>
        <p class="mt-6 text-gray-600 dark:text-gray-400">
            <x-input-label for="payments-plan" :value="__('Plano')" />
            <input id="payments-plan" type="radio" name="payments-plan" class="" value="monthly" checked>
            {{ __("R$49,90 por mês") }}
        </p>
    </div>

    <form action="{{ Route('payments.save') }}" method="POST" class="mt-6 space-y-6" id="payments-save">
        @csrf
        
        <div>
            <x-input-label for="name" :value="__('Nome')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', '')" required />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('E-mail')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', '')" required />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        <div>
            <x-input-label for="phone" :value="__('Telefone')" />
            <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', '')" required />
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>

        <div>
            <x-input-label for="cpfCnpj" :value="__('CPF / CNPJ')" />
            <x-text-input id="cpfCnpj" name="cpfCnpj" type="text" class="mt-1 block w-full" :value="old('cpfCnpj', '')" required />
            <x-input-error class="mt-2" :messages="$errors->get('cpfCnpj')" />
        </div>

        <div>
        <p class="mt-6 text-gray-600 dark:text-gray-400">
            <input type="checkbox" name="payments-consent" class="" value="on" required>
            Ao prosseguir, você declara estar ciente e de acordo com nossas <a href="{{ Route('policy') }}" class="underline">políticas</a>.
        </p>
    </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Assinar') }}</x-primary-button>

            @if (session('status') === 'payments-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
    
</section>
