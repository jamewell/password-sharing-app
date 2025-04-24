<div>
    <form wire:submit="generateLink" class="space-y-6">
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <input
                wire:model="password"
                type="password"
                id="password"
                name="password"
                required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="max_uses" class="block text-sm font-medium text-gray-700">Link Usage</label>
            <select wire:model="max_uses" name="max_uses" id="max_uses"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @foreach($maxUsesOptions as $option)
                    <option value="{{ $option->value }}">{{ $option->value }}</option>
                @endforeach
            </select>
            @error('max_uses') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="expiration_time" class="block text-sm font-medium text-gray-700">Link Expiration Time</label>
            <select wire:model="expiration_time" name="expiration_time" id="expiration_time"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm text-sm">
                @foreach($expirationTimeOptions as $option)
                    <option value="{{ $option['value'] }}">{{ $option['label'] }}</option>
                @endforeach
            </select>
            @error('expiration_time') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <button
                class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                Generate Link
            </button>
        </div>
    </form>

    @if($generatedUrl)
        <div class="mt-6 p-4 bg-green-50 rounded-md">
            <h3 class="text-sm font-medium text-green-800">Your sharing link has been generated!</h3>
            <div class="mt-2 text-sm text-green-700">
                <p>Share this link securely. It will expire after use or when the time limit is reached.</p>
                <div class="mt-2 flex">
                    <input wire:model="generatedUrl" type="text" readonly
                           class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <button @click="navigator.clipboard.writeText('{{ $generatedUrl }}')"
                            class="ml-2 inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Copy
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if($this->getErrorBag()->any())
        <div class="mt-4 p-4 bg-red-50 rounded-md">
            <h3 class="text-sm font-medium text-red-800">There were errors with your submission</h3>
            <div class="mt-2 text-sm text-red-700">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach($this->getErrorBag()->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
</div>
