<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Instellingen') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mt-10 sm:mt-0">
                        <div class="md:grid md:grid-cols-3 md:gap-6">
                            <div class="md:col-span-1">
                                <div class="px-4 sm:px-0">
                                    <h3 class="text-lg font-medium leading-6 text-gray-900">ESS (Softbrick)</h3>
                                </div>
                                @if(Session::has('softbrick:success'))
                                    <div role="alert">
                                        <div class="bg-green-500 text-white font-bold rounded-t px-4 py-2">
                                            Succes
                                        </div>
                                        <div class="border border-t-0 border-green-400 rounded-b bg-green-100 px-4 py-3 text-green-700">
                                            <p>Je gegevens zijn opgeslagen.</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="mt-5 md:mt-0 md:col-span-2">
                                <form action="{{ route('post:softbrick') }}" method="POST">
                                    {{ csrf_field() }}
                                    <div class="shadow overflow-hidden sm:rounded-md">
                                        <div class="px-4 py-5 bg-white sm:p-6">
                                            <div class="grid grid-cols-6 gap-6">
                                                <div class="col-span-6 sm:col-span-4">
                                                    <label for="email" class="block text-sm font-medium text-gray-700">Email adres</label>
                                                    <input type="text" name="email" id="email" @isset($softbrick->email) value="{{ $softbrick->email }}" @endisset class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                                </div>
                                                <div class="col-span-6 sm:col-span-3">
                                                    <label for="first-name" class="block text-sm font-medium text-gray-700">Wachtwoord</label>
                                                    <input type="password" name="password" id="password" @isset($softbrick->password) value="{{ $softbrick->password }}" @endisset class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                Opslaan
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
