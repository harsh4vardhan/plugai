<x-layouts.app>
    <x-app.container x-data="usageStats()" x-init="load()" class="space-y-6" x-cloak>
        <div class="bg-white p-6 rounded-xl shadow">
            <h2 class="text-2xl font-bold mb-4">Usage Dashboard</h2>
            <p><strong>Total Requests:</strong> <span x-text="summary.total_requests"></span></p>
            <p><strong>Total Tokens Used:</strong> <span x-text="summary.total_tokens"></span></p>

            <h3 class="text-xl font-semibold mt-4">By Model</h3>
            <ul class="mt-2 list-disc list-inside">
                <template x-for="row in summary.by_model" :key="row.model_name">
                    <li>
                        <strong x-text="row.model_name"></strong> -
                        <span x-text="row.count"></span> requests,
                        <span x-text="row.tokens"></span> tokens
                    </li>
                </template>
            </ul>
        </div>
    </x-app.container>
</x-layouts.app>

<script>
    function usageStats() {
        return {
            summary: { total_requests: 0, total_tokens: 0, by_model: [] },
            async load() {
                const res = await fetch('/api/usage');
                this.summary = await res.json();
            }
        }
    }
</script>
