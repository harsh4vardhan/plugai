<x-layouts.app>
    <x-app.container x-data="modelManager()" x-init="init()" class="min-h-screen font-sans antialiased text-gray-800 p-6 sm:p-8 lg:p-10" x-cloak>

        <header class="max-w-7xl mx-auto mb-10 lg:mb-12">
            <p class="text-lg text-gray-600 mt-3 max-w-3xl">Effortlessly manage your integrated AI service providers and gain insights into their performance and usage patterns.</p>
        </header>

        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">

            {{-- Add New AI Model Card --}}
            <div @click="openModal" class="flex flex-col items-center justify-center p-8 bg-white border border-blue-200 rounded-2xl shadow-sm hover:shadow-md hover:border-blue-400 transition duration-300 ease-in-out cursor-pointer group transform hover:-translate-y-1">
                <div class="p-4 bg-blue-100 rounded-full mb-4 group-hover:bg-blue-500 transition duration-300">
                    <svg class="w-8 h-8 text-blue-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                </div>
                <span class="text-gray-500 font-semibold text-xl group-hover:text-blue-800 transition duration-300">Add New AI Model</span>
            </div>

            {{-- Existing AI Model Cards --}}
            <template x-for="model in models" :key="model.id">
                <div @click="viewStats(model)" class="relative bg-white border border-gray-100 rounded-2xl p-6 shadow-sm hover:shadow-md hover:border-blue-200 transition duration-300 ease-in-out cursor-pointer transform hover:-translate-y-1 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center space-x-5 mb-4">
                            {{-- Dynamic Model Logos --}}
                            <template x-if="model.provider === 'openai'">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/4/4d/OpenAI_Logo.svg/200px-OpenAI_Logo.svg.png" alt="OpenAI Logo" class="h-10 w-10 object-contain">
                            </template>
                            <template x-if="model.provider === 'gemini'">
                                <img src="https://static.cdnlogo.com/logos/g/69/google-gemini.svg" alt="Gemini Logo" class="h-10 w-10 object-contain">
                            </template>
                            <template x-if="model.provider === 'anthropic'">
                                <img src="https://static.cdnlogo.com/logos/a/69/anthropic_800x800.png" alt="Anthropic Logo" class="h-10 w-10 object-contain">
                            </template>
                            <h3 class="text-xl font-semibold  text-gray-500" x-text="model.name"></h3>
                        </div>
                        <p class="text-sm text-gray-500 mb-5" x-text="model.type.charAt(0).toUpperCase() + model.type.slice(1) + ' Provider'"></p>

                        <div class="bg-gray-50 p-3 rounded-lg border border-gray-200 text-xs text-gray-600 font-mono flex items-center justify-between group">
                            <span class="truncate">API Key: <span class="font-semibold" x-text="model.api_key.substring(0, 4) + '...'+ model.api_key.substring(model.api_key.length - 4)"></span></span>
                            <button @click.stop="copyApiKey(model.id, model.api_key)" :class="{ 'text-blue-500': model.copied, 'text-gray-400 hover:text-blue-500': !model.copied }" class="ml-2 p-1 rounded transition duration-200" :title="model.copied ? 'Copied!' : 'Copy API Key'">
                                <span x-show="!model.copied">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M14 4h2a2 0 012 2v2m-6 3l-4 4m0 0l-4 4m4-4l4 4m0 0l4 4m-4-4V4"></path></svg>
                                </span>
                                <span x-show="model.copied">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                </span>
                            </button>
                        </div>
                    </div>

                    <div class="mt-6 pt-5 border-t border-gray-100 flex justify-end">
                        <button class="text-red-600 hover:text-red-700 font-medium text-sm transition duration-200 flex items-center group" @click.stop="removeModel(model.id)">
                            <svg class="inline-block mr-1 w-4 h-4 text-red-500 group-hover:text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            Remove
                        </button>
                    </div>
                </div>
            </template>
        </div>

        {{-- Add New Model Modal --}}
        <div x-show="showModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="fixed inset-0 z-50 overflow-y-auto bg-gray-900 bg-opacity-70 flex items-center justify-center p-4">
            <div @click.away="closeModal" class="bg-white rounded-2xl shadow-xl w-full max-w-lg mx-auto transform transition-all duration-300 ease-in-out">
                <div class="px-6 py-5 border-b border-gray-200 flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-500">Add New AI Model</h2>
                    <button @click="closeModal" class="text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-full p-1.5 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <form @submit.prevent="submitModel" class="p-6 space-y-6">
                    <div>
                        <label for="provider" class="block text-sm font-medium text-gray-700 mb-2">Model Provider</label>
                        <select id="provider" x-model="form.type" @change="updateModelOptions" class="w-full border border-gray-300 rounded-lg py-2.5 px-4 text-base bg-white focus:ring-blue-500 focus:border-blue-500 transition duration-200 ease-in-out shadow-sm appearance-none pr-8">
                            <option value="" disabled selected>Select Provider</option>
                            <option value="openai">OpenAI</option>
                            <option value="gemini">Gemini</option>
                            <option value="anthropic">Anthropic</option>
                        </select>
                    </div>
                    <div>
                        <label for="model-name" class="block text-sm font-medium text-gray-700 mb-2">Model Name</label>
                        <select id="model-name" x-model="form.name" class="w-full border border-gray-300 rounded-lg py-2.5 px-4 text-base bg-white focus:ring-blue-500 focus:border-blue-500 transition duration-200 ease-in-out shadow-sm appearance-none pr-8" required>
                            <template x-for="name in availableModels" :key="name">
                                <option :value="name" x-text="name"></option>
                            </template>
                        </select>
                    </div>
                    <div>
                        <label for="api-key" class="block text-sm font-medium text-gray-700 mb-2">API Key</label>
                        <input type="text" id="api-key" x-model="form.api_key" class="w-full border border-gray-300 rounded-lg py-2.5 px-4 text-base focus:ring-blue-500 focus:border-blue-500 transition duration-200 ease-in-out shadow-sm" placeholder="sk-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx" required>
                    </div>
                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" @click="closeModal" class="px-6 py-2.5 rounded-lg text-gray-700 border border-gray-300 bg-white hover:bg-gray-50 transition duration-200 focus:outline-none focus:ring-2 focus:ring-gray-300">Cancel</button>
                        <button type="submit" class="px-6 py-2.5 rounded-lg bg-blue-600 text-white font-medium hover:bg-blue-700 transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Save Model</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Usage Stats Modal --}}
        <div x-data="{ showForm: false, newApiForm: { name: '', prompt: '', max_tokens: '', temperature: '', tag: '' , provider: '' }, currentApiModelOptions: [] }" x-show="showStatsModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="fixed inset-0 z-50 overflow-y-auto bg-gray-900 bg-opacity-70 flex items-center justify-center p-4">
            <div @click.away="closeStatsModal" class="bg-white rounded-2xl shadow-xl w-full max-w-5xl h-auto max-h-full flex flex-col transform transition-all duration-300 ease-in-out">
                <div class="px-6 py-5 border-b border-gray-200 flex items-center justify-between flex-shrink-0">
                    <h2 class="text-xl font-bold text-gray-800">Usage Stats - <span class="text-indigo-600" x-text="selectedModel?.name"></span></h2>
                    <button @click="closeStatsModal" class="text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-full p-1.5 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <div class="p-6 flex-grow overflow-y-auto custom-scrollbar">
                    <div x-show="stats" class="space-y-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div class="bg-indigo-50 p-6 rounded-xl border border-indigo-100 shadow-sm flex items-center justify-between">
                                <div>
                                    <h3 class="font-medium text-sm text-indigo-800 mb-1">Total Requests</h3>
                                    <p class="text-4xl font-extrabold text-indigo-700" x-text="stats.total_requests"></p>
                                </div>
                                <svg class="w-12 h-12 text-indigo-400 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                            </div>
                            <div class="bg-green-50 p-6 rounded-xl border border-green-100 shadow-sm flex items-center justify-between">
                                <div>
                                    <h3 class="font-medium text-sm text-green-800 mb-1">Total Tokens Used</h3>
                                    <p class="text-4xl font-extrabold text-green-700" x-text="stats.total_tokens"></p>
                                </div>
                                <svg class="w-12 h-12 text-green-400 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            </div>
                            <div class="bg-yellow-50 p-6 rounded-xl border border-yellow-100 shadow-sm flex items-center justify-between">
                                <div>
                                    <h3 class="font-medium text-sm text-yellow-800 mb-1">Avg. Tokens per Request</h3>
                                    <p class="text-4xl font-extrabold text-yellow-700" x-text="stats.average_tokens_per_request ? stats.average_tokens_per_request.toFixed(0) : 'N/A'"></p>
                                </div>
                                <svg class="w-12 h-12 text-yellow-400 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3m0 0l3 3m-3-3v8m0-9a9 9 0 110 18A9 9 0 017 12z"></path></svg>
                            </div>
                        </div>
                        <button @click="showForm = !showForm; if(showForm) { newApiForm.provider = ''; newApiForm.name = ''; currentApiModelOptions = []; }"
                                class="ml-4 inline-flex items-center px-4 py-2 my-4 border border-indigo-600 text-indigo-600 text-sm font-medium rounded-lg hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Add API
                        </button>
                        <div x-show="showForm" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                             class="mt-8 bg-white border border-gray-200 rounded-xl p-6 shadow-md relative">
                            <button @click="showForm = false"
                                    class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded-full p-1.5 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>

                            <h3 class="text-xl font-semibold text-gray-800 mb-4">Add New API Configuration</h3> {{-- Adjusted text size --}}

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4"> {{-- Adjusted gap classes for better spacing --}}
                                <div>
                                    <label for="new-api-provider" class="block text-sm font-medium text-gray-700">Provider</label>
                                    <select id="new-api-provider" x-model="newApiForm.provider" @change="currentApiModelOptions = modelOptions[newApiForm.provider] || []; newApiForm.name = '';"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 p-2 text-sm">
                                        <option value="" disabled selected>Select Provider</option>
                                        <template x-for="providerKey in Object.keys(modelOptions)" :key="providerKey">
                                            <option :value="providerKey" x-text="providerKey.charAt(0).toUpperCase() + providerKey.slice(1)"></option>
                                        </template>
                                    </select>
                                </div>
                                <div>
                                    <label for="new-api-model-name" class="block text-sm font-medium text-gray-700">Model Name</label>
                                    <select id="new-api-model-name" x-model="newApiForm.name"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 p-2 text-sm">
                                        <option value="" disabled selected>Select Model</option>
                                        <template x-for="modelName in currentApiModelOptions" :key="modelName">
                                            <option :value="modelName" x-text="modelName"></option>
                                        </template>
                                    </select>
                                </div>
                                <div class="md:col-span-2"> {{-- Make prompt span full width for better layout --}}
                                    <label for="new-api-prompt" class="block text-sm font-medium text-gray-700">Base Prompt</label>
                                    <textarea id="new-api-prompt" x-model="newApiForm.prompt" rows="3"
                                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 p-2 text-sm" placeholder="e.g., You are a highly intelligent and helpful AI assistant."></textarea>
                                </div>
                                <div>
                                    <label for="new-api-max-tokens" class="block text-sm font-medium text-gray-700">Max Tokens</label>
                                    <input type="number" id="new-api-max-tokens" x-model="newApiForm.max_tokens"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 p-2 text-sm" placeholder="e.g., 2048"/>
                                </div>
                                <div>
                                    <label for="new-api-temperature" class="block text-sm font-medium text-gray-700">Temperature</label>
                                    <input type="number" step="0.01" id="new-api-temperature" x-model="newApiForm.temperature"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 p-2 text-sm" placeholder="e.g., 0.7"/>
                                </div>
                                <div>
                                    <label for="new-api-tag" class="block text-sm font-medium text-gray-700">Api tag</label>
                                    <input type="text" id="new-api-tag" x-model="newApiForm.tag"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 p-2 text-sm" placeholder="tag to be used in your api call"/>
                                </div>
                            </div>

                            <div class="pt-6 text-right"> {{-- Adjusted padding for better spacing --}}
                                <button @click="submitNewApiConfiguration"
                                        class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-medium text-base rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V9m-7 4l-3-3m0 0l3-3m-3 3h8m-7 4v-3m0 0V9a2 2 0 012-2h2a2 2 0 012 2v4"></path>
                                    </svg>
                                    Save API Configuration
                                </button>
                            </div>
                        </div>
                        <div class="bg-white p-5 rounded-xl shadow my-4">
                            <h3 class="text-lg font-semibold text-gray-800 mb-3">Endpoints</h3>

                            <template x-if="userApis.length === 0">
                                <p class="text-gray-500 text-sm">No APIs found.</p>
                            </template>

                            <template x-for="(api, index) in userApis.filter(api => api.provider === selectedProvider)" :key="api.id">
                                <div  class="border rounded-lg mb-4 overflow-hidden">
                                    <button @click="api.expanded = !api.expanded"
                                            class="w-full flex items-center justify-between px-4 py-3 hover:bg-gray-200">
                                        <span class="font-medium text-gray-700" x-text="api.tag || 'Untitled API'"></span>
                                        <svg :class="{ 'rotate-180': api.expanded }" class="w-5 h-5 transition-transform"
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>
                                    <div x-show="api.expanded" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90" class="bg-white p-6 rounded-lg shadow-xl">
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                                            <!-- Provider -->
                                            <div class="col-span-1">
                                                <label class="block text-sm font-semibold text-gray-700 mb-1">Provider</label>

                                                <div x-show="!api.editingProvider" class="flex items-center justify-between">
                                                    <span class="text-gray-800" x-text="api.provider || 'Not set'"></span>
                                                    <button @click="api.editingProvider = true" class="text-sm text-indigo-600 hover:underline ml-2">Edit</button>
                                                </div>

                                                <div x-show="api.editingProvider" class="flex items-center space-x-2 mt-1">
                                                    <select x-model="api.provider"
                                                            class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2">
                                                        <template x-for="(models, provider) in modelOptions" :key="provider">
                                                            <option :value="provider" x-text="provider.charAt(0).toUpperCase() + provider.slice(1)"></option>
                                                        </template>
                                                    </select>
                                                    <button @click="api.editingProvider = false" class="text-sm text-gray-600">Done</button>
                                                </div>
                                            </div>

                                            <!-- Model -->
                                            <div class="col-span-1">
                                                <label class="block text-sm font-semibold text-gray-700 mb-1">Model</label>

                                                <!-- Viewing mode -->
                                                <template x-if="!api.editingModel">
                                                    <div class="flex items-center justify-between">
                                                        <span x-text="api.mode_name" class="text-gray-800 font-medium"></span>
                                                        <button @click="api.temp_mode_name = api.mode_name; api.editingModel = true" class="ml-2 text-indigo-600 text-sm hover:underline">
                                                            Edit
                                                        </button>
                                                    </div>
                                                </template>

                                                <!-- Editing mode -->
                                                <template x-if="api.editingModel">
                                                    <div class="flex items-center space-x-2">
                                                        <select x-model="api.temp_mode_name"
                                                                class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2">
                                                            <template x-for="modelName in (modelOptions[api.provider] || [])" :key="modelName">
                                                                <option :value="modelName" x-text="modelName"></option>
                                                            </template>
                                                        </select>
                                                        <button @click="api.mode_name = api.temp_mode_name; api.editingModel = false"
                                                                class="text-sm text-green-600 hover:underline">Save</button>
                                                        <button @click="api.editingModel = false"
                                                                class="text-sm text-gray-500 hover:underline">Cancel</button>
                                                    </div>
                                                </template>
                                            </div>

                                            <div class="col-span-1">
                                                <label :for="`prompt-input-${api.id}`" class="block text-sm font-semibold text-gray-700 mb-1">Prompt</label>
                                                <input type="text" :id="`prompt-input-${api.id}`" x-model="api.prompt" placeholder="Enter your prompt here..." class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2" />
                                            </div>

                                            <div class="col-span-1">
                                                <label :for="`tag-input-${api.id}`" class="block text-sm font-semibold text-gray-700 mb-1">Tag</label>
                                                <input type="text" :id="`tag-input-${api.id}`" x-model="api.tag" placeholder="Add a tag..." class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2" />
                                            </div>

                                            <div class="col-span-1">
                                                <label :for="`temperature-range-${api.id}`" class="block text-sm font-semibold text-gray-700 mb-1">Temperature</label>
                                                <input type="range" :id="`temperature-range-${api.id}`" min="0" max="2" step="0.1" x-model="api.temperature" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2" />
                                                <div class="text-sm text-gray-500 mt-2">Value: <span class="font-medium text-indigo-600" x-text="api.temperature ?? '0.0'"></span></div>
                                            </div>

                                            <div class="col-span-1">
                                                <label :for="`max-tokens-range-${api.id}`" class="block text-sm font-semibold text-gray-700 mb-1">Max Tokens</label>
                                                <input type="range" :id="`max-tokens-range-${api.id}`" min="1" max="4000" step="1" x-model="api.max_tokens" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2" />
                                                <div class="text-sm text-gray-500 mt-2">Value: <span class="font-medium text-indigo-600" x-text="api.max_tokens"></span></div>
                                            </div>

                                            <div class="col-span-1">
                                                <label class="block text-sm font-semibold text-gray-700 mb-1">API Count</label>
                                                <div class="text-base text-gray-800 font-medium py-2 px-3 rounded-md bg-gray-50 border border-gray-200">
                                                    <span x-text="api.api_count"></span>
                                                </div>
                                            </div>

                                            <div class="col-span-1">
                                                <label class="block text-sm font-semibold text-gray-700 mb-1">Tokens Used</label>
                                                <div class="text-base text-gray-800 font-medium py-2 px-3 rounded-md bg-gray-50 border border-gray-200">
                                                    <span x-text="api.tokens_used"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="text-right mt-8 pt-4 border-t border-gray-200">
                                            <button @click="updateApi(api)" class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                                                Save Changes
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                            <h3 class="font-semibold text-lg text-gray-800 mb-4">Daily Usage Trend (Requests)</h3>
                            <div class="relative h-60"> <canvas id="usageChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <div x-show="!stats" class="text-center text-gray-500 py-10">
                        <svg class="animate-spin h-10 w-10 text-blue-500 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <p class="text-lg font-medium">Loading usage data...</p>
                        <p class="text-sm mt-2">This might take a moment if the model has a lot of history.</p>
                    </div>
                </div>
            </div>
        </div>
    </x-app.container>
</x-layouts.app>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function modelManager() {
        return {
            showModal: false,
            models: [],
            form: { // Used for 'Add New AI Model' modal
                name: '',
                type: '',
                api_key: ''
            },
            // newApiForm and currentApiModelOptions are now part of the main scope for the 'Add API Configuration' form
            showForm: false, // Controls visibility of the 'Add API Configuration' form within the stats modal
            newApiForm: { // Used for the 'Add API Configuration' form within the stats modal
                name: '',
                prompt: '',
                max_tokens: '',
                temperature: '',
                tag: '' ,
                provider: ''
            },
            currentApiModelOptions: [], // For the 'Add API Configuration' form

            availableModels: [], // For the 'Add New AI Model' modal

            modelOptions: {
                openai: ['gpt-3.5-turbo', 'gpt-4', 'gpt-4o', 'dall-e-3'],
                gemini: ['gemini-pro', 'gemini-1.5-pro', 'gemini-2.0-flash'],
                anthropic: ['claude-3-opus-20240229', 'claude-3-sonnet-20240229', 'claude-3-haiku-20240307']
            },
            userApis: [],
            fetchUserApis() {
                // Fetch APIs for the selected model
                if (!this.selectedModel) return;

                // You might need an actual endpoint like /user-api?model_id={id}
                // or /models/{id}/user-apis if your backend is structured that way
                fetch(`/user-api`) // Assuming an endpoint to fetch APIs for a specific model
                    .then(res => {
                        if (!res.ok) throw new Error('Failed to fetch user APIs for model.');
                        return res.json();
                    })
                    .then(data => {
                        this.userApis = data.map(api => ({
                            ...api,
                            expanded: false,
                            provider: api.provider,
                            // Ensure mode_name is set, default to empty string if null/undefined
                            mode_name: api.mode_name || '',
                            editingProvider: false,
                            editingModel: false,
                            temp_mode_name: '', // ← we’ll use this,
                        }));
                        console.log('User APIs loaded:', this.userApis);

                    })
                    .catch(error => {
                        console.error('Error loading user APIs:', error);
                        alert('Failed to load user APIs.');
                    });
            },

            async updateApi(api) {
                // Apply temporary values if in edit mode
                if (api.editingModel) {
                    api.mode_name = api.temp_mode_name;
                    api.editingModel = false;
                }

                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                const headers = {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                };

                const payload = {
                    provider: api.provider,
                    tag: api.tag,
                    prompt: api.prompt,
                    temperature: api.temperature,
                    tokens_used: api.tokens_used,
                    api_count: api.api_count,
                    max_tokens: api.max_tokens,
                    mode_name: api.mode_name
                };
                console.log('Updating API:', payload);
                try {
                    const res = await fetch(`/user-api/${api.id}`, {
                        method: 'PUT',
                        headers,
                        body: JSON.stringify(payload)
                    });

                    if (!res.ok) {
                        const errorData = await res.json();
                        throw new Error(errorData.message || 'Update failed.');
                    }

                    alert('API updated successfully.');
                } catch (error) {
                    console.error('Update failed:', error);
                    alert('Failed to update API: ' + error.message);
                }
            }

            ,

            async init() {
                if (typeof Chart === 'undefined') {
                    console.error('Chart.js is not loaded.');
                    return;
                }
                // Initial update for the 'Add New AI Model' modal provider
                this.updateModelOptions();

                try {
                    const res = await fetch('/ai-models');
                    if (!res.ok) {
                        throw new Error(`HTTP error! status: ${res.status}`);
                    }
                    this.models = (await res.json()).map(model => ({ ...model, copied: false }));
                } catch (error) {
                    console.error('Failed to fetch models:', error);
                }
            },

            showStatsModal: false,
            selectedModel: null,
            stats: null,
            usageChartInstance: null,
            selectedProvider: null,
            async viewStats(model) {
                this.selectedModel = model;
                this.showStatsModal = true;
                this.stats = null; // Clear previous stats to show loading state
                this.showForm = false; // Hide the add API form initially
                this.newApiForm = { name: '', prompt: '', max_tokens: '', temperature: '', tag: '' , provider: ''}; // Reset new API form
                this.currentApiModelOptions = []; // Clear model options for the new API form
                this.selectedProvider = model.provider;
                // Fetch APIs specific to this model
                this.fetchUserApis();

                // Destroy previous chart instance if it exists
                if (this.usageChartInstance) {
                    this.usageChartInstance.destroy();
                    this.usageChartInstance = null;
                }

                try {
                    const apiToken = document.querySelector('meta[name="api-token"]')?.getAttribute('content');
                    const headers = { 'Content-Type': 'application/json' };
                    if (apiToken) {
                        headers['Authorization'] = 'Bearer ' + apiToken;
                    }

                    const res = await fetch(`/models/${model.id}/stats`, { headers });

                    if (res.ok) {
                        this.stats = await res.json();
                        if (this.stats.total_requests > 0) {
                            this.stats.average_tokens_per_request = this.stats.total_tokens / this.stats.total_requests;
                        } else {
                            this.stats.average_tokens_per_request = 0;
                        }

                        this.$nextTick(() => {
                            if (this.showStatsModal && this.stats) { // Ensure modal is still open and stats loaded
                                this.renderChart(this.stats.daily_counts || []);
                            }
                        });
                    } else {
                        const errorData = await res.json();
                        alert('Failed to load stats: ' + (errorData.message || 'Unknown error'));
                        this.stats = null;
                    }
                } catch (error) {
                    console.error('Error fetching stats:', error);
                    alert('An error occurred while fetching stats.');
                    this.stats = null;
                }
            },

            closeStatsModal() {
                this.showStatsModal = false;
                this.stats = null;
                this.selectedModel = null;
                this.userApis = []; // Clear user APIs when closing
                this.showForm = false; // Hide add API form
                this.newApiForm = { name: '', prompt: '', max_tokens: '', temperature: '', tag: '' , provider: ''}; // Reset new API form
                this.currentApiModelOptions = []; // Clear model options for the new API form
                if (this.usageChartInstance) {
                    this.usageChartInstance.destroy();
                    this.usageChartInstance = null;
                }
            },

            renderChart(data) {
                const ctx = document.getElementById('usageChart');
                if (!ctx) {
                    console.error('Chart canvas element not found.');
                    return;
                }

                if (this.usageChartInstance) {
                    this.usageChartInstance.destroy();
                }

                this.usageChartInstance = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.map(d => d.date),
                        datasets: [{
                            label: 'Requests per Day',
                            data: data.map(d => d.count),
                            fill: true,
                            borderColor: 'rgb(99, 102, 241)', // Tailwind indigo-500
                            backgroundColor: 'rgba(99, 102, 241, 0.1)', // Light indigo fill
                            tension: 0.4,
                            pointBackgroundColor: 'rgb(99, 102, 241)',
                            pointBorderColor: '#fff',
                            pointHoverBackgroundColor: '#fff',
                            pointHoverBorderColor: 'rgb(99, 102, 241)',
                            pointRadius: 5,
                            pointHoverRadius: 8,
                            borderWidth: 2,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false, // Important for fixed height parent
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top',
                                labels: {
                                    color: '#4b5563',
                                    font: {
                                        size: 14,
                                        weight: '500'
                                    }
                                }
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false,
                                backgroundColor: 'rgba(17, 24, 39, 0.9)',
                                titleFont: { size: 15, weight: 'bold' },
                                bodyFont: { size: 14 },
                                padding: 12,
                                cornerRadius: 8,
                                displayColors: false,
                                callbacks: {
                                    title: function(context) {
                                        return 'Date: ' + context[0].label;
                                    },
                                    label: function(context) {
                                        return 'Requests: ' + context.parsed.y;
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    color: '#6b7280',
                                    font: {
                                        size: 13
                                    }
                                }
                            },
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: '#e5e7eb',
                                    borderDash: [5, 5]
                                },
                                ticks: {
                                    color: '#6b7280',
                                    precision: 0,
                                    font: {
                                        size: 13
                                    }
                                }
                            }
                        }
                    }
                });
            },

            openModal() {
                this.showModal = true;
                this.form.type = 'openai'; // Default provider for new AI model
                this.updateModelOptions();
            },

            closeModal() {
                this.showModal = false;
                this.form = { name: '', type: '', api_key: '' };
                this.availableModels = [];
            },

            // This method is for the 'Add New AI Model' modal
            updateModelOptions() {
                console.log('reached here in updateModelOptions');
                this.availableModels = this.modelOptions[this.form.type] || [];
                this.form.name = this.availableModels.length > 0 ? this.availableModels[0] : '';
            },

            // This method is for the 'Add New API Configuration' form within the stats modal
            async submitNewApiConfiguration() {
                try {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                    const headers = {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    };
                    if (csrfToken) {
                        headers['X-CSRF-TOKEN'] = csrfToken;
                    }

                    // Add the model_id of the currently selected AI model
                    const payload = {
                        ...this.newApiForm,
                        model_id: this.selectedModel.id // Associate the new API config with the currently viewed model
                    };

                    const res = await fetch('/user-api', {
                        method: 'POST',
                        headers: headers,
                        body: JSON.stringify(payload)
                    });

                    if (!res.ok) {
                        const error = await res.json();
                        throw new Error(error.message || 'Something went wrong');
                    }

                    const newApi = await res.json();
                    this.userApis.push({ ...newApi, expanded: false }); // Add the new API to the list of APIs for the current model
                    this.showForm = false; // Hide the form
                    this.newApiForm = { name: '', prompt: '', max_tokens: '', temperature: '', tag: '' , provider: ''}; // Reset the form
                    this.currentApiModelOptions = []; // Clear model options for the new API form
                    alert('API configuration saved successfully!');
                } catch (error) {
                    alert('Error saving API configuration: ' + error.message);
                    console.error('Error saving API configuration:', error);
                }
            },

            async removeModel(id) {
                if (!confirm('Are you sure you want to remove this model? This action cannot be undone.')) {
                    return;
                }
                try {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                    const headers = {};
                    if (csrfToken) {
                        headers['X-CSRF-TOKEN'] = csrfToken;
                    }

                    const res = await fetch(`/ai-models/${id}`, {
                        method: 'DELETE',
                        headers: headers
                    });

                    if (!res.ok) {
                        const error = await res.json();
                        throw new Error(error.message || 'Something went wrong');
                    }

                    this.models = this.models.filter(m => m.id !== id);
                } catch (error) {
                    alert('Error removing model: ' + error.message);
                    console.error('Error removing model:', error);
                }
            },

            async submitModel() {
                try {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                    const headers = { 'Content-Type': 'application/json' };
                    if (csrfToken) {
                        headers['X-CSRF-TOKEN'] = csrfToken;
                    }

                    const res = await fetch('/ai-models', {
                        method: 'POST',
                        headers: headers,
                        body: JSON.stringify(this.form)
                    });

                    if (!res.ok) {
                        const error = await res.json();
                        throw new Error(error.message || 'Something went wrong');
                    }

                    const newModel = await res.json();
                    this.models.push({ ...newModel, copied: false }); // Ensure new models also have the 'copied' state
                    this.closeModal();
                } catch (error) {
                    alert('Error adding model: ' + error.message);
                    console.error('Error adding model:', error);
                }
            },

            copyApiKey(modelId, apiKey) {
                navigator.clipboard.writeText(apiKey)
                    .then(() => {
                        const model = this.models.find(m => m.id === modelId);
                        if (model) {
                            model.copied = true;
                            setTimeout(() => {
                                model.copied = false;
                            }, 2000);
                        }
                    })
                    .catch(err => {
                        console.error('Failed to copy API Key: ', err);
                        alert('Failed to copy API Key.');
                    });
            }
        }
    }
</script>

<style>
    /* Custom Scrollbar for the Stats Modal */
    .custom-scrollbar::-webkit-scrollbar {
        width: 10px;
        height: 10px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f0f4f8; /* Light gray background */
        border-radius: 10px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #cbd5e0; /* Gray-400 thumb */
        border-radius: 10px;
        border: 3px solid #f0f4f8; /* Border to match track background */
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #a0aec0; /* Gray-500 on hover */
    }
</style>
