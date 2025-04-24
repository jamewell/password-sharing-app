<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h1 class="text-2xl font-bold mb-6">Shared Password</h1>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Shared Password</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <input id="password" type="text" readonly value="{{ $password }}"
                                       class="flex-1 block w-full rounded-none rounded-l-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <button onclick="copyToClipboard('password')"
                                        class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 rounded-r-md bg-gray-50 text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                    Copy
                                </button>
                            </div>
                        </div>

                        @if($isLastUse)
                            <div class="p-4 bg-yellow-50 rounded-md">
                                <div class="flex">
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-yellow-800">Note</h3>
                                        <div class="mt-2 text-sm text-yellow-700">
                                            <p>This was the last use of this link. It has now expired.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function copyToClipboard(elementId) {
            const element = document.getElementById(elementId);
            element.select();
            element.setSelectionRange(0, 99999);
            document.execCommand('copy');

            // Show a temporary tooltip or notification
            const button = element.nextElementSibling;
            const originalText = button.textContent;
            button.textContent = 'Copied!';
            setTimeout(() => {
                button.textContent = originalText;
            }, 2000);
        }
    </script>
</x-app-layout>
